<?php
/** 403 page. Included by admin_require_module()/admin_require_admin(). */
$fbUser = admin_current_user();
$fbHome = admin_home_url($fbUser);
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>403 &middot; Admin &middot; <?= e(BRAND_NAME); ?></title>
    <link rel="stylesheet" href="assets/admin.css?v=1">
</head>
<body class="adm adm-center-page">
    <div class="adm-card adm-authcard">
        <img src="<?= e(brand_asset('/assets/images/logo.png')); ?>" alt="<?= e(BRAND_NAME); ?>" class="adm-auth-logo">
        <h1>Access denied</h1>
        <p class="adm-muted">Your account doesn't have permission to open this section.</p>
        <p><a class="adm-btn" href="<?= e($fbHome); ?>">Back to your home</a></p>
    </div>
</body>
</html>
