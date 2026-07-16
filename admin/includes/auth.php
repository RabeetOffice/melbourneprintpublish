<?php
/**
 * Admin auth: hardened sessions, users + roles (RBAC), CSRF, login throttle,
 * optional TOTP 2FA. Every admin page starts by requiring this file and
 * calling admin_require_module('<key>') / admin_require_admin() /
 * admin_require_login() BEFORE doing any work.
 */

require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/totp.php';
require_once ADMIN_SITE_ROOT . '/includes/config.php';
require_once ADMIN_SITE_ROOT . '/includes/smtp-mailer.php';
require_once __DIR__ . '/layout.php';

const ADMIN_SESSION_NAME = 'mpp_admin_sess';
const ADMIN_LOGIN_MAX_ATTEMPTS = 5;
const ADMIN_LOGIN_LOCKOUT = 900; // 15 minutes

/* ---------------------------------------------------------------------------
 * Module registry (RBAC). Rule 1: only modules this brand actually has.
 * ------------------------------------------------------------------------- */

function admin_modules(): array
{
    return [
        'dashboard'    => 'Dashboard',
        'posts'        => 'Blog Posts',
        'authors'      => 'Authors',
        'submissions'  => 'Submissions',
        'portfolio'    => 'Portfolio',
        'websites'     => 'Website Portfolio',
        'videos'       => 'Video Trailers',
        'testimonials' => 'Testimonials',
        'seo'          => 'SEO & Meta',
        'settings'     => 'Site Settings',
    ];
}

function admin_module_keys(): array
{
    return array_keys(admin_modules());
}

/* ---------------------------------------------------------------------------
 * Session
 * ------------------------------------------------------------------------- */

function admin_session_start(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }
    $secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
    session_name(ADMIN_SESSION_NAME);
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'secure'   => $secure,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

function admin_security_headers(): void
{
    header('X-Robots-Tag: noindex, nofollow');
    header('X-Frame-Options: DENY');
    header('X-Content-Type-Options: nosniff');
    header('Referrer-Policy: same-origin');
    header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
}

/* ---------------------------------------------------------------------------
 * Users + roles stores
 * ------------------------------------------------------------------------- */

function admin_users_file(): string
{
    return ADMIN_DATA_DIR . DIRECTORY_SEPARATOR . 'users.json';
}

function admin_roles_file(): string
{
    return ADMIN_DATA_DIR . DIRECTORY_SEPARATOR . 'roles.json';
}

function admin_load_users(): array
{
    $data = admin_json_read(admin_users_file(), ['users' => []]);
    return is_array($data['users'] ?? null) ? $data['users'] : [];
}

function admin_save_users(array $users): bool
{
    return admin_json_write(admin_users_file(), ['users' => array_values($users)]);
}

function admin_load_roles(): array
{
    $data = admin_json_read(admin_roles_file(), ['roles' => []]);
    $roles = is_array($data['roles'] ?? null) ? $data['roles'] : [];
    // The locked super-admin role must always exist.
    foreach ($roles as $role) {
        if (($role['name'] ?? '') === 'super-admin') {
            return $roles;
        }
    }
    array_unshift($roles, [
        'name' => 'super-admin', 'label' => 'Super Admin', 'modules' => ['*'], 'locked' => true,
    ]);
    return $roles;
}

function admin_save_roles(array $roles): bool
{
    return admin_json_write(admin_roles_file(), ['roles' => array_values($roles)]);
}

function admin_get_role(string $name): ?array
{
    foreach (admin_load_roles() as $role) {
        if (($role['name'] ?? '') === $name) {
            return $role;
        }
    }
    return null;
}

function admin_find_user(string $username): ?array
{
    foreach (admin_load_users() as $user) {
        if (strcasecmp((string) ($user['username'] ?? ''), $username) === 0) {
            return $user;
        }
    }
    return null;
}

function admin_update_user(string $username, array $patch): bool
{
    $users = admin_load_users();
    foreach ($users as $i => $user) {
        if (strcasecmp((string) ($user['username'] ?? ''), $username) === 0) {
            $users[$i] = array_merge($user, $patch);
            return admin_save_users($users);
        }
    }
    return false;
}

/* ---------------------------------------------------------------------------
 * Current user / RBAC checks
 * ------------------------------------------------------------------------- */

function admin_current_user(): ?array
{
    admin_session_start();
    $username = $_SESSION['admin_user'] ?? null;
    if (!is_string($username) || $username === '') {
        return null;
    }
    $user = admin_find_user($username);
    if (!$user) {
        // Account was deleted while logged in: kill the session.
        unset($_SESSION['admin_user']);
        return null;
    }
    return $user;
}

/** Module keys this user can access. Unknown role => zero modules (fail closed). */
function admin_user_modules(?array $user): array
{
    if (!$user) {
        return [];
    }
    $role = admin_get_role((string) ($user['role'] ?? ''));
    if (!$role) {
        return [];
    }
    $modules = is_array($role['modules'] ?? null) ? $role['modules'] : [];
    if (in_array('*', $modules, true)) {
        return admin_module_keys();
    }
    return array_values(array_intersect($modules, admin_module_keys()));
}

function admin_is_super(?array $user): bool
{
    if (!$user) {
        return false;
    }
    $role = admin_get_role((string) ($user['role'] ?? ''));
    return $role && in_array('*', (array) ($role['modules'] ?? []), true);
}

function admin_can(string $module, ?array $user = null): bool
{
    $user = $user ?? admin_current_user();
    return in_array($module, admin_user_modules($user), true);
}

function admin_url(string $path = ''): string
{
    $base = rtrim(dirname(dirname($_SERVER['SCRIPT_NAME'] ?? '/admin/x')), '/\\');
    if (basename($base) !== 'admin') {
        $base .= '/admin';
    }
    return $base . '/' . ltrim($path, '/');
}

/** First page this user is allowed to see (never land on a 403 after login). */
function admin_home_url(?array $user = null): string
{
    $user = $user ?? admin_current_user();
    $map = [
        'dashboard'    => 'dashboard.php',
        'posts'        => 'posts.php',
        'authors'      => 'authors.php',
        'submissions'  => 'submissions.php',
        'portfolio'    => 'portfolio.php',
        'websites'     => 'websites.php',
        'videos'       => 'video-trailers.php',
        'testimonials' => 'testimonials.php',
        'seo'          => 'seo.php',
        'settings'     => 'settings.php',
    ];
    foreach (admin_user_modules($user) as $module) {
        if (isset($map[$module])) {
            return admin_url($map[$module]);
        }
    }
    return admin_url('account.php');
}

function admin_require_login(): array
{
    admin_session_start();
    admin_security_headers();
    $user = admin_current_user();
    if (!$user) {
        header('Location: ' . admin_url('login.php'));
        exit;
    }
    return $user;
}

function admin_require_module(string $module): array
{
    $user = admin_require_login();
    if (!admin_can($module, $user)) {
        http_response_code(403);
        require __DIR__ . '/forbidden.php';
        exit;
    }
    return $user;
}

function admin_require_admin(): array
{
    $user = admin_require_login();
    if (!admin_is_super($user)) {
        http_response_code(403);
        require __DIR__ . '/forbidden.php';
        exit;
    }
    return $user;
}

/* ---------------------------------------------------------------------------
 * CSRF
 * ------------------------------------------------------------------------- */

function admin_csrf_token(): string
{
    admin_session_start();
    if (empty($_SESSION['admin_csrf'])) {
        $_SESSION['admin_csrf'] = admin_random_token();
    }
    return $_SESSION['admin_csrf'];
}

function admin_csrf_field(): string
{
    return '<input type="hidden" name="csrf" value="' . e(admin_csrf_token()) . '">';
}

function admin_verify_csrf(): bool
{
    admin_session_start();
    $sent = $_POST['csrf'] ?? ($_SERVER['HTTP_X_CSRF_TOKEN'] ?? '');
    $known = $_SESSION['admin_csrf'] ?? '';
    return is_string($sent) && $sent !== '' && is_string($known) && $known !== ''
        && hash_equals($known, $sent);
}

/** Call at the top of every state-changing POST handler. */
function admin_require_csrf(): void
{
    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST' && !admin_verify_csrf()) {
        http_response_code(419);
        header('Content-Type: application/json');
        echo json_encode(['ok' => false, 'error' => 'Session expired. Reload the page and try again.']);
        exit;
    }
}

/* ---------------------------------------------------------------------------
 * Flash + Post/Redirect/Get. A form page handles its POST, stashes the outcome
 * as a one-shot flash, then 303-redirects to a clean GET of itself. A browser
 * refresh then re-requests the GET (never the POST), so nothing is submitted
 * twice - no duplicate rows and no "confirm form resubmission" dialog.
 * ------------------------------------------------------------------------- */

function admin_flash(string $msg, string $type = 'ok'): void
{
    if ($msg === '') {
        return;
    }
    admin_session_start();
    $_SESSION['admin_flash'] = ['msg' => $msg, 'type' => $type === 'error' ? 'error' : 'ok'];
}

/** Pop the pending flash (shown once). Returns ['msg'=>'','type'=>'ok'] if none. */
function admin_flash_take(): array
{
    admin_session_start();
    $f = $_SESSION['admin_flash'] ?? null;
    unset($_SESSION['admin_flash']);
    return is_array($f)
        ? ['msg' => (string) ($f['msg'] ?? ''), 'type' => (($f['type'] ?? '') === 'error' ? 'error' : 'ok')]
        : ['msg' => '', 'type' => 'ok'];
}

/** 303-redirect to a relative admin URL, then stop. Call before any output. */
function admin_redirect(string $to): void
{
    header('Location: ' . $to, true, 303);
    exit;
}

/**
 * End a POST handler: flash the outcome ($err wins over $msg) and redirect to a
 * clean GET. On error the edit context is preserved (?<editKey>=<id>#form) so
 * the edit form re-opens; on success we land on the plain list. Never returns.
 */
function admin_prg_finish(string $page, string $msg, string $err, string $editKey = 'edit', string $anchor = 'form'): void
{
    admin_flash($err !== '' ? $err : $msg, $err !== '' ? 'error' : 'ok');
    $suffix = '';
    if ($err !== '' && $editKey !== '' && ($_GET[$editKey] ?? '') !== '') {
        $suffix = '?' . $editKey . '=' . rawurlencode((string) $_GET[$editKey]) . ($anchor !== '' ? '#' . $anchor : '');
    }
    admin_redirect($page . $suffix);
}

/* ---------------------------------------------------------------------------
 * Login throttle: 5 failures per (IP + username) -> 15 min lockout
 * ------------------------------------------------------------------------- */

function admin_throttle_file(): string
{
    return ADMIN_DATA_DIR . DIRECTORY_SEPARATOR . 'login-attempts.json';
}

function admin_throttle_key(string $username, string $stage = 'pw'): string
{
    return sha1($stage . '|' . admin_client_ip() . '|' . strtolower($username));
}

function admin_throttle_check(string $username, string $stage = 'pw'): int
{
    $data = admin_json_read(admin_throttle_file(), []);
    $entry = $data[admin_throttle_key($username, $stage)] ?? null;
    if (!is_array($entry)) {
        return 0;
    }
    if (($entry['count'] ?? 0) >= ADMIN_LOGIN_MAX_ATTEMPTS) {
        $remaining = ((int) ($entry['last'] ?? 0)) + ADMIN_LOGIN_LOCKOUT - time();
        if ($remaining > 0) {
            return $remaining;
        }
    }
    return 0;
}

function admin_throttle_hit(string $username, string $stage = 'pw'): void
{
    $data = admin_json_read(admin_throttle_file(), []);
    $key = admin_throttle_key($username, $stage);
    $entry = is_array($data[$key] ?? null) ? $data[$key] : ['count' => 0, 'last' => 0];
    if (time() - (int) ($entry['last'] ?? 0) > ADMIN_LOGIN_LOCKOUT) {
        $entry['count'] = 0; // stale window, restart the count
    }
    $entry['count'] = (int) ($entry['count'] ?? 0) + 1;
    $entry['last'] = time();
    $data[$key] = $entry;
    // prune stale entries so the file never grows unbounded
    foreach ($data as $k => $v) {
        if (time() - (int) ($v['last'] ?? 0) > ADMIN_LOGIN_LOCKOUT * 4) {
            unset($data[$k]);
        }
    }
    admin_json_write(admin_throttle_file(), $data);
}

function admin_throttle_clear(string $username, string $stage = 'pw'): void
{
    $data = admin_json_read(admin_throttle_file(), []);
    unset($data[admin_throttle_key($username, $stage)]);
    admin_json_write(admin_throttle_file(), $data);
}

/* ---------------------------------------------------------------------------
 * reCAPTCHA on login. Gate the skip on the REAL peer address, never on the
 * spoofable Host header. verify_recaptcha() (site helper) already returns
 * _skipped when no keys are configured.
 * ------------------------------------------------------------------------- */

function admin_recaptcha_enabled(): bool
{
    return defined('RECAPTCHA_SECRET_KEY') && RECAPTCHA_SECRET_KEY !== ''
        && defined('RECAPTCHA_SITE_KEY') && RECAPTCHA_SITE_KEY !== '';
}

function admin_recaptcha_ok(): bool
{
    if (admin_is_loopback()) {
        return true; // local dev: domain-locked keys can never validate here
    }
    if (!admin_recaptcha_enabled()) {
        return true;
    }
    $token = (string) ($_POST['recaptcha_token'] ?? '');
    if ($token === '') {
        return false;
    }
    $result = verify_recaptcha($token, 'admin_login', 0.5);
    return !empty($result['_ok']);
}

/* ---------------------------------------------------------------------------
 * Login / logout
 * ------------------------------------------------------------------------- */

function admin_login_password(string $username, string $password): array
{
    $wait = admin_throttle_check($username);
    if ($wait > 0) {
        return ['ok' => false, 'error' => 'Too many attempts. Try again in ' . ceil($wait / 60) . ' minute(s).'];
    }
    $user = admin_find_user($username);
    if (!$user || !password_verify($password, (string) ($user['password'] ?? ''))) {
        admin_throttle_hit($username);
        return ['ok' => false, 'error' => 'Invalid username or password.'];
    }
    admin_throttle_clear($username);

    admin_session_start();
    if (!empty($user['totp_secret'])) {
        // Two-step: password verified, now require the TOTP code.
        $_SESSION['admin_2fa_pending'] = $user['username'];
        $_SESSION['admin_2fa_time'] = time();
        return ['ok' => true, 'twofactor' => true];
    }
    admin_complete_login($user);
    return ['ok' => true, 'twofactor' => false];
}

function admin_login_totp(string $code): array
{
    admin_session_start();
    $username = $_SESSION['admin_2fa_pending'] ?? '';
    $started = (int) ($_SESSION['admin_2fa_time'] ?? 0);
    if (!is_string($username) || $username === '' || time() - $started > 300) {
        unset($_SESSION['admin_2fa_pending'], $_SESSION['admin_2fa_time']);
        return ['ok' => false, 'error' => 'Login expired. Start again.', 'restart' => true];
    }
    $wait = admin_throttle_check($username, '2fa');
    if ($wait > 0) {
        return ['ok' => false, 'error' => 'Too many attempts. Try again in ' . ceil($wait / 60) . ' minute(s).'];
    }
    $user = admin_find_user($username);
    if (!$user || empty($user['totp_secret'])) {
        return ['ok' => false, 'error' => 'Login expired. Start again.', 'restart' => true];
    }

    $code = trim($code);
    $valid = totp_verify((string) $user['totp_secret'], $code);
    if (!$valid) {
        // Fall back to single-use backup codes.
        $hashes = is_array($user['totp_backup'] ?? null) ? $user['totp_backup'] : [];
        if ($hashes && totp_consume_backup_code($hashes, $code)) {
            admin_update_user($username, ['totp_backup' => $hashes]);
            $valid = true;
        }
    }
    if (!$valid) {
        admin_throttle_hit($username, '2fa');
        return ['ok' => false, 'error' => 'Invalid code.'];
    }
    admin_throttle_clear($username, '2fa');
    unset($_SESSION['admin_2fa_pending'], $_SESSION['admin_2fa_time']);
    admin_complete_login($user);
    return ['ok' => true];
}

function admin_complete_login(array $user): void
{
    admin_session_start();
    session_regenerate_id(true);
    $_SESSION['admin_user'] = (string) $user['username'];
    $_SESSION['admin_csrf'] = admin_random_token();
    admin_update_user((string) $user['username'], ['last_login' => date('c')]);
}

function admin_logout(): void
{
    admin_session_start();
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
    }
    session_destroy();
}
