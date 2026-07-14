<?php
/** Dynamic robots.txt. Served for /robots.txt via .htaccess rewrite. */
require_once __DIR__ . '/includes/config.php';
header('Content-Type: text/plain; charset=UTF-8');

$lines = [
    'User-agent: *',
    'Disallow: /wp-admin/',
    'Disallow: /portfolio/',
    'Disallow: /admin/',
    'Disallow: /blogs/admin-preview.php',
    'Disallow: /trash/',
    'Allow: /wp-admin/admin-ajax.php',
    '',
    'Sitemap: https://melbourneprintpublish.com.au/sitemap_index.xml',
];
// Any extra disallow lines the owner adds in settings (one per line).
$extra = (string) mpp_setting('robots.extra', '');
if (trim($extra) !== '') {
    echo implode("\n", $lines) . "\n" . trim($extra) . "\n";
} else {
    echo implode("\n", $lines) . "\n";
}
