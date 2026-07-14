<?php
/**
 * Minimal SMTP mailer + Google reCAPTCHA v3 verifier.
 * No Composer required — uses native PHP sockets and stream_socket_enable_crypto.
 *
 * Configured via SMTP_* and RECAPTCHA_* constants in config.php.
 */

declare(strict_types=1);

require_once __DIR__ . '/config.php';

/* ---------------------------------------------------------------------------
 *  SMTP client
 * ------------------------------------------------------------------------- */

final class SmtpMailer
{
    private string $host;
    private int    $port;
    private string $username;
    private string $password;
    private string $encryption; // 'tls' (STARTTLS), 'ssl', or 'none'
    private int    $timeout;
    private string $localHost;

    /** @var resource|null */
    private $socket = null;

    public function __construct(array $config = [])
    {
        $this->host       = (string) ($config['host']       ?? 'smtp.gmail.com');
        $this->port       = (int)    ($config['port']       ?? 587);
        $this->username   = (string) ($config['username']   ?? '');
        $this->password   = (string) ($config['password']   ?? '');
        $this->encryption = strtolower((string) ($config['encryption'] ?? 'tls'));
        $this->timeout    = (int)    ($config['timeout']    ?? 30);
        $this->localHost  = (string) ($config['local_host'] ?? ($_SERVER['SERVER_NAME'] ?? 'localhost'));
    }

    /**
     * Send a plain-text message.
     *
     * Required keys in $message:
     *   from        string  envelope sender
     *   from_name   string  display name
     *   to          string|string[]  recipient address(es)
     *   subject     string
     *   body        string  plain-text body
     * Optional:
     *   reply_to    string
     */
    public function send(array $message): bool
    {
        $recipients = (array) $message['to'];
        if (!$recipients) {
            throw new RuntimeException('SMTP send: no recipients');
        }

        $this->connect();
        try {
            $this->handshakeAndAuth();

            // Envelope
            $this->cmd('MAIL FROM:<' . $message['from'] . '>');
            $this->expect('250');
            foreach ($recipients as $to) {
                $this->cmd('RCPT TO:<' . $to . '>');
                $this->expect(['250', '251']);
            }

            // Data
            $this->cmd('DATA');
            $this->expect('354');

            [$headers, $body] = $this->buildMessage($message, $recipients);
            $body = $this->dotStuff(self::normaliseEol($body));

            // Send headers + blank line + body + final ".\r\n"
            $this->writeRaw($headers . "\r\n\r\n" . $body . "\r\n.\r\n");
            $this->expect('250');

            $this->cmd('QUIT');
            return true;
        } finally {
            $this->disconnect();
        }
    }

    /** Greeting + EHLO + STARTTLS (when configured) + AUTH LOGIN. */
    private function handshakeAndAuth(): void
    {
        $this->expect('220');
        $this->cmd('EHLO ' . $this->localHost);
        $this->expect('250');

        if ($this->encryption === 'tls') {
            $this->cmd('STARTTLS');
            $this->expect('220');

            $cryptoMethods = STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT;
            if (defined('STREAM_CRYPTO_METHOD_TLSv1_3_CLIENT')) {
                $cryptoMethods |= STREAM_CRYPTO_METHOD_TLSv1_3_CLIENT;
            }
            if (!stream_socket_enable_crypto($this->socket, true, $cryptoMethods)) {
                throw new RuntimeException('SMTP: failed to enable TLS');
            }

            // RFC 3207: re-EHLO after STARTTLS.
            $this->cmd('EHLO ' . $this->localHost);
            $this->expect('250');
        }

        // AUTH LOGIN
        $this->cmd('AUTH LOGIN');
        $this->expect('334');
        $this->cmd(base64_encode($this->username));
        $this->expect('334');
        $this->cmd(base64_encode($this->password));
        $this->expect('235');
    }

    /**
     * Connect and authenticate, then QUIT — proves the host, port, encryption
     * and credentials are right without sending any email. Throws on failure.
     */
    public function verifyAuth(): void
    {
        $this->connect();
        try {
            $this->handshakeAndAuth();
            $this->cmd('QUIT');
        } finally {
            $this->disconnect();
        }
    }

    private function connect(): void
    {
        $context = stream_context_create([
            'ssl' => [
                'verify_peer'       => true,
                'verify_peer_name'  => true,
                'SNI_enabled'       => true,
                'allow_self_signed' => false,
            ],
        ]);

        $address = ($this->encryption === 'ssl' ? 'ssl://' : '') . $this->host . ':' . $this->port;

        $errno  = 0;
        $errstr = '';
        $sock   = @stream_socket_client(
            $address,
            $errno,
            $errstr,
            $this->timeout,
            STREAM_CLIENT_CONNECT,
            $context
        );

        if (!$sock) {
            throw new RuntimeException("SMTP connect to {$address} failed: {$errstr} ({$errno})");
        }

        stream_set_timeout($sock, $this->timeout);
        $this->socket = $sock;
    }

    private function disconnect(): void
    {
        if (is_resource($this->socket)) {
            @fclose($this->socket);
        }
        $this->socket = null;
    }

    private function cmd(string $line): void
    {
        $this->writeRaw($line . "\r\n");
    }

    private function writeRaw(string $data): void
    {
        if (!is_resource($this->socket)) {
            throw new RuntimeException('SMTP: socket not open');
        }
        $written = @fwrite($this->socket, $data);
        if ($written === false) {
            throw new RuntimeException('SMTP: failed to write to socket');
        }
    }

    /**
     * Read a multiline SMTP response and assert its code.
     * @param string|string[] $expectedCode
     */
    private function expect($expectedCode): string
    {
        $codes    = (array) $expectedCode;
        $response = '';
        while (!feof($this->socket)) {
            $line = fgets($this->socket, 515);
            if ($line === false) {
                break;
            }
            $response .= $line;
            // "NNN " ends multiline; "NNN-" continues
            if (strlen($line) >= 4 && $line[3] === ' ') {
                break;
            }
        }

        $actual = substr($response, 0, 3);
        if (!in_array($actual, $codes, true)) {
            throw new RuntimeException(
                'SMTP: expected ' . implode('/', $codes) . ', got ' . trim($response)
            );
        }
        return $response;
    }

    /**
     * Build the message headers + body. Returns [headersString, bodyString].
     * If 'body_html' is provided we emit a multipart/alternative payload so
     * recipients see the rich HTML version when their client supports it and
     * the plain-text fallback otherwise.
     *
     * @param string[] $recipients
     * @return array{0:string,1:string}
     */
    private function buildMessage(array $message, array $recipients): array
    {
        $fromName    = (string) ($message['from_name'] ?? '');
        $fromHeader  = $fromName !== ''
            ? sprintf('"%s" <%s>', self::encodeHeader($fromName), $message['from'])
            : $message['from'];
        $toHeader    = implode(', ', array_map(static fn (string $a) => '<' . $a . '>', $recipients));
        $messageHost = parse_url(defined('BRAND_SITE_URL') ? BRAND_SITE_URL : 'localhost', PHP_URL_HOST) ?: 'localhost';
        $messageId   = '<' . bin2hex(random_bytes(8)) . '@' . $messageHost . '>';

        $headers = [
            'Date: '       . date('r'),
            'From: '       . $fromHeader,
            'To: '         . $toHeader,
            'Subject: '    . self::encodeHeader((string) $message['subject']),
            'MIME-Version: 1.0',
            'Message-ID: ' . $messageId,
            'X-Mailer: SmtpMailer/1.0 (PHP)',
        ];

        if (!empty($message['reply_to'])) {
            $headers[] = 'Reply-To: <' . $message['reply_to'] . '>';
        }

        $textBody = (string) ($message['body']      ?? '');
        $htmlBody = (string) ($message['body_html'] ?? '');

        if ($htmlBody === '') {
            $headers[] = 'Content-Type: text/plain; charset=UTF-8';
            $headers[] = 'Content-Transfer-Encoding: 8bit';
            return [implode("\r\n", $headers), $textBody];
        }

        // multipart/alternative
        $boundary = 'iph-' . bin2hex(random_bytes(8));
        $headers[] = 'Content-Type: multipart/alternative; boundary="' . $boundary . '"';

        $body  = "This is a multi-part message in MIME format.\r\n\r\n";
        $body .= '--' . $boundary . "\r\n";
        $body .= "Content-Type: text/plain; charset=UTF-8\r\n";
        $body .= "Content-Transfer-Encoding: 8bit\r\n\r\n";
        $body .= $textBody . "\r\n\r\n";
        $body .= '--' . $boundary . "\r\n";
        $body .= "Content-Type: text/html; charset=UTF-8\r\n";
        $body .= "Content-Transfer-Encoding: 8bit\r\n\r\n";
        $body .= $htmlBody . "\r\n\r\n";
        $body .= '--' . $boundary . "--\r\n";

        return [implode("\r\n", $headers), $body];
    }

    private static function encodeHeader(string $value): string
    {
        if (preg_match('/[^\x20-\x7E]/', $value)) {
            return '=?UTF-8?B?' . base64_encode($value) . '?=';
        }
        return $value;
    }

    private static function dotStuff(string $body): string
    {
        // RFC 5321 §4.5.2 — lines starting with "." must be doubled.
        return preg_replace('/^\./m', '..', $body);
    }

    private static function normaliseEol(string $body): string
    {
        $body = str_replace(["\r\n", "\r"], "\n", $body);
        return str_replace("\n", "\r\n", $body);
    }
}

/* ---------------------------------------------------------------------------
 *  Public helpers
 * ------------------------------------------------------------------------- */

/**
 * Send an email through the configured SMTP relay.
 * Falls back to PHP's mail() if SMTP is not configured.
 *
 * @param string|string[] $to
 * @param string $body      Plain-text body (always sent — used as fallback)
 * @param string $replyTo   Optional Reply-To address
 * @param string $bodyHtml  Optional HTML body — when set, recipients see HTML
 */
function send_smtp_email($to, string $subject, string $body, string $replyTo = '', string $bodyHtml = ''): bool
{
    $recipients = array_values(array_filter(
        (array) $to,
        static fn ($email) => filter_var($email, FILTER_VALIDATE_EMAIL)
    ));
    if (!$recipients) {
        return false;
    }

    // If SMTP isn't set up, fall back to native mail().
    if (!defined('SMTP_HOST') || SMTP_HOST === '' || !defined('SMTP_USER') || SMTP_USER === '') {
        $fromName  = defined('SMTP_FROM_NAME') ? SMTP_FROM_NAME : BRAND_NAME;
        $fromEmail = defined('SMTP_FROM_EMAIL') ? SMTP_FROM_EMAIL : BRAND_EMAIL;
        $headers = [
            'From: '     . $fromName . ' <' . $fromEmail . '>',
            'Reply-To: ' . ($replyTo !== '' ? $replyTo : $fromEmail),
            'MIME-Version: 1.0',
        ];
        if ($bodyHtml !== '') {
            $boundary = 'iph-' . bin2hex(random_bytes(8));
            $headers[] = 'Content-Type: multipart/alternative; boundary="' . $boundary . '"';
            $payload  = "--$boundary\r\nContent-Type: text/plain; charset=UTF-8\r\n\r\n$body\r\n\r\n";
            $payload .= "--$boundary\r\nContent-Type: text/html; charset=UTF-8\r\n\r\n$bodyHtml\r\n\r\n";
            $payload .= "--$boundary--\r\n";
            return @mail(implode(',', $recipients), $subject, $payload, implode("\r\n", $headers));
        }
        $headers[] = 'Content-Type: text/plain; charset=UTF-8';
        return @mail(implode(',', $recipients), $subject, $body, implode("\r\n", $headers));
    }

    $mailer = new SmtpMailer([
        'host'       => SMTP_HOST,
        'port'       => SMTP_PORT,
        'username'   => SMTP_USER,
        'password'   => SMTP_PASS,
        'encryption' => SMTP_ENCRYPTION,
    ]);

    try {
        return $mailer->send([
            'from'      => SMTP_FROM_EMAIL,
            'from_name' => SMTP_FROM_NAME,
            'to'        => $recipients,
            'subject'   => $subject,
            'body'      => $body,
            'body_html' => $bodyHtml,
            'reply_to'  => $replyTo,
        ]);
    } catch (Throwable $e) {
        error_log('SMTP send failed: ' . $e->getMessage());
        return false;
    }
}

/* ---------------------------------------------------------------------------
 *  Google reCAPTCHA v3
 * ------------------------------------------------------------------------- */

/**
 * Verify a reCAPTCHA v3 token against Google's siteverify API.
 *
 * Returns an associative array with the API response plus a `_ok` boolean.
 * If RECAPTCHA_SECRET_KEY is not configured, verification is skipped and
 * `_ok` is true (so existing flows keep working before keys are pasted in).
 */
function verify_recaptcha(string $token, string $expectedAction = 'submit', float $minScore = 0.5): array
{
    if (!defined('RECAPTCHA_SECRET_KEY') || RECAPTCHA_SECRET_KEY === '') {
        return ['_ok' => true, '_skipped' => true];
    }

    if ($token === '') {
        return ['_ok' => false, '_error' => 'missing-token'];
    }

    $payload = http_build_query([
        'secret'   => RECAPTCHA_SECRET_KEY,
        'response' => $token,
        'remoteip' => $_SERVER['REMOTE_ADDR'] ?? '',
    ]);

    $body = false;

    if (function_exists('curl_init')) {
        $ch = curl_init('https://www.google.com/recaptcha/api/siteverify');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $payload,
            CURLOPT_TIMEOUT        => 10,
            CURLOPT_CONNECTTIMEOUT => 5,
        ]);
        $body = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($body === false || $httpCode >= 400) {
            return ['_ok' => false, '_error' => 'http-' . $httpCode];
        }
    } else {
        $context = stream_context_create([
            'http' => [
                'method'        => 'POST',
                'header'        => "Content-Type: application/x-www-form-urlencoded\r\n",
                'content'       => $payload,
                'timeout'       => 10,
                'ignore_errors' => true,
            ],
        ]);
        $body = @file_get_contents('https://www.google.com/recaptcha/api/siteverify', false, $context);
        if ($body === false) {
            return ['_ok' => false, '_error' => 'no-response'];
        }
    }

    $result = json_decode((string) $body, true);
    if (!is_array($result)) {
        return ['_ok' => false, '_error' => 'invalid-json'];
    }

    $success = ($result['success'] ?? false) === true;
    $score   = (float) ($result['score']  ?? 0);
    $action  = (string) ($result['action'] ?? '');

    $result['_ok'] = $success
        && $score >= $minScore
        && ($expectedAction === '' || $action === $expectedAction);

    return $result;
}
