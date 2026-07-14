<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/content-store.php';

$user = admin_require_module('portfolio');

$msg = '';
$err = '';

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    admin_require_csrf();
    $do = (string) ($_POST['do'] ?? '');
    $items = mpp_portfolio_read();

    $clean = fn(string $v) => trim(strip_tags(ukph_strip_php($v)));

    if ($do === 'save') {
        $id = $clean((string) ($_POST['id'] ?? ''));
        $entry = [
            'id'     => $id,
            'hidden' => ($_POST['hidden'] ?? '') === '1' ? 'hidden' : '',
            'image'  => $clean((string) ($_POST['image'] ?? '')),
            'alt'    => $clean((string) ($_POST['alt'] ?? '')),
            'name'   => $clean((string) ($_POST['name'] ?? '')),
            'link'   => $clean((string) ($_POST['link'] ?? '')),
        ];
        // Uploaded cover wins over any manually pasted image URL.
        if (!empty($_FILES['cover']['name'])) {
            [$ok, $result] = admin_upload_image_webp($_FILES['cover'], 'assets/images/portfolio', $entry['name'] ?: 'cover');
            if ($ok) {
                $entry['image'] = '/' . $result;
            } else {
                $err = 'Cover upload failed: ' . $result;
            }
        }
        // No cover URL and no upload: auto-fetch the cover from the Amazon
        // link's ASIN. Only fills an empty field — saved covers are never changed.
        if ($entry['image'] === '' && $entry['link'] !== '') {
            $entry['image'] = mpp_amazon_cover_from_link($entry['link']);
        }
        if ($entry['name'] === '') {
            $err = $err ?: 'The book name is required.';
        }
        if ($err === '') {
            if ($id === '') {
                $entry['id'] = mpp_next_id($items);
                array_unshift($items, $entry);
            } else {
                $found = false;
                foreach ($items as $i => $existing) {
                    if ((string) ($existing['id'] ?? '') === $id) {
                        $items[$i] = $entry;
                        $found = true;
                        break;
                    }
                }
                if (!$found) {
                    $err = 'Item not found.';
                }
            }
        }
        if ($err === '') {
            $msg = mpp_portfolio_write($items) ? 'Portfolio saved and the live data file was regenerated.' : '';
            $err = $msg === '' ? 'Could not write the portfolio data file (lint failed or IO error).' : '';
        }
    } elseif ($do === 'delete') {
        $id = (string) ($_POST['id'] ?? '');
        $items = array_values(array_filter($items, fn($i) => (string) ($i['id'] ?? '') !== $id));
        $msg = mpp_portfolio_write($items) ? 'Item deleted.' : '';
        $err = $msg === '' ? 'Could not write the portfolio data file.' : '';
    } elseif ($do === 'toggle') {
        $id = (string) ($_POST['id'] ?? '');
        foreach ($items as $i => $existing) {
            if ((string) ($existing['id'] ?? '') === $id) {
                $items[$i]['hidden'] = ($existing['hidden'] ?? '') === '' ? 'hidden' : '';
            }
        }
        $msg = mpp_portfolio_write($items) ? 'Visibility updated.' : '';
        $err = $msg === '' ? 'Could not write the portfolio data file.' : '';
    } elseif ($do === 'move') {
        $id = (string) ($_POST['id'] ?? '');
        $dir = (string) ($_POST['dir'] ?? '');
        foreach ($items as $i => $existing) {
            if ((string) ($existing['id'] ?? '') === $id) {
                $j = $dir === 'up' ? $i - 1 : $i + 1;
                if ($j >= 0 && $j < count($items)) {
                    [$items[$i], $items[$j]] = [$items[$j], $items[$i]];
                }
                break;
            }
        }
        $msg = mpp_portfolio_write($items) ? 'Order updated.' : '';
        $err = $msg === '' ? 'Could not write the portfolio data file.' : '';
    }

    // Post/Redirect/Get: a refresh after this can never re-run the POST.
    admin_prg_finish('portfolio.php', $msg, $err);
}

$flash = admin_flash_take();
if ($flash['type'] === 'error') { $err = $flash['msg']; } else { $msg = $flash['msg']; }

$items = mpp_portfolio_read();
$editId = (string) ($_GET['edit'] ?? '');
$editing = null;
foreach ($items as $item) {
    if ($editId !== '' && (string) ($item['id'] ?? '') === $editId) {
        $editing = $item;
        break;
    }
}

admin_layout_start($user, 'portfolio', 'Portfolio');
?>
<div class="adm-page-head">
    <div>
        <div class="adm-eyebrow">Showcase</div>
        <h1>Port<span>folio</span></h1>
    </div>
    <a class="adm-btn adm-btn-sm" href="portfolio.php#form">+ Add book</a>
</div>

<?php if ($msg): ?><div class="adm-alert adm-alert-ok"><?= e($msg); ?></div><?php endif; ?>
<?php if ($err): ?><div class="adm-alert adm-alert-error"><?= e($err); ?></div><?php endif; ?>

<div class="adm-card" id="form">
    <h2><?= $editing ? 'Edit <span>book</span>' : 'Add <span>book</span>'; ?></h2>
    <form method="post" enctype="multipart/form-data">
        <?= admin_csrf_field(); ?>
        <input type="hidden" name="do" value="save">
        <input type="hidden" name="id" value="<?= e((string) ($editing['id'] ?? '')); ?>">
        <div class="adm-row">
            <div>
                <label class="adm-label">Book name</label>
                <input class="adm-input" type="text" name="name" required value="<?= e((string) ($editing['name'] ?? '')); ?>">
            </div>
            <div>
                <label class="adm-label">Image alt text</label>
                <input class="adm-input" type="text" name="alt" value="<?= e((string) ($editing['alt'] ?? '')); ?>">
            </div>
        </div>
        <label class="adm-label">Amazon / store link</label>
        <input class="adm-input" type="text" name="link" placeholder="https://www.amazon.com/&hellip;" value="<?= e((string) ($editing['link'] ?? '')); ?>">
        <div class="adm-row" style="margin-top:14px">
            <div>
                <label class="adm-label" style="margin-top:0">Cover image URL (or upload &rarr;)</label>
                <input class="adm-input" type="text" name="image" placeholder="https://m.media-amazon.com/&hellip; or /assets/images/portfolio/&hellip;" value="<?= e((string) ($editing['image'] ?? '')); ?>">
                <p class="adm-help">An uploaded file overrides this URL and is converted to WebP. Leave both empty to auto-fetch the cover from the Amazon link.</p>
            </div>
            <div>
                <label class="adm-label" style="margin-top:0">Upload cover</label>
                <input class="adm-input" type="file" name="cover" accept="image/*">
            </div>
        </div>
        <label class="adm-label"><input type="checkbox" name="hidden" value="1" <?= ($editing['hidden'] ?? '') !== '' ? 'checked' : ''; ?>> Hidden (kept in the data file, not shown on the site)</label>
        <p style="margin-top:16px">
            <button class="adm-btn" type="submit"><?= $editing ? 'Save changes' : 'Add book'; ?></button>
            <?php if ($editing): ?><a class="adm-btn adm-btn-ghost" href="portfolio.php">Cancel</a><?php endif; ?>
        </p>
    </form>
</div>

<div class="adm-card">
    <h2>All <span>books</span> <small class="adm-muted" style="font-weight:400">(<?= count($items); ?>)</small></h2>
    <div class="adm-tablewrap">
    <table class="adm-table">
        <thead><tr><th></th><th>Name</th><th>Link</th><th>Status</th><th style="width:230px"></th></tr></thead>
        <tbody>
        <?php foreach ($items as $item): $id = (string) ($item['id'] ?? ''); ?>
            <tr>
                <td><img class="thumb" loading="lazy" src="<?= e(preg_match('~^https?://~i', (string) $item['image']) ? $item['image'] : brand_asset((string) $item['image'])); ?>" alt=""></td>
                <td><strong><?= e((string) $item['name']); ?></strong></td>
                <td><small class="adm-muted"><?= e(mb_strimwidth((string) $item['link'], 0, 44, '…')); ?></small></td>
                <td><span class="adm-chip <?= ($item['hidden'] ?? '') === '' ? 'adm-chip-green' : 'adm-chip-grey'; ?>"><?= ($item['hidden'] ?? '') === '' ? 'Visible' : 'Hidden'; ?></span></td>
                <td style="white-space:nowrap">
                    <a class="adm-btn adm-btn-ghost adm-btn-sm" href="?edit=<?= e(rawurlencode($id)); ?>#form">Edit</a>
                    <form method="post" style="display:inline"><?= admin_csrf_field(); ?><input type="hidden" name="do" value="move"><input type="hidden" name="id" value="<?= e($id); ?>"><input type="hidden" name="dir" value="up"><button class="adm-btn adm-btn-ghost adm-btn-sm" title="Move up">&uarr;</button></form>
                    <form method="post" style="display:inline"><?= admin_csrf_field(); ?><input type="hidden" name="do" value="move"><input type="hidden" name="id" value="<?= e($id); ?>"><input type="hidden" name="dir" value="down"><button class="adm-btn adm-btn-ghost adm-btn-sm" title="Move down">&darr;</button></form>
                    <form method="post" style="display:inline"><?= admin_csrf_field(); ?><input type="hidden" name="do" value="toggle"><input type="hidden" name="id" value="<?= e($id); ?>"><button class="adm-btn adm-btn-ghost adm-btn-sm"><?= ($item['hidden'] ?? '') === '' ? 'Hide' : 'Show'; ?></button></form>
                    <form method="post" style="display:inline" onsubmit="return confirm('Delete this portfolio item?');"><?= admin_csrf_field(); ?><input type="hidden" name="do" value="delete"><input type="hidden" name="id" value="<?= e($id); ?>"><button class="adm-btn adm-btn-danger adm-btn-sm">&times;</button></form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </div>
</div>
<?php admin_layout_end();
