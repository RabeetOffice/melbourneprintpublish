<?php
define('BRAND_NAME', 'Melbourne Print & Publish');
define('BRAND_DOMAIN', 'melbourneprintpublish.com.au');

$request_host = $_SERVER['HTTP_HOST'] ?? BRAND_DOMAIN;
$is_local_request = preg_match('/^(localhost|127\.0\.0\.1)(:\d+)?$/i', $request_host) === 1;

if ($is_local_request) {
    $request_scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $site_root = realpath(__DIR__ . '/..');
    $doc_root = realpath($_SERVER['DOCUMENT_ROOT'] ?? '');

    if ($site_root && $doc_root && strpos($site_root, $doc_root) === 0) {
        $request_dir = str_replace('\\', '/', substr($site_root, strlen($doc_root)));
    } else {
        $request_dir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
    }

    $request_dir = rtrim($request_dir, '/');
    if ($request_dir === '/' || $request_dir === '\\') {
        $request_dir = '';
    }

    define('BRAND_SITE_URL', $request_scheme . '://' . $request_host . $request_dir);
} else {
    define('BRAND_SITE_URL', 'https://' . BRAND_DOMAIN);
}

// True on the production host. Used to enable clean (no-.php) canonical URLs
// only on the live site; localhost dev keeps .php URLs untouched.
define('BRAND_IS_LIVE', !$is_local_request);

define('BRAND_EMAIL', 'info@melbourneprintpublish.com.au');

/* -------------------------------------------------------------------------
 *  Site settings layer (admin-managed). Loaded before SMTP/reCAPTCHA so
 *  those constants can be overridden from the admin. With no overrides the
 *  hard-coded defaults below apply unchanged.
 * ----------------------------------------------------------------------- */
require_once __DIR__ . '/site-settings.php';

/**
 * Pick an admin override when it is a non-empty string, else the built-in
 * default. Empty strings mean "leave the default" (see site-settings.php).
 */
function cfg_or(string $path, $default)
{
    $v = mpp_setting($path, '');
    return ($v === '' || $v === null) ? $default : $v;
}

// Out-of-repo secrets (git-ignored, HTTP-denied). Absent → empty fallbacks.
$__secrets = is_file(__DIR__ . '/config.secret.php') ? (require __DIR__ . '/config.secret.php') : [];
if (!is_array($__secrets)) { $__secrets = []; }

/* -------------------------------------------------------------------------
 *  SMTP - Google Workspace (defaults; overridable in Admin → Site → SMTP)
 *  NOTE: SMTP credentials below still use the .com Google Workspace account.
 *  After Google Workspace migration to .com.au, update SMTP_USER /
 *  SMTP_FROM_EMAIL to marcus.hale@melbourneprintpublish.com.au and refresh
 *  the App Password.
 * ----------------------------------------------------------------------- */
define('SMTP_HOST', cfg_or('smtp.host', 'smtp.gmail.com'));
define('SMTP_PORT', (int) cfg_or('smtp.port', 587));
define('SMTP_ENCRYPTION', cfg_or('smtp.encryption', 'tls'));
define('SMTP_USER', cfg_or('smtp.user', 'marcus.hale@melbourneprintpublish.com.au'));
define('SMTP_PASS', cfg_or('smtp.pass', (string) ($__secrets['smtp_pass'] ?? 'oxxk iwfe tfsv pyei')));
define('SMTP_FROM_EMAIL', cfg_or('smtp.from_email', 'marcus.hale@melbourneprintpublish.com.au'));
define('SMTP_FROM_NAME', cfg_or('smtp.from_name', BRAND_NAME));

/* -------------------------------------------------------------------------
 *  reCAPTCHA v3 (Admin → Site → reCAPTCHA). Only defined when a key is set,
 *  so verify_recaptcha() keeps skipping gracefully until configured.
 * ----------------------------------------------------------------------- */
$__rc_site   = (string) mpp_setting('recaptcha.site_key', '');
$__rc_secret = (string) mpp_setting('recaptcha.secret_key', '');
if ($__rc_site !== '')   { define('RECAPTCHA_SITE_KEY', $__rc_site); }
if ($__rc_secret !== '') { define('RECAPTCHA_SECRET_KEY', $__rc_secret); }
unset($__rc_site, $__rc_secret);

/* -------------------------------------------------------------------------
 *  Analytics ID rewrite. The GTM/GA/verification IDs are baked into page
 *  markup in a few spots the per-page SEO renderer doesn't reach (the homepage
 *  head and every page's GTM <noscript> fallback). To keep "change analytics in
 *  the admin" consistent site-wide, buffer the page output and swap the seeded
 *  default IDs for the current settings — but ONLY when the owner has actually
 *  overridden one, so the default state stays byte-for-byte identical (and pays
 *  zero overhead).
 * ----------------------------------------------------------------------- */
if (mpp_is_overridden('analytics.gtm_id')
    || mpp_is_overridden('analytics.ga_id')
    || mpp_is_overridden('analytics.google_site_verification')) {
    ob_start(function (string $html): string {
        return strtr($html, [
            'GTM-PFZRKR97' => (string) mpp_setting('analytics.gtm_id'),
            'G-V7PVJBXYL9' => (string) mpp_setting('analytics.ga_id'),
            'fmX4SQHeIHlfWDv3FiuLtWRDGStxBIfdRWPTQGQ8Vxs' => (string) mpp_setting('analytics.google_site_verification'),
        ]);
    });
}

/* -------------------------------------------------------------------------
 *  Lead database (defaults; overridable in Admin → Site → Lead database)
 * ----------------------------------------------------------------------- */
define('DB_HOST', cfg_or('db.host', 'localhost'));
define('DB_NAME', cfg_or('db.name', 'melbelgx_leads'));
define('DB_USER', cfg_or('db.user', 'melbelgx_leadsuser'));
define('DB_PASS', cfg_or('db.pass', (string) ($__secrets['db_pass'] ?? 'o)Rt7v8+Og5b')));
define('DB_CHARSET', 'utf8mb4');

$lead_recipients = [
    'ethan.reyes@melbourneprintpublish.com.au',
    'marcus.hale@melbournebookpublisher.com.au',
    'sam.naran@ukpublishinghouse.co.uk',
];

/* -------------------------------------------------------------------------
 *  Admin settings overrides (managed in /admin/settings.php).
 *  A missing or unreadable file leaves the defaults above untouched.
 * ----------------------------------------------------------------------- */
$__adm_overrides = __DIR__ . '/../admin/data/settings-overrides.json';
if (is_file($__adm_overrides)) {
    $__adm_data = json_decode((string) @file_get_contents($__adm_overrides), true);
    if (is_array($__adm_data) && array_key_exists('lead_recipients', $__adm_data) && is_array($__adm_data['lead_recipients'])) {
        // An empty list is intentional (blanking must work), so no array_filter here;
        // entries were validated as emails when saved.
        $lead_recipients = array_values(array_map('strval', $__adm_data['lead_recipients']));
    }
    unset($__adm_data);
}
unset($__adm_overrides);

function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

/* -------------------------------------------------------------------------
 *  SEO helpers — dynamic canonical & absolute URLs
 *  Use these in templates so a future domain change only requires editing
 *  BRAND_DOMAIN above.
 * ----------------------------------------------------------------------- */

/**
 * Absolute URL for any internal path, anchored to BRAND_SITE_URL.
 * Example: brand_url('/about-us.php') -> https://melbourneprintpublish.com.au/about-us.php
 */
function brand_url(string $path = ''): string
{
    if ($path === '' || $path === '/') {
        return rtrim(BRAND_SITE_URL, '/') . '/';
    }
    return rtrim(BRAND_SITE_URL, '/') . '/' . ltrim($path, '/');
}

/**
 * Host-relative path for an internal asset or page. Prepends the project's
 * sub-directory when running under XAMPP at /brands/<site>/, and resolves to
 * a plain root-anchored path on live.
 *
 *   brand_asset('/assets/images/logo.png')
 *     localhost -> /brands/melbourneprintpublish.com.au/assets/images/logo.png
 *     live      -> /assets/images/logo.png
 *
 * Pass-through cases that are returned unchanged:
 *   - empty string
 *   - URLs with scheme (http://, https://, //, mailto:, tel:, javascript:)
 *   - in-page anchors (#section)
 */
function brand_asset(string $path): string
{
    if ($path === '') {
        return '';
    }
    if (preg_match('~^(?:[a-z][a-z0-9+.\-]*:|//|\#)~i', $path)) {
        return $path;
    }

    static $base = null;
    if ($base === null) {
        $base = '';
        $siteRoot = realpath(__DIR__ . '/..');
        $docRoot  = realpath($_SERVER['DOCUMENT_ROOT'] ?? '');
        if ($siteRoot && $docRoot && strpos($siteRoot, $docRoot) === 0) {
            $sub = str_replace('\\', '/', substr($siteRoot, strlen($docRoot)));
            $sub = '/' . trim($sub, '/');
            if ($sub !== '/' && $sub !== '') {
                $base = $sub;
            }
        }
    }

    return $base . '/' . ltrim($path, '/');
}

/**
 * Canonical URL for the current request. Strips query string and trailing
 * index.php so search engines always see one clean URL per page.
 */
function brand_canonical(): string
{
    $uri = $_SERVER['REQUEST_URI'] ?? '/';
    $path = parse_url($uri, PHP_URL_PATH) ?: '/';

    // Strip the local sub-directory prefix when running under /brands/<site>/
    $site_root = realpath(__DIR__ . '/..');
    $doc_root  = realpath($_SERVER['DOCUMENT_ROOT'] ?? '');
    if ($site_root && $doc_root && strpos($site_root, $doc_root) === 0) {
        $prefix = '/' . trim(str_replace('\\', '/', substr($site_root, strlen($doc_root))), '/');
        if ($prefix !== '/' && strpos($path, $prefix) === 0) {
            $path = substr($path, strlen($prefix));
        }
    }

    if ($path === '' || $path === '/index.php' || $path === '/index') {
        $path = '/';
    }

    // On live, hide the .php extension so canonical matches the rewritten URL.
    // On localhost, leave .php intact so dev URLs still resolve.
    if (BRAND_IS_LIVE && substr($path, -4) === '.php') {
        $path = substr($path, 0, -4);
    }

    // On live, force a trailing slash on all non-root pages so canonical
    // matches the .htaccess "add trailing slash" rule. Skip URLs that already
    // end in '/' and skip anything that looks like an asset (has an extension).
    if (BRAND_IS_LIVE && $path !== '/' && substr($path, -1) !== '/') {
        $hasExtension = (bool) preg_match('/\.[A-Za-z0-9]+$/', $path);
        if (!$hasExtension) {
            $path .= '/';
        }
    }

    return rtrim(BRAND_SITE_URL, '/') . $path;
}

/**
 * Host-relative link to an internal PAGE, in the right form per environment:
 *   - LIVE  → clean URL with trailing slash   (/about-us/)         [no .php]
 *   - LOCAL → .php URL under the project dir   (/brands/<site>/about-us.php)
 *
 * Accepts any of: 'about-us', '/about-us', 'about-us.php', '/about-us/'.
 * Home ('' or '/') → '/' on live, project root on localhost.
 * External URLs (scheme or //) and #anchors are returned unchanged.
 */
function brand_link(string $path = ''): string
{
    if (preg_match('~^(?:[a-z][a-z0-9+.\-]*:|//|\#)~i', $path)) {
        return $path;
    }
    $clean = trim($path, '/');
    if (substr($clean, -4) === '.php') {
        $clean = substr($clean, 0, -4);
    }
    if ($clean === '' || $clean === 'index') {
        return brand_asset('/'); // home
    }
    if (BRAND_IS_LIVE) {
        return '/' . $clean . '/';
    }
    return brand_asset('/' . $clean . '.php');
}

/* ---------------------------------------------------------------------------
 * Website-portfolio thumbnail.
 *
 * A pinned screenshot (uploaded file or pasted URL) always wins. When the
 * entry has no image but does have a website link, a live screenshot is
 * auto-fetched via WordPress mshots (free, no API key, no watermark,
 * CDN-cached). The very first request for a brand-new URL returns a
 * "generating" placeholder for a couple of seconds while WP renders the
 * page; every request after that is served instantly from their CDN.
 */
function mpp_website_thumb(array $item, int $w = 720, int $h = 520): string
{
    $image = trim((string) ($item['image'] ?? ''));
    if ($image !== '') {
        return preg_match('~^(?:https?:)?//~i', $image) ? $image : brand_asset($image);
    }
    $link = trim((string) ($item['link'] ?? ''));
    if (preg_match('~^https?://~i', $link)) {
        return 'https://s0.wp.com/mshots/v1/' . rawurlencode($link) . '?w=' . $w . '&h=' . $h;
    }
    return '';
}

/* ---------------------------------------------------------------------------
 * YouTube helpers (Video Trailers portfolio).
 *
 * The admin only ever pastes a YouTube link in ANY common form and these
 * turn it into a clean, embeddable URL. Handles watch?v=, youtu.be/,
 * /shorts/, /embed/, /live/, a bare 11-char id, and even a full <iframe>
 * embed snippet (the src is extracted). A /shorts/ or /watch URL cannot be
 * put in an <iframe> directly (X-Frame-Options: SAMEORIGIN) — only the
 * /embed/ form loads — which is exactly what mpp_youtube_embed() returns.
 */
function mpp_youtube_id(string $url): string
{
    $url = trim($url);
    if ($url === '') {
        return '';
    }
    // If an <iframe ... src="..."> snippet was pasted, work from the src.
    if (stripos($url, '<iframe') !== false && preg_match('~src\s*=\s*["\']([^"\']+)["\']~i', $url, $m)) {
        $url = trim($m[1]);
    }
    // Any of the known YouTube URL shapes.
    if (preg_match('~(?:youtube(?:-nocookie)?\.com/(?:watch\?(?:[^#]*&)?v=|embed/|shorts/|live/|v/)|youtu\.be/)([A-Za-z0-9_-]{11})~i', $url, $m)) {
        return $m[1];
    }
    // A bare video id.
    if (preg_match('~^[A-Za-z0-9_-]{11}$~', $url)) {
        return $url;
    }
    return '';
}

/** Clean, iframe-safe embed URL for a pasted YouTube link (empty if unparseable). */
function mpp_youtube_embed(string $url): string
{
    $id = mpp_youtube_id($url);
    return $id === '' ? '' : 'https://www.youtube.com/embed/' . $id . '?rel=0';
}

/** YouTube-hosted poster image for a pasted link (used for admin thumbnails). */
function mpp_youtube_thumb(string $url): string
{
    $id = mpp_youtube_id($url);
    return $id === '' ? '' : 'https://i.ytimg.com/vi/' . $id . '/hqdefault.jpg';
}
