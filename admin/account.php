<?php
/** My Account: own password change + own 2FA (any logged-in user). */
require_once __DIR__ . '/includes/auth.php';

$user = admin_require_login();

$msg = '';
$err = '';
$showBackupCodes = null;
$pendingSecret = null;

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    admin_require_csrf();
    $do = (string) ($_POST['do'] ?? '');

    if ($do === 'password') {
        $current = (string) ($_POST['current'] ?? '');
        $new = (string) ($_POST['new'] ?? '');
        $confirm = (string) ($_POST['confirm'] ?? '');
        if (!password_verify($current, (string) $user['password'])) {
            $err = 'Current password is wrong.';
        } elseif (strlen($new) < 10) {
            $err = 'New password must be at least 10 characters.';
        } elseif ($new !== $confirm) {
            $err = 'New passwords do not match.';
        } else {
            admin_update_user((string) $user['username'], ['password' => password_hash($new, PASSWORD_BCRYPT)]);
            $msg = 'Password changed.';
        }
    } elseif ($do === '2fa_start') {
        $pendingSecret = totp_generate_secret();
        $_SESSION['pending_totp'] = $pendingSecret;
    } elseif ($do === '2fa_confirm') {
        $secret = (string) ($_SESSION['pending_totp'] ?? '');
        $code = (string) ($_POST['code'] ?? '');
        if ($secret === '') {
            $err = 'Setup expired. Start again.';
        } elseif (!totp_verify($secret, $code)) {
            $err = 'That code did not match. Scan the QR again and retry.';
            $pendingSecret = $secret;
        } else {
            [$plain, $hashes] = totp_generate_backup_codes();
            admin_update_user((string) $user['username'], [
                'totp_secret' => $secret,
                'totp_backup' => $hashes,
            ]);
            unset($_SESSION['pending_totp']);
            $showBackupCodes = $plain;
            $msg = 'Two-factor authentication is ON. Store your backup codes now - they are shown only once.';
        }
    } elseif ($do === '2fa_disable') {
        if (!password_verify((string) ($_POST['password'] ?? ''), (string) $user['password'])) {
            $err = 'Password is wrong.';
        } else {
            $users = admin_load_users();
            foreach ($users as $i => $u) {
                if (strcasecmp((string) $u['username'], (string) $user['username']) === 0) {
                    unset($users[$i]['totp_secret'], $users[$i]['totp_backup']);
                }
            }
            admin_save_users($users);
            $msg = 'Two-factor authentication is OFF.';
        }
    }
    $user = admin_current_user();
}

$has2fa = !empty($user['totp_secret']);
if ($pendingSecret === null && isset($_SESSION['pending_totp']) && !$has2fa) {
    $pendingSecret = (string) $_SESSION['pending_totp'];
}
$otpauth = $pendingSecret ? totp_otpauth_uri($pendingSecret, (string) $user['username'], BRAND_NAME . ' Admin') : '';

admin_layout_start($user, 'account', 'My Account');
?>
<div class="adm-page-head">
    <div>
        <div class="adm-eyebrow">Security</div>
        <h1>My <span>Account</span></h1>
    </div>
</div>

<?php if ($msg): ?><div class="adm-alert adm-alert-ok"><?= e($msg); ?></div><?php endif; ?>
<?php if ($err): ?><div class="adm-alert adm-alert-error"><?= e($err); ?></div><?php endif; ?>

<?php if ($showBackupCodes): ?>
<div class="adm-card" style="max-width:640px">
    <h2>Backup <span>codes</span></h2>
    <p class="adm-help">Each code works once if you lose your authenticator. This is the only time they are shown.</p>
    <div class="adm-codes">
        <?php foreach ($showBackupCodes as $code): ?><code><?= e($code); ?></code><?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<div class="adm-editor-grid">
    <div class="adm-card">
        <h2>Change <span>password</span></h2>
        <form method="post" autocomplete="off">
            <?= admin_csrf_field(); ?>
            <input type="hidden" name="do" value="password">
            <label class="adm-label">Current password</label>
            <input class="adm-input" type="password" name="current" required autocomplete="current-password">
            <label class="adm-label">New password (min 10 chars)</label>
            <input class="adm-input" type="password" name="new" required autocomplete="new-password">
            <label class="adm-label">Repeat new password</label>
            <input class="adm-input" type="password" name="confirm" required autocomplete="new-password">
            <p style="margin-top:16px"><button class="adm-btn" type="submit">Change password</button></p>
        </form>
    </div>

    <div class="adm-card">
        <h2>Two-factor <span>authentication</span></h2>
        <?php if ($has2fa): ?>
            <p><span class="adm-chip adm-chip-green">2FA is ON</span></p>
            <p class="adm-help">Logging in requires a 6-digit code from your authenticator app.</p>
            <form method="post" style="margin-top:14px">
                <?= admin_csrf_field(); ?>
                <input type="hidden" name="do" value="2fa_disable">
                <label class="adm-label">Confirm with your password to turn 2FA off</label>
                <input class="adm-input" type="password" name="password" required>
                <p style="margin-top:12px"><button class="adm-btn adm-btn-danger adm-btn-sm" type="submit">Disable 2FA</button></p>
            </form>
        <?php elseif ($pendingSecret): ?>
            <p class="adm-help">1. Scan this QR with Google Authenticator / Authy (or enter the key manually). 2. Enter the current code to confirm.</p>
            <div class="adm-qr" id="qrBox"></div>
            <p style="text-align:center"><code style="user-select:all"><?= e($pendingSecret); ?></code></p>
            <form method="post">
                <?= admin_csrf_field(); ?>
                <input type="hidden" name="do" value="2fa_confirm">
                <label class="adm-label">6-digit code</label>
                <input class="adm-input" type="text" name="code" inputmode="numeric" autocomplete="one-time-code" required>
                <p style="margin-top:12px"><button class="adm-btn" type="submit">Confirm &amp; enable</button></p>
            </form>
            <script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>
            <script>
            (function () {
                var box = document.getElementById('qrBox');
                var canvas = document.createElement('canvas');
                box.appendChild(canvas);
                QRCode.toCanvas(canvas, <?= json_encode($otpauth); ?>, {width: 190}, function (err) {
                    if (err) { box.innerHTML = '<p class="adm-help">QR unavailable - use the manual key below.</p>'; }
                });
            }());
            </script>
        <?php else: ?>
            <p><span class="adm-chip adm-chip-grey">2FA is OFF</span></p>
            <p class="adm-help">Strongly recommended for every account that can publish to the site.</p>
            <form method="post" style="margin-top:14px">
                <?= admin_csrf_field(); ?>
                <input type="hidden" name="do" value="2fa_start">
                <button class="adm-btn" type="submit">Set up 2FA</button>
            </form>
        <?php endif; ?>
    </div>
</div>
<?php admin_layout_end();
