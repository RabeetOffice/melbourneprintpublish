<?php
require_once __DIR__ . '/includes/auth.php';
admin_session_start();
admin_security_headers();

if (admin_current_user()) {
    header('Location: ' . admin_home_url());
    exit;
}

$error = '';
$step = isset($_SESSION['admin_2fa_pending']) ? '2fa' : 'password';

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    if (!admin_verify_csrf()) {
        http_response_code(419);
        $error = 'Session expired. Please try again.';
    } elseif (($_POST['stage'] ?? '') === '2fa') {
        $result = admin_login_totp((string) ($_POST['code'] ?? ''));
        if (!empty($result['ok'])) {
            header('Location: ' . admin_home_url());
            exit;
        }
        $error = (string) ($result['error'] ?? 'Invalid code.');
        $step = empty($result['restart']) ? '2fa' : 'password';
    } else {
        if (!admin_recaptcha_ok()) {
            $error = 'Captcha verification failed. Please try again.';
        } else {
            $result = admin_login_password(
                trim((string) ($_POST['username'] ?? '')),
                (string) ($_POST['password'] ?? '')
            );
            if (!empty($result['ok'])) {
                if (!empty($result['twofactor'])) {
                    $step = '2fa';
                } else {
                    header('Location: ' . admin_home_url());
                    exit;
                }
            } else {
                $error = (string) ($result['error'] ?? 'Login failed.');
            }
        }
    }
}
$captcha = admin_recaptcha_enabled() && !admin_is_loopback();
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Log in &middot; Admin &middot; <?= e(BRAND_NAME); ?></title>
    <link rel="stylesheet" href="assets/admin.css?v=1">
    <?php if ($captcha): ?>
    <script src="https://www.google.com/recaptcha/api.js?render=<?= e(RECAPTCHA_SITE_KEY); ?>"></script>
    <?php endif; ?>
</head>
<body class="adm adm-center-page">
    <div class="adm-card adm-authcard">
        <img src="<?= e(brand_asset('/assets/images/logo.png')); ?>" alt="<?= e(BRAND_NAME); ?>" class="adm-auth-logo">
        <h1>Admin <span>login</span></h1>

        <?php if ($error !== ''): ?>
        <div class="adm-alert adm-alert-error"><?= e($error); ?></div>
        <?php endif; ?>

        <?php if ($step === '2fa'): ?>
        <p class="adm-muted">Enter the 6-digit code from your authenticator app, or one of your backup codes.</p>
        <form method="post" autocomplete="off">
            <?= admin_csrf_field(); ?>
            <input type="hidden" name="stage" value="2fa">
            <label class="adm-label" for="code">Authentication code</label>
            <input class="adm-input" type="text" id="code" name="code" inputmode="numeric" autocomplete="one-time-code" autofocus required>
            <button class="adm-btn adm-btn-block" type="submit">Verify</button>
        </form>
        <p class="adm-auth-alt"><a href="login.php">&larr; Start over</a></p>
        <?php else: ?>
        <form method="post" id="loginForm" autocomplete="off">
            <?= admin_csrf_field(); ?>
            <input type="hidden" name="stage" value="password">
            <?php if ($captcha): ?>
            <input type="hidden" name="recaptcha_token" id="recaptchaToken" value="">
            <?php endif; ?>
            <label class="adm-label" for="username">Username</label>
            <input class="adm-input" type="text" id="username" name="username" autocomplete="username" autofocus required>
            <label class="adm-label" for="password">Password</label>
            <input class="adm-input" type="password" id="password" name="password" autocomplete="current-password" required>
            <button class="adm-btn adm-btn-block" type="submit">Log in</button>
        </form>
        <?php if ($captcha): ?>
        <script>
        document.getElementById('loginForm').addEventListener('submit', function (ev) {
            var tokenField = document.getElementById('recaptchaToken');
            if (tokenField.value) { return; }
            ev.preventDefault();
            var form = this;
            grecaptcha.ready(function () {
                grecaptcha.execute('<?= e(RECAPTCHA_SITE_KEY); ?>', {action: 'admin_login'}).then(function (token) {
                    tokenField.value = token;
                    form.submit();
                });
            });
        });
        </script>
        <?php endif; ?>
        <?php endif; ?>
    </div>
</body>
</html>
