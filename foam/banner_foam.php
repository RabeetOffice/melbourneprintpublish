<?php
$siteRoot = realpath(__DIR__ . '/..');
$docRoot = realpath($_SERVER['DOCUMENT_ROOT'] ?? '');
$basePath = '';
if ($siteRoot && $docRoot && strpos($siteRoot, $docRoot) === 0) {
    $basePath = '/' . trim(str_replace('\\', '/', substr($siteRoot, strlen($docRoot))), '/');
    if ($basePath === '/') {
        $basePath = '';
    }
}
$formAction = $basePath . '/form-submission.php';
$currentUrl = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://')
    . ($_SERVER['HTTP_HOST'] ?? 'melbourneprintpublish.com.au')
    . ($_SERVER['REQUEST_URI'] ?? '/');
?>
<div class="contact-form banner-form">
   
    
    <form action="<?php echo htmlspecialchars($formAction, ENT_QUOTES, 'UTF-8'); ?>" method="POST">

        <div class="form-group-row">
            <input name="fullName" type="text" placeholder="Full Name" required>
        </div>

        <div class="form-group-row">
            <input name="email" type="email" placeholder="Email" required>
        </div>

        <div class="form-group-row">
            <input name="phone" type="tel" placeholder="Phone Number">
        </div>

        <div class="form-group-row">
            <input name="message" type="text" placeholder="I am looking for...">
        </div>

        <input type="hidden" name="form_type" value="banner">
        <input type="hidden" name="fullpageurl" value="<?php echo htmlspecialchars($currentUrl, ENT_QUOTES, 'UTF-8'); ?>">

        <?php if (function_exists('mpp_recaptcha_form_field')) { mpp_recaptcha_form_field(); } ?><button type="submit">Let's Start</button>

    </form>
    
    
</div>
