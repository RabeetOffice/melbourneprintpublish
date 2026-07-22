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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.0.8/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo mpp_asset_v($assetBase, '/assets/js/slick.min.js'); ?>"></script>
<script src="<?php echo mpp_asset_v($assetBase, '/assets/js/wow.min.js'); ?>"></script>
<script src="<?php echo mpp_asset_v($assetBase, '/assets/js/Observer.min.js'); ?>"></script>
<script src="<?php echo mpp_asset_v($assetBase, '/assets/js/custom.js'); ?>"></script>
