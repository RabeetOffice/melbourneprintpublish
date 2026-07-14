<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/content-store.php';

$user = admin_require_module('testimonials');

$msg = '';
$err = '';

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    admin_require_csrf();
    $do = (string) ($_POST['do'] ?? '');
    $items = mpp_testimonials_read();
    $clean = fn(string $v) => trim(strip_tags(ukph_strip_php($v)));

    if ($do === 'save') {
        $id = $clean((string) ($_POST['id'] ?? ''));
        $entry = [
            'id'     => $id,
            'hidden' => ($_POST['hidden'] ?? '') === '1' ? 'hidden-post' : '',
            'name'   => $clean((string) ($_POST['name'] ?? '')),
            'text'   => $clean((string) ($_POST['text'] ?? '')),
            'link'   => $clean((string) ($_POST['link'] ?? '')),
        ];
        if ($entry['link'] === '') {
            $entry['link'] = 'javascript:void(0);'; // the data file's own placeholder convention
        }
        if ($entry['name'] === '' || $entry['text'] === '') {
            $err = 'Name and review text are required.';
        } elseif ($id === '') {
            $entry['id'] = mpp_next_id($items);
            $items[] = $entry;
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
                $err = 'Testimonial not found.';
            }
        }
        if ($err === '') {
            $msg = mpp_testimonials_write($items) ? 'Testimonials saved and the live data file was regenerated.' : '';
            $err = $msg === '' ? 'Could not write the testimonials data file (lint failed or IO error).' : '';
        }
    } elseif ($do === 'delete') {
        $id = (string) ($_POST['id'] ?? '');
        $items = array_values(array_filter($items, fn($i) => (string) ($i['id'] ?? '') !== $id));
        $msg = mpp_testimonials_write($items) ? 'Testimonial deleted.' : '';
        $err = $msg === '' ? 'Could not write the testimonials data file.' : '';
    } elseif ($do === 'toggle') {
        $id = (string) ($_POST['id'] ?? '');
        foreach ($items as $i => $existing) {
            if ((string) ($existing['id'] ?? '') === $id) {
                $items[$i]['hidden'] = ($existing['hidden'] ?? '') === '' ? 'hidden-post' : '';
            }
        }
        $msg = mpp_testimonials_write($items) ? 'Visibility updated.' : '';
        $err = $msg === '' ? 'Could not write the testimonials data file.' : '';
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
        $msg = mpp_testimonials_write($items) ? 'Order updated.' : '';
        $err = $msg === '' ? 'Could not write the testimonials data file.' : '';
    }

    // Post/Redirect/Get: a refresh after this can never re-run the POST.
    admin_prg_finish('testimonials.php', $msg, $err);
}

$flash = admin_flash_take();
if ($flash['type'] === 'error') { $err = $flash['msg']; } else { $msg = $flash['msg']; }

$items = mpp_testimonials_read();
$editId = (string) ($_GET['edit'] ?? '');
$editing = null;
foreach ($items as $item) {
    if ($editId !== '' && (string) ($item['id'] ?? '') === $editId) {
        $editing = $item;
        break;
    }
}

admin_layout_start($user, 'testimonials', 'Testimonials');
?>
<div class="adm-page-head">
    <div>
        <div class="adm-eyebrow">Reviews</div>
        <h1>Testi<span>monials</span></h1>
    </div>
    <a class="adm-btn adm-btn-sm" href="testimonials.php#form">+ Add testimonial</a>
</div>

<?php if ($msg): ?><div class="adm-alert adm-alert-ok"><?= e($msg); ?></div><?php endif; ?>
<?php if ($err): ?><div class="adm-alert adm-alert-error"><?= e($err); ?></div><?php endif; ?>

<div class="adm-card" id="form">
    <h2><?= $editing ? 'Edit <span>testimonial</span>' : 'Add <span>testimonial</span>'; ?></h2>
    <form method="post">
        <?= admin_csrf_field(); ?>
        <input type="hidden" name="do" value="save">
        <input type="hidden" name="id" value="<?= e((string) ($editing['id'] ?? '')); ?>">
        <div class="adm-row">
            <div>
                <label class="adm-label">Reviewer name</label>
                <input class="adm-input" type="text" name="name" required value="<?= e((string) ($editing['name'] ?? '')); ?>">
            </div>
            <div>
                <label class="adm-label">Review link (Trustpilot / Google / reviews.io)</label>
                <input class="adm-input" type="text" name="link" placeholder="https://&hellip; (optional)" value="<?= e((string) (($editing['link'] ?? '') === 'javascript:void(0);' ? '' : ($editing['link'] ?? ''))); ?>">
            </div>
        </div>
        <label class="adm-label">Review text</label>
        <textarea class="adm-textarea" name="text" rows="4" required><?= e((string) ($editing['text'] ?? '')); ?></textarea>
        <label class="adm-label"><input type="checkbox" name="hidden" value="1" <?= ($editing['hidden'] ?? '') !== '' ? 'checked' : ''; ?>> Hidden (kept in the data file, not shown on the site)</label>
        <p style="margin-top:16px">
            <button class="adm-btn" type="submit"><?= $editing ? 'Save changes' : 'Add testimonial'; ?></button>
            <?php if ($editing): ?><a class="adm-btn adm-btn-ghost" href="testimonials.php">Cancel</a><?php endif; ?>
        </p>
    </form>
</div>

<div class="adm-card">
    <h2>All <span>testimonials</span> <small class="adm-muted" style="font-weight:400">(<?= count($items); ?>)</small></h2>
    <div class="adm-tablewrap">
    <table class="adm-table">
        <thead><tr><th>Name</th><th>Review</th><th>Status</th><th style="width:230px"></th></tr></thead>
        <tbody>
        <?php foreach ($items as $item): $id = (string) ($item['id'] ?? ''); ?>
            <tr>
                <td><strong><?= e((string) $item['name']); ?></strong></td>
                <td><small><?= e(mb_strimwidth((string) $item['text'], 0, 90, '…')); ?></small></td>
                <td><span class="adm-chip <?= ($item['hidden'] ?? '') === '' ? 'adm-chip-green' : 'adm-chip-grey'; ?>"><?= ($item['hidden'] ?? '') === '' ? 'Visible' : 'Hidden'; ?></span></td>
                <td style="white-space:nowrap">
                    <a class="adm-btn adm-btn-ghost adm-btn-sm" href="?edit=<?= e(rawurlencode($id)); ?>#form">Edit</a>
                    <form method="post" style="display:inline"><?= admin_csrf_field(); ?><input type="hidden" name="do" value="move"><input type="hidden" name="id" value="<?= e($id); ?>"><input type="hidden" name="dir" value="up"><button class="adm-btn adm-btn-ghost adm-btn-sm" title="Move up">&uarr;</button></form>
                    <form method="post" style="display:inline"><?= admin_csrf_field(); ?><input type="hidden" name="do" value="move"><input type="hidden" name="id" value="<?= e($id); ?>"><input type="hidden" name="dir" value="down"><button class="adm-btn adm-btn-ghost adm-btn-sm" title="Move down">&darr;</button></form>
                    <form method="post" style="display:inline"><?= admin_csrf_field(); ?><input type="hidden" name="do" value="toggle"><input type="hidden" name="id" value="<?= e($id); ?>"><button class="adm-btn adm-btn-ghost adm-btn-sm"><?= ($item['hidden'] ?? '') === '' ? 'Hide' : 'Show'; ?></button></form>
                    <form method="post" style="display:inline" onsubmit="return confirm('Delete this testimonial?');"><?= admin_csrf_field(); ?><input type="hidden" name="do" value="delete"><input type="hidden" name="id" value="<?= e($id); ?>"><button class="adm-btn adm-btn-danger adm-btn-sm">&times;</button></form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </div>
</div>
<?php admin_layout_end();
