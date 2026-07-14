<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/smtp-mailer.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BRAND_SITE_URL);
    exit;
}

function field_value(string $key): string
{
    $value = $_POST[$key] ?? '';
    if (is_array($value)) {
        return implode(', ', array_map('trim', $value));
    }
    return trim((string) $value);
}

function first_field_value(array $keys): string
{
    foreach ($keys as $key) {
        $value = field_value($key);
        if ($value !== '') {
            return $value;
        }
    }
    return '';
}

function client_ip(): string
{
    foreach (['HTTP_CF_CONNECTING_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR'] as $key) {
        if (empty($_SERVER[$key])) {
            continue;
        }
        $value = trim((string) $_SERVER[$key]);
        if ($key === 'HTTP_X_FORWARDED_FOR') {
            $value = trim(explode(',', $value)[0]);
        }
        return $value;
    }
    return '';
}

function redirect_back(string $status): void
{
    $siteRoot = rtrim(BRAND_SITE_URL, '/') . '/';

    if ($status === 'success') {
        header('Location: ' . $siteRoot . 'thank-you.php');
        exit;
    }

    $target = field_value('fullpageurl') ?: ($_SERVER['HTTP_REFERER'] ?? $siteRoot);
    if ($target === '') {
        $target = $siteRoot;
    }
    if (!preg_match('/^https?:\/\//i', $target)) {
        $target = $siteRoot . ltrim($target, '/');
    }
    // Open-redirect guard: never bounce off-site. An absolute target must share
    // the site's host, otherwise fall back to the site root.
    $targetHost = parse_url($target, PHP_URL_HOST);
    $siteHost = parse_url($siteRoot, PHP_URL_HOST);
    if ($targetHost !== null && strcasecmp((string) $targetHost, (string) $siteHost) !== 0) {
        $target = $siteRoot;
    }

    $separator = str_contains($target, '?') ? '&' : '?';
    header('Location: ' . $target . $separator . 'form_status=' . rawurlencode($status));
    exit;
}

function log_lead_to_db(array $lead): void
{
    if (!defined('DB_HOST') || DB_HOST === '' || !defined('DB_NAME') || DB_NAME === '') {
        return;
    }

    try {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . (defined('DB_CHARSET') ? DB_CHARSET : 'utf8mb4');
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);

        $pdo->exec(
            "CREATE TABLE IF NOT EXISTS leads (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                form_type VARCHAR(100) NULL,
                name VARCHAR(255) NULL,
                email VARCHAR(255) NULL,
                phone VARCHAR(100) NULL,
                service VARCHAR(255) NULL,
                message TEXT NULL,
                page_url TEXT NULL,
                ip_address VARCHAR(100) NULL,
                user_agent TEXT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
        );

        $stmt = $pdo->prepare(
            'INSERT INTO leads (form_type, name, email, phone, service, message, page_url, ip_address, user_agent)
             VALUES (:form_type, :name, :email, :phone, :service, :message, :page_url, :ip_address, :user_agent)'
        );

        $stmt->execute([
            'form_type' => $lead['form_type'],
            'name' => $lead['name'],
            'email' => $lead['email'],
            'phone' => $lead['phone'],
            'service' => $lead['service'],
            'message' => $lead['message'],
            'page_url' => $lead['page_url'],
            'ip_address' => $lead['ip_address'],
            'user_agent' => $lead['user_agent'],
        ]);
    } catch (Throwable $e) {
        error_log('[lead-db] ' . $e->getMessage());
    }
}

function lead_form_label(string $formType): string
{
    return [
        'banner' => 'Banner Form',
        'contact' => 'Contact Form',
        'footer' => 'Footer Form',
        'website' => 'Website Form',
    ][$formType] ?? ucwords(str_replace('_', ' ', $formType ?: 'Website'));
}

function lead_visible_rows(array $lead): array
{
    $labels = [
        'name' => 'Full Name',
        'email' => 'Email',
        'phone' => 'Phone',
        'service' => 'Service Interested In',
        'message' => 'Message',
    ];

    $rows = [];
    foreach ($labels as $key => $label) {
        $value = trim((string) ($lead[$key] ?? ''));
        if ($value !== '') {
            $rows[] = ['label' => $label, 'value' => $value, 'key' => $key];
        }
    }
    return $rows;
}

function build_lead_text(array $lead): string
{
    $lines = [
        'New lead from ' . BRAND_NAME,
        'Source: ' . lead_form_label($lead['form_type']),
        'Received: ' . date('j F Y, H:i') . ' (Australia/Melbourne)',
        str_repeat('=', 60),
        '',
    ];

    foreach (lead_visible_rows($lead) as $row) {
        $lines[] = $row['label'] . ': ' . $row['value'];
    }

    $lines[] = '';
    $lines[] = str_repeat('-', 60);
    if (!empty($lead['page_url'])) {
        $lines[] = 'Submitted from: ' . $lead['page_url'];
    }
    if (!empty($lead['ip_address'])) {
        $lines[] = 'IP: ' . $lead['ip_address'];
    }

    return implode("\r\n", $lines);
}

function build_lead_html(array $lead): string
{
    $rowsHtml = '';
    foreach (lead_visible_rows($lead) as $row) {
        $label = e($row['label']);
        $value = e($row['value']);

        if ($row['key'] === 'email') {
            $value = '<a href="mailto:' . $value . '" style="color:#0d5c63;text-decoration:none;font-weight:600;">' . $value . '</a>';
        } elseif ($row['key'] === 'phone') {
            $tel = e(preg_replace('/[^0-9+]/', '', $row['value']));
            $value = '<a href="tel:' . $tel . '" style="color:#0d5c63;text-decoration:none;font-weight:600;">' . $value . '</a>';
        } elseif ($row['key'] === 'message') {
            $value = nl2br($value);
        }

        $rowsHtml .= '<tr>'
            . '<td style="padding:14px 18px;border-bottom:1px solid #ececec;font-size:13px;color:#667085;font-weight:700;text-transform:uppercase;width:170px;vertical-align:top;">' . $label . '</td>'
            . '<td style="padding:14px 18px;border-bottom:1px solid #ececec;font-size:15px;color:#182230;line-height:1.55;vertical-align:top;">' . $value . '</td>'
            . '</tr>';
    }

    if ($rowsHtml === '') {
        $rowsHtml = '<tr><td style="padding:18px;color:#667085;font-size:14px;">No fields were submitted.</td></tr>';
    }

    $sourceLabel = e(lead_form_label($lead['form_type']));
    $name = e($lead['name'] ?: 'A new visitor');
    $pageUrl = e($lead['page_url']);
    $ip = e($lead['ip_address']);
    $brandName = e(BRAND_NAME);
    $brandSite = e(rtrim(BRAND_SITE_URL, '/'));

    $meta = [];
    if ($pageUrl !== '') {
        $meta[] = 'Page: <a href="' . $pageUrl . '" style="color:#0d5c63;">' . $pageUrl . '</a>';
    }
    if ($ip !== '') {
        $meta[] = 'IP: ' . $ip;
    }
    $metaHtml = $meta ? implode(' &nbsp;|&nbsp; ', $meta) : '';

    return <<<HTML
<!DOCTYPE html>
<html lang="en">
<body style="margin:0;padding:0;background:#f5f7f8;font-family:Arial,sans-serif;color:#182230;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f5f7f8;padding:32px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellspacing="0" cellpadding="0" style="max-width:600px;width:100%;background:#ffffff;border-radius:14px;overflow:hidden;">
                    <tr>
                        <td style="background:#0d5c63;padding:24px 30px;color:#ffffff;">
                            <strong style="font-size:13px;letter-spacing:1px;text-transform:uppercase;">New Lead</strong>
                            <span style="float:right;font-size:12px;opacity:.75;">{$brandName}</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:30px 30px 18px;">
                            <h1 style="margin:0 0 8px;font-size:24px;line-height:1.25;">{$name} just got in touch</h1>
                            <p style="margin:0;font-size:14px;color:#667085;">via <strong style="color:#0d5c63;">{$sourceLabel}</strong> | {$brandName}</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:0 30px 28px;">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border:1px solid #ececec;border-radius:10px;overflow:hidden;">{$rowsHtml}</table>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:18px 30px 28px;border-top:1px solid #ececec;font-size:12px;color:#667085;line-height:1.7;">{$metaHtml}</td>
                    </tr>
                </table>
                <p style="margin:18px auto 0;max-width:600px;font-size:11px;color:#98a2b3;">
                    This notification was sent automatically by <a href="{$brandSite}" style="color:#667085;">{$brandName}</a>.
                </p>
            </td>
        </tr>
    </table>
</body>
</html>
HTML;
}

function send_lead_email(array $lead, array $recipients): bool
{
    $name = $lead['name'] !== '' ? $lead['name'] : 'New lead';
    $subject = sprintf('New Lead: %s - %s', $name, lead_form_label($lead['form_type']));

    return send_smtp_email(
        $recipients,
        $subject,
        build_lead_text($lead),
        $lead['email'] ?: '',
        build_lead_html($lead)
    );
}

$firstName = field_value('firstName');
$lastName = field_value('lastName');
$name = first_field_value(['name', 'fullName']) ?: trim($firstName . ' ' . $lastName);

$lead = [
    'form_type' => field_value('form_type') ?: 'website',
    'name' => $name,
    'email' => field_value('email'),
    'phone' => first_field_value(['number', 'phone']),
    'service' => first_field_value(['service', 'services']),
    'message' => first_field_value(['msg', 'message']),
    'page_url' => field_value('fullpageurl') ?: ($_SERVER['HTTP_REFERER'] ?? ''),
    'ip_address' => client_ip(),
    'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
];

if ($lead['name'] === '' || !filter_var($lead['email'], FILTER_VALIDATE_EMAIL) || $lead['phone'] === '') {
    redirect_back('invalid');
}

// Optional reCAPTCHA v3 gate (Admin → Site Settings). Off unless enabled AND
// keys configured; skipped for loopback so local testing still works.
if (function_exists('mpp_recaptcha_public_enabled') && mpp_recaptcha_public_enabled()) {
    $peer = $_SERVER['REMOTE_ADDR'] ?? '';
    $isLoopback = in_array($peer, ['127.0.0.1', '::1', '::ffff:127.0.0.1'], true);
    if (!$isLoopback) {
        $token = (string) ($_POST['recaptcha_token'] ?? '');
        $rc = verify_recaptcha($token, 'submit', (float) mpp_setting('recaptcha.min_score', 0.5));
        if (empty($rc['_ok'])) {
            redirect_back('captcha');
        }
    }
}

global $lead_recipients;
$emailSent = send_lead_email($lead, $lead_recipients ?? []);
if (!$emailSent) {
    error_log('[lead] mail send returned false for ' . $lead['email']);
}

log_lead_to_db($lead);

redirect_back('success');
