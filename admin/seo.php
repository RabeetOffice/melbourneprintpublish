<?php
require_once __DIR__ . '/includes/auth.php';
require_once ADMIN_SITE_ROOT . '/includes/seo-meta.php';

$user = admin_require_module('seo');

$SEO_FILE = ADMIN_DATA_DIR . DIRECTORY_SEPARATOR . 'seo-pages.json';
$msg = '';
$err = '';

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    admin_require_csrf();
    $all = admin_json_read($SEO_FILE, []);
    $key = (string) ($_POST['key'] ?? '');
    if (!isset($all[$key])) {
        $err = 'Unknown page.';
    } elseif (($_POST['do'] ?? '') === 'revert') {
        // Reset the editable fields to the seeded original snapshot.
        $seed = is_array($all[$key]['seed'] ?? null) ? $all[$key]['seed'] : [];
        $all[$key]['title'] = (string) ($seed['title'] ?? $all[$key]['title'] ?? '');
        $all[$key]['description'] = (string) ($seed['description'] ?? '');
        $all[$key]['keywords'] = (string) ($seed['keywords'] ?? '');
        $all[$key]['schemas'] = is_array($seed['schemas'] ?? null) ? $seed['schemas'] : [];
        $msg = admin_json_write($SEO_FILE, $all) ? 'Reset to the original meta for this page.' : '';
        $err = $msg === '' ? 'Could not write the SEO file.' : '';
    } else {
        $clean = fn(string $v) => trim(preg_replace('~\s+~', ' ', strip_tags($v)));
        $newTitle = $clean((string) ($_POST['title'] ?? ''));
        $newDesc = $clean((string) ($_POST['description'] ?? ''));
        $newKw = $clean((string) ($_POST['keywords'] ?? ''));
        // Schemas: validate each block is parseable JSON; keep raw text.
        $schemas = [];
        $badJson = false;
        foreach ((array) ($_POST['schemas'] ?? []) as $block) {
            $block = trim((string) $block);
            if ($block === '') { continue; }
            if (json_decode($block) === null && strtolower($block) !== 'null') { $badJson = true; }
            $schemas[] = $block;
        }
        if ($badJson) {
            $err = 'One of the schema blocks is not valid JSON. Nothing saved.';
        } elseif ($newTitle === '') {
            $err = 'Title is required.';
        } else {
            $all[$key]['title'] = $newTitle;
            $all[$key]['description'] = $newDesc;
            $all[$key]['keywords'] = $newKw;
            $all[$key]['schemas'] = $schemas;
            $msg = admin_json_write($SEO_FILE, $all) ? 'SEO saved. This page renders it site-wide from the central head.' : '';
            $err = $msg === '' ? 'Could not write the SEO file.' : '';
        }
    }

    // Post/Redirect/Get: a refresh never re-saves. Stay on the edited page.
    admin_flash($err !== '' ? $err : $msg, $err !== '' ? 'error' : 'ok');
    admin_redirect('seo.php' . ($key !== '' ? '?page=' . rawurlencode($key) : ''));
}

$flash = admin_flash_take();
if ($flash['type'] === 'error') { $err = $flash['msg']; } else { $msg = $flash['msg']; }

$all = admin_json_read($SEO_FILE, []);
$editKey = (string) ($_GET['page'] ?? '');
$editing = isset($all[$editKey]) ? $all[$editKey] : null;

admin_layout_start($user, 'seo', 'SEO & Meta');
?>
<div class="adm-page-head">
    <div>
        <div class="adm-eyebrow">Search</div>
        <h1>SEO &amp; <span>Meta</span></h1>
    </div>
    <?php if ($editing): ?><a class="adm-btn adm-btn-ghost adm-btn-sm" href="seo.php">&larr; All pages</a><?php endif; ?>
</div>

<?php if ($msg): ?><div class="adm-alert adm-alert-ok"><?= e($msg); ?></div><?php endif; ?>
<?php if ($err): ?><div class="adm-alert adm-alert-error"><?= e($err); ?></div><?php endif; ?>

<?php if (!$all): ?>
<div class="adm-alert adm-alert-info">No pages seeded yet. Run the SEO migration first.</div>
<?php
elseif ($editing):
    $seed = is_array($editing['seed'] ?? null) ? $editing['seed'] : [];
    $curr = ['title' => (string) ($editing['title'] ?? ''), 'description' => (string) ($editing['description'] ?? ''), 'keywords' => (string) ($editing['keywords'] ?? ''), 'schemas' => (array) ($editing['schemas'] ?? [])];
    $seedNorm = ['title' => (string) ($seed['title'] ?? ''), 'description' => (string) ($seed['description'] ?? ''), 'keywords' => (string) ($seed['keywords'] ?? ''), 'schemas' => (array) ($seed['schemas'] ?? [])];
    $differs = $curr !== $seedNorm;
?>
<div class="adm-card">
    <h2>Edit: <span><?= e($editKey); ?></span></h2>
    <p class="adm-help">
        Status: <?= $differs
            ? '<span class="adm-chip adm-chip-accent">customised</span>'
            : '<span class="adm-chip adm-chip-grey">original</span>'; ?>
        &middot; Public URL: <code>/<?= e($editKey === 'index' ? '' : $editKey . '/'); ?></code>
        &middot; Rendered from the central head (Admin &rarr; Site Settings controls GTM/GA/verification).
    </p>
    <form method="post">
        <?= admin_csrf_field(); ?>
        <input type="hidden" name="key" value="<?= e($editKey); ?>">
        <label class="adm-label">Title tag</label>
        <input class="adm-input" name="title" value="<?= e((string) ($editing['title'] ?? '')); ?>">
        <label class="adm-label">Meta description</label>
        <textarea class="adm-textarea" name="description" rows="3"><?= e((string) ($editing['description'] ?? '')); ?></textarea>
        <label class="adm-label">Meta keywords <small class="adm-muted">(optional)</small></label>
        <input class="adm-input" name="keywords" value="<?= e((string) ($editing['keywords'] ?? '')); ?>">

        <label class="adm-label">Structured data (JSON-LD blocks)</label>
        <div id="schemaList">
        <?php $schemas = (array) ($editing['schemas'] ?? []); if (!$schemas) { $schemas = ['']; } foreach ($schemas as $s): ?>
            <textarea class="adm-textarea" name="schemas[]" rows="5" spellcheck="false" style="margin-bottom:8px;font-family:var(--adm-mono);font-size:13px"><?= e((string) $s); ?></textarea>
        <?php endforeach; ?>
        </div>
        <button class="adm-btn adm-btn-ghost adm-btn-sm" type="button" id="addSchema">+ Add schema block</button>

        <p style="margin-top:16px">
            <button class="adm-btn" type="submit">Save SEO</button>
            <a class="adm-btn adm-btn-ghost" href="<?= e(brand_asset('/' . ($editKey === '404' ? '404.php' : $editKey . '.php'))); ?>" target="_blank" rel="noopener">Preview page</a>
        </p>
    </form>
    <?php if ($differs): ?>
    <form method="post" onsubmit="return confirm('Reset this page to its original meta?');" style="margin-top:8px">
        <?= admin_csrf_field(); ?>
        <input type="hidden" name="key" value="<?= e($editKey); ?>">
        <input type="hidden" name="do" value="revert">
        <button class="adm-btn adm-btn-danger adm-btn-sm" type="submit">Reset to original</button>
    </form>
    <?php endif; ?>
</div>
<script>
document.getElementById('addSchema').addEventListener('click', function () {
    var t = document.createElement('textarea');
    t.className = 'adm-textarea'; t.name = 'schemas[]'; t.rows = 5; t.spellcheck = false;
    t.style.marginBottom = '8px'; t.style.fontFamily = 'var(--adm-mono)'; t.style.fontSize = '13px';
    document.getElementById('schemaList').appendChild(t);
    t.focus();
});
</script>
<?php else: ?>
<div class="adm-card">
    <div class="adm-tablewrap">
    <table class="adm-table">
        <thead><tr><th>Page</th><th>Title</th><th>Status</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($all as $key => $p):
            $seed = is_array($p['seed'] ?? null) ? $p['seed'] : [];
            $differs = (string) ($p['title'] ?? '') !== (string) ($seed['title'] ?? '')
                || (string) ($p['description'] ?? '') !== (string) ($seed['description'] ?? '')
                || (string) ($p['keywords'] ?? '') !== (string) ($seed['keywords'] ?? '')
                || (array) ($p['schemas'] ?? []) !== (array) ($seed['schemas'] ?? []);
        ?>
            <tr>
                <td><code><?= e($key); ?></code></td>
                <td><?= e(mb_strimwidth((string) ($p['title'] ?? ''), 0, 70, '…')); ?></td>
                <td><?= $differs ? '<span class="adm-chip adm-chip-accent">customised</span>' : '<span class="adm-chip adm-chip-grey">original</span>'; ?></td>
                <td><a class="adm-btn adm-btn-ghost adm-btn-sm" href="?page=<?= e(rawurlencode($key)); ?>">Edit</a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </div>
</div>
<?php endif; ?>
<?php admin_layout_end();
