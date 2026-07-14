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
?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.0.8/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo $assetBaseEsc; ?>/assets/js/slick.min.js"></script>
<script src="<?php echo $assetBaseEsc; ?>/assets/js/wow.min.js"></script>
<script src="<?php echo $assetBaseEsc; ?>/assets/js/Observer.min.js"></script>
<script src="<?php echo $assetBaseEsc; ?>/assets/js/custom.js"></script>
