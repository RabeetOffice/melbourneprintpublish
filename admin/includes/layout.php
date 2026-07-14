<?php
/**
 * Admin shell: sidebar (desktop) / top bar + hamburger (mobile).
 * Nav is built from the user's accessible modules; access itself is enforced
 * server-side on every page, the nav is presentation only.
 *
 * Usage:  admin_layout_start($user, 'posts', 'Blog Posts');  ...page... admin_layout_end();
 */

function admin_nav_items(array $user): array
{
    $items = [];
    $modules = admin_user_modules($user);
    $defs = [
        'dashboard'    => ['dashboard.php', 'Dashboard', 'M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z'],
        'posts'        => ['posts.php', 'Blog Posts', 'M19 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2zM7 7h10v2H7V7zm0 4h10v2H7v-2zm0 4h7v2H7v-2z'],
        'authors'      => ['authors.php', 'Authors', 'M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z'],
        'submissions'  => ['submissions.php', 'Submissions', 'M20 4H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2zm0 4-8 5-8-5V6l8 5 8-5v2z'],
        'portfolio'    => ['portfolio.php', 'Portfolio', 'M20 6h-4V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2H4a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2zm-10-2h4v2h-4V4z'],
        'websites'     => ['websites.php', 'Websites', 'M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20zm6.93 6h-2.95a15.6 15.6 0 0 0-1.38-3.56A8.03 8.03 0 0 1 18.93 8zM12 4.04A13.9 13.9 0 0 1 13.91 8h-3.82A13.9 13.9 0 0 1 12 4.04zM4.26 14a8.1 8.1 0 0 1 0-4h3.38a16.5 16.5 0 0 0 0 4H4.26zm.81 2h2.95c.32 1.25.78 2.45 1.38 3.56A8.03 8.03 0 0 1 5.07 16zm2.95-8H5.07a8.03 8.03 0 0 1 4.33-3.56A15.6 15.6 0 0 0 8.02 8zM12 19.96A13.9 13.9 0 0 1 10.09 16h3.82A13.9 13.9 0 0 1 12 19.96zM14.34 14H9.66a14.6 14.6 0 0 1 0-4h4.68a14.6 14.6 0 0 1 0 4zm.26 5.56c.6-1.11 1.06-2.31 1.38-3.56h2.95a8.03 8.03 0 0 1-4.33 3.56zM16.36 14a16.5 16.5 0 0 0 0-4h3.38a8.1 8.1 0 0 1 0 4h-3.38z'],
        'testimonials' => ['testimonials.php', 'Testimonials', 'M20 2H4a2 2 0 0 0-2 2v18l4-4h14a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2zM7 9h10v2H7V9zm6 5H7v-2h6v2zm4-6H7V6h10v2z'],
        'seo'          => ['seo.php', 'SEO &amp; Meta', 'M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20zm0 3a7 7 0 0 1 6.32 4H14.9A9.6 9.6 0 0 0 12 5.06 9.6 9.6 0 0 0 9.1 9H5.68A7 7 0 0 1 12 5zm0 14a7 7 0 0 1-6.32-4H9.1a9.6 9.6 0 0 0 2.9 3.94A9.6 9.6 0 0 0 14.9 15h3.42A7 7 0 0 1 12 19z'],
        'settings'     => ['settings.php', 'Site Settings', 'M19.14 12.94a7.5 7.5 0 0 0 .05-.94 7.5 7.5 0 0 0-.05-.94l2.03-1.58a.5.5 0 0 0 .12-.64l-1.92-3.32a.5.5 0 0 0-.61-.22l-2.39.96a7.3 7.3 0 0 0-1.62-.94l-.36-2.54A.5.5 0 0 0 13.9 2h-3.8a.5.5 0 0 0-.49.42l-.36 2.54c-.59.24-1.13.56-1.62.94l-2.39-.96a.5.5 0 0 0-.61.22L2.71 8.48a.5.5 0 0 0 .12.64l2.03 1.58a7.5 7.5 0 0 0 0 1.88l-2.03 1.58a.5.5 0 0 0-.12.64l1.92 3.32c.13.23.4.32.61.22l2.39-.96c.49.38 1.03.7 1.62.94l.36 2.54a.5.5 0 0 0 .49.42h3.8a.5.5 0 0 0 .49-.42l.36-2.54a7.3 7.3 0 0 0 1.62-.94l2.39.96c.22.09.48 0 .61-.22l1.92-3.32a.5.5 0 0 0-.12-.64l-2.03-1.58zM12 15.5A3.5 3.5 0 1 1 12 8.5a3.5 3.5 0 0 1 0 7z'],
    ];
    foreach ($defs as $key => $def) {
        if (in_array($key, $modules, true)) {
            $items[] = ['href' => $def[0], 'label' => $def[1], 'icon' => $def[2], 'key' => $key];
        }
    }
    if (admin_is_super($user)) {
        $items[] = [
            'href' => 'users.php', 'label' => 'Roles & Users', 'key' => 'users',
            'icon' => 'M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5s-3 1.34-3 3 1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5C15 14.17 10.33 13 8 13zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z',
        ];
    }
    $items[] = [
        'href' => 'account.php', 'label' => 'My Account', 'key' => 'account',
        'icon' => 'M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z',
    ];
    return $items;
}

function admin_layout_start(array $user, string $active, string $title): void
{
    $nav = admin_nav_items($user);
    $role = admin_get_role((string) ($user['role'] ?? ''));
    $roleLabel = $role ? (string) ($role['label'] ?? $role['name']) : 'No role';
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title><?= e($title); ?> &middot; Admin &middot; <?= e(BRAND_NAME); ?></title>
    <link rel="stylesheet" href="assets/admin.css?v=1">
</head>
<body class="adm">
<a class="adm-skip" href="#adm-main">Skip to content</a>
<div class="adm-shell">
    <header class="adm-topbar">
        <button class="adm-burger" id="admBurger" aria-label="Menu" aria-expanded="false">
            <span></span><span></span><span></span>
        </button>
        <img src="<?= e(brand_asset('/assets/images/logo.png')); ?>" alt="<?= e(BRAND_NAME); ?>" class="adm-topbar-logo">
        <span class="adm-topbar-title"><?= e($title); ?></span>
    </header>
    <aside class="adm-sidebar" id="admSidebar">
        <div class="adm-brand">
            <img src="<?= e(brand_asset('/assets/images/logo.png')); ?>" alt="<?= e(BRAND_NAME); ?>">
            <div class="adm-brand-rule"></div>
        </div>
        <nav class="adm-nav" aria-label="Admin">
            <?php foreach ($nav as $item): ?>
            <a href="<?= e($item['href']); ?>" class="adm-nav-link<?= $item['key'] === $active ? ' is-active' : ''; ?>">
                <svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><path d="<?= e($item['icon']); ?>" fill="currentColor"/></svg>
                <span><?= e($item['label']); ?></span>
            </a>
            <?php endforeach; ?>
        </nav>
        <div class="adm-side-foot">
            <a class="adm-viewsite" href="<?= e(brand_asset('/index.php')); ?>" target="_blank" rel="noopener">
                <svg viewBox="0 0 24 24" width="16" height="16" aria-hidden="true"><path d="M14 3v2h3.59l-9.83 9.83 1.41 1.41L19 6.41V10h2V3h-7zM19 19H5V5h7V3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7h-2v7z" fill="currentColor"/></svg>
                View website
            </a>
            <div class="adm-userchip">
                <div class="adm-userchip-avatar"><?= e(strtoupper(substr((string) $user['username'], 0, 1))); ?></div>
                <div class="adm-userchip-meta">
                    <strong><?= e((string) $user['username']); ?></strong>
                    <small><?= e($roleLabel); ?></small>
                </div>
                <a href="logout.php" class="adm-logout" title="Log out">
                    <svg viewBox="0 0 24 24" width="16" height="16" aria-hidden="true"><path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5-5-5zM4 5h8V3H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h8v-2H4V5z" fill="currentColor"/></svg>
                </a>
            </div>
        </div>
    </aside>
    <div class="adm-backdrop" id="admBackdrop"></div>
    <main class="adm-main" id="adm-main">
<?php
}

function admin_layout_end(): void
{
?>
    </main>
</div>
<script src="assets/admin.js?v=1"></script>
</body>
</html>
<?php
}
