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
    // See includes/style.php for why this exists: it cache-busts local
    // assets against the .htaccess 1-year "immutable" Cache-Control header.
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
<?php
/* All of these are deferred: none of the page templates contain an inline
   script that calls jQuery or a plugin, so nothing can run before these
   finish. "defer" keeps classic scripts in document order, so jQuery still
   executes before bootstrap/slick/custom.js -- it only stops them blocking
   the parser. Bootstrap 4 needs popper, and both need jQuery, so the order
   below must not be rearranged.

   The standalone popper.js that used to sit between jQuery and Bootstrap has
   been dropped: "bootstrap.bundle" is the Popper-inclusive build, so it was a
   duplicate -- and at 1.0.8 it was the wrong major version for Bootstrap 4.6
   anyway. Nothing in the site references a Popper global directly. */
?>
<script defer src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<script defer src="<?php echo mpp_asset_v($assetBase, '/assets/js/slick.min.js'); ?>"></script>
<script defer src="<?php echo mpp_asset_v($assetBase, '/assets/js/wow.min.js'); ?>"></script>
<script defer src="<?php echo mpp_asset_v($assetBase, '/assets/js/Observer.min.js'); ?>"></script>
<script defer src="<?php echo mpp_asset_v($assetBase, '/assets/js/custom.js'); ?>"></script>
