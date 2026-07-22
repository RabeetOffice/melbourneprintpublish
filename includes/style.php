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
<!-- Framework first -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">

<!-- Icons -->
<link rel="stylesheet" type="text/css" href="<?php echo mpp_asset_v($assetBase, '/assets/fontawesome/css/all.min.css'); ?>">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<!-- Plugin CSS -->
<link rel="stylesheet" type="text/css" href="<?php echo mpp_asset_v($assetBase, '/assets/css/slick.css'); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo mpp_asset_v($assetBase, '/assets/css/slick-theme.css'); ?>">

<!-- Fonts -->
<link rel="stylesheet" type="text/css" href="<?php echo mpp_asset_v($assetBase, '/assets/fonts/poppins/stylesheet.css'); ?>">

<!-- Custom CSS LAST -->
<link rel="stylesheet" type="text/css" href="<?php echo mpp_asset_v($assetBase, '/assets/css/style.css'); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo mpp_asset_v($assetBase, '/assets/css/responsive.css'); ?>">



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
