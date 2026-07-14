<?php
/**
 * Central per-page SEO/meta renderer + custom-script slots + schema.
 *
 * ONE dynamic source for every page's <head>. Each page carries only its own
 * title / description / keywords / JSON-LD schema (in admin/data/seo-pages.json,
 * edited in Admin → SEO). The shared boilerplate — geo meta, canonical, the
 * google-site-verification tag and the GTM + GA scripts — is rendered centrally
 * here from Site Settings, so it is no longer copy-pasted into every page and a
 * change in the admin takes effect site-wide.
 */

require_once __DIR__ . '/config.php';

function mpp_seo_file(): string
{
    return __DIR__ . '/../admin/data/seo-pages.json';
}

function mpp_seo_all(): array
{
    static $cache = null;
    if ($cache !== null) {
        return $cache;
    }
    $data = [];
    if (is_file(mpp_seo_file())) {
        $raw = json_decode((string) @file_get_contents(mpp_seo_file()), true);
        if (is_array($raw)) {
            $data = $raw;
        }
    }
    return $cache = $data;
}

function mpp_seo_page(string $key): ?array
{
    $all = mpp_seo_all();
    return isset($all[$key]) && is_array($all[$key]) ? $all[$key] : null;
}

/**
 * The shared head block, rendered from Site Settings: geo/DC meta, the
 * google-site-verification tag and the GTM + GA scripts. One definition for the
 * whole site — editing the analytics IDs in the admin changes every page.
 */
function mpp_seo_shared_block(): string
{
    $gsv = mpp_e('analytics.google_site_verification');
    $ga  = mpp_e('analytics.ga_id');
    $gtm = mpp_e('analytics.gtm_id');
    $out  = "    <meta name=\"DC.title\" content=\"Melbourne Print and Publish\" />\n";
    $out .= "    <meta name=\"geo.region\" content=\"AU-VIC\" />\n";
    $out .= "    <meta name=\"geo.placename\" content=\"Melbourne\" />\n";
    $out .= "    <meta name=\"geo.position\" content=\"-37.841303;144.97652\" />\n";
    $out .= "    <meta name=\"ICBM\" content=\"-37.841303, 144.97652\" />\n";
    $out .= "    <link rel=\"canonical\" href=\"" . e(brand_canonical()) . "\" />\n";
    if ($gsv !== '') {
        $out .= "    <meta name=\"google-site-verification\" content=\"$gsv\" />\n";
    }
    if ($ga !== '') {
        $out .= "    <script async src=\"https://www.googletagmanager.com/gtag/js?id=$ga\"></script>\n";
        $out .= "    <script>\n    window.dataLayer = window.dataLayer || [];\n    function gtag(){dataLayer.push(arguments);}\n    gtag('js', new Date());\n    gtag('config', '$ga');\n    </script>\n";
    }
    if ($gtm !== '') {
        $out .= "    <script>\n    (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer','$gtm');\n    </script>\n";
    }
    return $out;
}

/** The GTM <noscript> fallback for right after <body>. From settings. */
function mpp_seo_gtm_noscript(): string
{
    $gtm = mpp_e('analytics.gtm_id');
    if ($gtm === '') {
        return '';
    }
    return '<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=' . $gtm
        . '" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>';
}

/**
 * Render a page's whole <head> SEO region from its stored fields + the shared
 * block. $key is the seo-pages.json key (e.g. 'about-us'). Unknown key → nothing.
 */
function mpp_seo_head(string $key): void
{
    $page = mpp_seo_page($key);
    if ($page === null) {
        return; // unknown page keeps whatever head it already has
    }

    $title    = (string) ($page['title'] ?? '');
    $desc     = (string) ($page['description'] ?? '');
    $keywords = (string) ($page['keywords'] ?? '');
    $schemas  = is_array($page['schemas'] ?? null) ? $page['schemas'] : [];
    $h = static fn($s): string => htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8');

    echo '<title>' . $h($title) . "</title>\n";
    echo '    <meta name="description" content="' . $h($desc) . "\" />\n";
    if (trim($keywords) !== '') {
        echo '    <meta name="keywords" content="' . $h($keywords) . "\" />\n";
    }
    echo mpp_seo_shared_block();

    foreach ($schemas as $json) {
        $json = trim((string) $json);
        if ($json !== '') {
            echo "    <script type=\"application/ld+json\">\n" . mpp_jsonld_safe($json) . "\n    </script>\n";
        }
    }
    mpp_seo_org_schema();
}

/**
 * Make a JSON-LD string safe to embed in <script>: escape every "<" as its
 * JSON unicode form "<". This neutralises "</script>", "<!--" and "<script"
 * alike, and "<" is a valid JSON escape that decodes back to "<", so the
 * structured data's meaning is unchanged for parsers.
 */
function mpp_jsonld_safe(string $json): string
{
    // "\\u003c" (single-quoted) is the two chars backslash+u... = the JSON escape
    // for "<". Built by concatenation so it can't be mis-encoded on the way in.
    $lt = '\\' . 'u003c';
    return str_replace('<', $lt, $json);
}

/** Site-wide Organization schema — off unless the owner turns it on. */
function mpp_seo_org_schema(): void
{
    if (!mpp_setting('schema.organization_enabled', false)) {
        return;
    }
    $json = trim((string) mpp_setting('schema.organization_json', ''));
    if ($json === '') {
        return;
    }
    echo "\n    <script type=\"application/ld+json\">\n" . mpp_jsonld_safe($json) . "\n    </script>";
}

/* Custom script slots (mpp_custom_script) are defined in site-settings.php so
 * they are available on every page via config.php, not only where SEO loads. */
