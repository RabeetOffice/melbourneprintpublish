<?php
/**
 * Connection health checks for Admin → Site Settings: lead database, SMTP
 * and reCAPTCHA. Each check returns:
 *   ['state' => 'ok'|'warn'|'fail'|'off', 'label' => ..., 'detail' => ...]
 * using the same effective values config.php resolved (settings override →
 * config.secret.php → built-in default). Network checks keep timeouts short
 * so the page stays responsive when a service is unreachable.
 */

require_once __DIR__ . '/helpers.php';
require_once ADMIN_SITE_ROOT . '/includes/smtp-mailer.php';

/** One-line, length-capped error text safe for the admin UI. */
function admin_health_trim(string $message): string
{
    $message = trim((string) preg_replace('~\s+~', ' ', $message));
    return mb_strimwidth($message, 0, 220, '…');
}

/** Lead database: connect, then prove the leads table is readable. */
function admin_health_db(): array
{
    if (!defined('DB_HOST') || DB_HOST === '' || !defined('DB_NAME') || DB_NAME === '') {
        return ['state' => 'off', 'label' => 'Not configured', 'detail' => 'Set the database host and name below.'];
    }
    try {
        $pdo = new PDO(
            'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . (defined('DB_CHARSET') ? DB_CHARSET : 'utf8mb4'),
            DB_USER,
            DB_PASS,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_TIMEOUT => 3]
        );
    } catch (Throwable $e) {
        return ['state' => 'fail', 'label' => 'Not connected', 'detail' => admin_health_trim($e->getMessage())];
    }
    try {
        $count = (int) $pdo->query('SELECT COUNT(*) FROM leads')->fetchColumn();
        return [
            'state'  => 'ok',
            'label'  => 'Connected',
            'detail' => DB_NAME . ' @ ' . DB_HOST . ' as ' . DB_USER . ' — leads table OK (' . $count . ' lead' . ($count === 1 ? '' : 's') . ').',
        ];
    } catch (Throwable $e) {
        return [
            'state'  => 'warn',
            'label'  => 'Table missing',
            'detail' => 'Connected to ' . DB_NAME . ' @ ' . DB_HOST . ' but the leads table is not readable: ' . admin_health_trim($e->getMessage()),
        ];
    }
}

/** SMTP: full connect + STARTTLS + AUTH LOGIN handshake, no email sent. */
function admin_health_smtp(): array
{
    if (!defined('SMTP_HOST') || SMTP_HOST === '' || !defined('SMTP_USER') || SMTP_USER === '') {
        return ['state' => 'off', 'label' => 'Not configured', 'detail' => 'Form emails fall back to PHP mail() until SMTP is set.'];
    }
    if (!defined('SMTP_PASS') || SMTP_PASS === '') {
        return [
            'state'  => 'warn',
            'label'  => 'No password',
            'detail' => 'The SMTP password is empty — is includes/config.secret.php present on this server?',
        ];
    }
    $mailer = new SmtpMailer([
        'host'       => SMTP_HOST,
        'port'       => SMTP_PORT,
        'username'   => SMTP_USER,
        'password'   => SMTP_PASS,
        'encryption' => SMTP_ENCRYPTION,
        'timeout'    => 6,
    ]);
    try {
        $mailer->verifyAuth();
        return [
            'state'  => 'ok',
            'label'  => 'Connected',
            'detail' => 'Authenticated with ' . SMTP_HOST . ':' . SMTP_PORT . ' as ' . SMTP_USER . '. No email was sent.',
        ];
    } catch (Throwable $e) {
        return ['state' => 'fail', 'label' => 'Failed', 'detail' => admin_health_trim($e->getMessage())];
    }
}

/**
 * reCAPTCHA: ask Google's siteverify with a dummy token. Google rejects the
 * token either way, but the error code tells us whether the SECRET key itself
 * is valid ("invalid-input-response" = key accepted, "invalid-input-secret"
 * = key wrong). The site key can only be proven in a real browser widget.
 */
function admin_health_recaptcha(): array
{
    $site   = defined('RECAPTCHA_SITE_KEY') ? RECAPTCHA_SITE_KEY : '';
    $secret = defined('RECAPTCHA_SECRET_KEY') ? RECAPTCHA_SECRET_KEY : '';
    if ($site === '' && $secret === '') {
        return ['state' => 'off', 'label' => 'Not configured', 'detail' => 'Captcha stays inactive (forms still work) until both keys are added.'];
    }
    if ($site === '' || $secret === '') {
        return [
            'state'  => 'warn',
            'label'  => 'Incomplete',
            'detail' => 'Only the ' . ($site === '' ? 'secret' : 'site') . ' key is set — both keys are needed before the captcha activates.',
        ];
    }

    $result = verify_recaptcha('admin-health-probe', '', 0.0);
    if (isset($result['_error'])) {
        return [
            'state'  => 'warn',
            'label'  => 'Unverified',
            'detail' => 'Keys are set, but Google could not be reached to verify them (' . admin_health_trim((string) $result['_error']) . ').',
        ];
    }
    $codes = array_map('strval', (array) ($result['error-codes'] ?? []));
    if (in_array('invalid-input-secret', $codes, true)) {
        return ['state' => 'fail', 'label' => 'Secret key rejected', 'detail' => 'Google does not recognise the secret key — re-copy it from the reCAPTCHA admin console.'];
    }
    return [
        'state'  => 'ok',
        'label'  => 'Keys active',
        'detail' => 'Google accepted the secret key; captcha is active on the admin login' . (mpp_setting('recaptcha.enable_public_forms', false) ? ' and public forms.' : '. Public forms are unticked.'),
    ];
}

/** All three checks, keyed by display name (used by the status card). */
function admin_health_all(): array
{
    return [
        'Lead database' => admin_health_db(),
        'SMTP email'    => admin_health_smtp(),
        'reCAPTCHA'     => admin_health_recaptcha(),
    ];
}
