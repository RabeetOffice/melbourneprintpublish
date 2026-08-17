<?php
$siteRoot = realpath(__DIR__ . '/..');
$docRoot = realpath($_SERVER['DOCUMENT_ROOT'] ?? '');
$assetBase = '';
if ($siteRoot && $docRoot && strpos($siteRoot, $docRoot) === 0) {
    $assetBase = '/' . trim(str_replace('\\', '/', substr($siteRoot, strlen($docRoot))), '/');
    if ($assetBase === '/') {
        $assetBase = '';
    }
}
$assetBaseEsc = htmlspecialchars($assetBase, ENT_QUOTES, 'UTF-8');

if (!function_exists('mpp_asset_v')) {
    /**
     * Cache-busted URL for a local static asset. The .htaccess file serves
     * CSS/JS with a 1-year "immutable" Cache-Control header (correct for
     * performance), which means a returning visitor's browser will keep
     * using its cached copy of e.g. style.css for up to a year even after
     * this file changes on the server, unless the URL itself changes.
     * Appending the file's own last-modified time as a query string gives
     * every edit a fresh URL automatically, with no manual version bump.
     */
    function mpp_asset_v(string $assetBase, string $relPath): string
    {
        $escBase = htmlspecialchars($assetBase, ENT_QUOTES, 'UTF-8');
        $diskPath = __DIR__ . '/../' . ltrim($relPath, '/');
        $v = is_file($diskPath) ? (string) @filemtime($diskPath) : '';
        $qs = $v !== '' ? ('?v=' . $v) : '';
        return $escBase . '/' . ltrim($relPath, '/') . $qs;
    }
}
?>
<?php include __DIR__ . '/favicon.php'; ?>
<!-- Warm up the third-party origins the <head> is about to block on. Each
     saves a DNS + TCP + TLS round trip on the critical path. -->
<link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
<link rel="preconnect" href="https://code.jquery.com" crossorigin>
<link rel="preconnect" href="https://www.googletagmanager.com">
<link rel="dns-prefetch" href="https://analytics.ahrefs.com">

<!-- Preload the two Poppins weights that carry almost all visible text
     (400 body, 600 headings). PageSpeed measured CLS 1.088 on mobile and
     named these fonts, with the hero <section class="banner"> alone shifting
     0.961: the page painted in a fallback face, then reflowed when Poppins
     arrived and pushed everything below it down. Fetching them at highest
     priority means the real face is usually ready for the first paint. -->
<link rel="preload" as="font" type="font/woff2" crossorigin href="<?php echo mpp_asset_v($assetBase, '/assets/fonts/poppins/Poppins-Regular.woff2'); ?>">
<link rel="preload" as="font" type="font/woff2" crossorigin href="<?php echo mpp_asset_v($assetBase, '/assets/fonts/poppins/Poppins-SemiBold.woff2'); ?>">

<?php
/* Critical CSS inlined, full stylesheets loaded asynchronously.
 *
 * PageSpeed measured 900 ms of render-blocking requests on mobile. The five
 * stylesheets are no longer on the critical path: the above-the-fold rules
 * (header + hero, plus the @font-face declarations) are inlined below, and
 * the complete sheets load via the media="print" swap, applying as soon as
 * they arrive. The <noscript> block keeps the site fully styled without JS.
 *
 * critical.css is generated - see the note at the top of that file. If the
 * header or hero styling changes, regenerate it or the first paint will be
 * missing those rules.
 */
$mppCritical = __DIR__ . '/../assets/css/critical.css';
if (is_file($mppCritical)) {
    $css = (string) @file_get_contents($mppCritical);
    // font url()s are stored root-relative against a placeholder because
    // inlined CSS resolves relative URLs against the page, not the stylesheet
    $css = str_replace('%%ASSET_BASE%%', $assetBase, $css);
    echo "<style id=\"mpp-critical\">" . $css . "</style>\n";
}

$mppSheets = [
    '/assets/css/bootstrap-subset.css',
    '/assets/css/slick.css',
    '/assets/fonts/poppins/stylesheet.css',
    '/assets/css/style.css',
    '/assets/css/responsive.css',
];
foreach ($mppSheets as $sheet) {
    $href = mpp_asset_v($assetBase, $sheet);
    echo '<link rel="stylesheet" type="text/css" href="' . $href
       . '" media="print" onload="this.media=\'all\';this.onload=null;">' . "\n";
}
echo "<noscript>\n";
foreach ($mppSheets as $sheet) {
    echo '    <link rel="stylesheet" type="text/css" href="'
       . mpp_asset_v($assetBase, $sheet) . '">' . "\n";
}
echo "</noscript>\n";
?>

<script src="https://analytics.ahrefs.com/analytics.js" data-key="WHe/W6MDbhwfbjQb46uAUQ" async></script>

<!-- Preconnect CDN -->
<!--<link rel="preconnect" href="https://cdn.jsdelivr.net">-->
<!--<link rel="preconnect" href="https://cdnjs.cloudflare.com">-->

<!-- Main critical CSS -->
<!--<link rel="stylesheet" href="/assets/css/style.css">-->

<!-- Non-critical CSS async -->
<!--<link rel="stylesheet" href="/assets/css/responsive.css" media="print" onload="this.media='all'">-->

<!-- Bootstrap async -->
<!--<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" media="print" onload="this.media='all'" crossorigin="anonymous">-->

<!-- Font Awesome: keep only one, not both -->
<!--<link rel="stylesheet" href="/assets/fontawesome/css/all.min.css" media="print" onload="this.media='all'">-->

<!-- Slick async -->
<!--<link rel="stylesheet" href="/assets/css/slick.css" media="print" onload="this.media='all'">-->
<!--<link rel="stylesheet" href="/assets/css/slick-theme.css" media="print" onload="this.media='all'">-->

<!-- Fonts async -->
<!--<link rel="stylesheet" href="/assets/fonts/poppins/stylesheet.css" media="print" onload="this.media='all'">-->

<!--<noscript>-->
<!--    <link rel="stylesheet" href="/assets/css/responsive.css">-->
<!--    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">-->
<!--    <link rel="stylesheet" href="/assets/fontawesome/css/all.min.css">-->
<!--    <link rel="stylesheet" href="/assets/css/slick.css">-->
<!--    <link rel="stylesheet" href="/assets/css/slick-theme.css">-->
<!--    <link rel="stylesheet" href="/assets/fonts/poppins/stylesheet.css">-->
<!--</noscript>-->
<?php if (function_exists('mpp_custom_script')) { mpp_custom_script('head'); } ?>
