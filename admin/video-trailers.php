<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/content-store.php';

$user = admin_require_module('videos');

$msg = '';
$err = '';

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    admin_require_csrf();
    $do = (string) ($_POST['do'] ?? '');
    $items = mpp_videos_read();

    $clean = fn(string $v) => trim(strip_tags(ukph_strip_php($v)));

    if ($do === 'save') {
        $id = $clean((string) ($_POST['id'] ?? ''));
        // The link may be pasted as any YouTube form (watch, youtu.be, shorts,
        // embed, or a full <iframe>). ukph_strip_php would mangle an <iframe>
        // paste, so parse the raw value to a video id, then store the canonical
        // watch URL. mpp_youtube_id() ignores anything that is not a real id.
        $rawLink = trim((string) ($_POST['link'] ?? ''));
        $videoId = mpp_youtube_id($rawLink);
        $entry = [
            'id'          => $id,
            'hidden'      => ($_POST['hidden'] ?? '') === '1' ? 'hidden' : '',
            'name'        => $clean((string) ($_POST['name'] ?? '')),
            'description' => $clean((string) ($_POST['description'] ?? '')),
            'link'        => $videoId !== '' ? 'https://www.youtube.com/watch?v=' . $videoId : '',
        ];
        if ($entry['name'] === '') {
            $err = 'The video title is required.';
        } elseif ($rawLink === '') {
            $err = 'A YouTube link is required.';
        } elseif ($videoId === '') {
            $err = 'That does not look like a YouTube link — could not detect a video ID. Paste a link like https://www.youtube.com/watch?v=… , a youtu.be/… , or a /shorts/… URL.';
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
            $msg = mpp_videos_write($items) ? 'Video trailer saved and the live data file was regenerated.' : '';
            $err = $msg === '' ? 'Could not write the video trailers data file (lint failed or IO error).' : '';
        }
    } elseif ($do === 'delete') {
        $id = (string) ($_POST['id'] ?? '');
        $items = array_values(array_filter($items, fn($i) => (string) ($i['id'] ?? '') !== $id));
        $msg = mpp_videos_write($items, true) ? 'Item deleted.' : '';
        $err = $msg === '' ? 'Could not write the video trailers data file.' : '';
    } elseif ($do === 'toggle') {
        $id = (string) ($_POST['id'] ?? '');
        foreach ($items as $i => $existing) {
            if ((string) ($existing['id'] ?? '') === $id) {
                $items[$i]['hidden'] = ($existing['hidden'] ?? '') === '' ? 'hidden' : '';
            }
        }
        $msg = mpp_videos_write($items) ? 'Visibility updated.' : '';
        $err = $msg === '' ? 'Could not write the video trailers data file.' : '';
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
        $msg = mpp_videos_write($items) ? 'Order updated.' : '';
        $err = $msg === '' ? 'Could not write the video trailers data file.' : '';
    }

    // Post/Redirect/Get: a refresh after this can never re-run the POST.
    admin_prg_finish('video-trailers.php', $msg, $err);
}

$flash = admin_flash_take();
if ($flash['type'] === 'error') { $err = $flash['msg']; } else { $msg = $flash['msg']; }

$items = mpp_videos_read();
$editId = (string) ($_GET['edit'] ?? '');
$editing = null;
foreach ($items as $item) {
    if ($editId !== '' && (string) ($item['id'] ?? '') === $editId) {
        $editing = $item;
        break;
    }
}

admin_layout_start($user, 'videos', 'Video Trailers');
?>
<div class="adm-page-head">
    <div>
        <div class="adm-eyebrow">Showcase</div>
        <h1>Video <span>Trailers</span></h1>
    </div>
    <a class="adm-btn adm-btn-sm" href="video-trailers.php#form">+ Add video</a>
</div>

<?php if ($msg): ?><div class="adm-alert adm-alert-ok"><?= e($msg); ?></div><?php endif; ?>
<?php if ($err): ?><div class="adm-alert adm-alert-error"><?= e($err); ?></div><?php endif; ?>

<div class="adm-card" id="form">
    <h2><?= $editing ? 'Edit <span>video</span>' : 'Add <span>video</span>'; ?></h2>
    <form method="post">
        <?= admin_csrf_field(); ?>
        <input type="hidden" name="do" value="save">
        <input type="hidden" name="id" value="<?= e((string) ($editing['id'] ?? '')); ?>">
        <label class="adm-label">Video title</label>
        <input class="adm-input" type="text" name="name" required value="<?= e((string) ($editing['name'] ?? '')); ?>">
        <label class="adm-label">YouTube link</label>
        <input class="adm-input" type="text" name="link" required placeholder="https://www.youtube.com/watch?v=&hellip;" value="<?= e((string) ($editing['link'] ?? '')); ?>">
        <p class="adm-help">Paste any YouTube link &mdash; a normal <code>watch?v=</code> URL, a <code>youtu.be/</code> short link, a <code>/shorts/</code> URL, an <code>/embed/</code> URL, or even the full <code>&lt;iframe&gt;</code> embed code. It is converted to a player automatically.</p>
        <label class="adm-label">Short description (optional)</label>
        <textarea class="adm-input" name="description" rows="2" placeholder="One or two lines shown on the card under the title&hellip;"><?= e((string) ($editing['description'] ?? '')); ?></textarea>
        <label class="adm-label"><input type="checkbox" name="hidden" value="1" <?= ($editing['hidden'] ?? '') !== '' ? 'checked' : ''; ?>> Hidden (kept in the data file, not shown on the site)</label>
        <p style="margin-top:16px">
            <button class="adm-btn" type="submit"><?= $editing ? 'Save changes' : 'Add video'; ?></button>
            <?php if ($editing): ?><a class="adm-btn adm-btn-ghost" href="video-trailers.php">Cancel</a><?php endif; ?>
        </p>
    </form>
</div>

<div class="adm-card">
    <h2>All <span>videos</span> <small class="adm-muted" style="font-weight:400">(<?= count($items); ?>)</small></h2>
    <div class="adm-tablewrap">
    <table class="adm-table">
        <thead><tr><th></th><th>Title</th><th>Link</th><th>Status</th><th style="width:230px"></th></tr></thead>
        <tbody>
        <?php foreach ($items as $item): $id = (string) ($item['id'] ?? ''); $thumb = mpp_youtube_thumb((string) ($item['link'] ?? '')); ?>
            <tr>
                <td><?php if ($thumb !== ''): ?><img class="thumb" loading="lazy" src="<?= e($thumb); ?>" alt="" style="width:96px;height:54px;object-fit:cover;border-radius:6px"><?php endif; ?></td>
                <td><strong><?= e((string) $item['name']); ?></strong></td>
                <td><small class="adm-muted"><?= e(mb_strimwidth((string) $item['link'], 0, 44, '…')); ?></small></td>
                <td><span class="adm-chip <?= ($item['hidden'] ?? '') === '' ? 'adm-chip-green' : 'adm-chip-grey'; ?>"><?= ($item['hidden'] ?? '') === '' ? 'Visible' : 'Hidden'; ?></span></td>
                <td style="white-space:nowrap">
                    <a class="adm-btn adm-btn-ghost adm-btn-sm" href="?edit=<?= e(rawurlencode($id)); ?>#form">Edit</a>
                    <form method="post" style="display:inline"><?= admin_csrf_field(); ?><input type="hidden" name="do" value="move"><input type="hidden" name="id" value="<?= e($id); ?>"><input type="hidden" name="dir" value="up"><button class="adm-btn adm-btn-ghost adm-btn-sm" title="Move up">&uarr;</button></form>
                    <form method="post" style="display:inline"><?= admin_csrf_field(); ?><input type="hidden" name="do" value="move"><input type="hidden" name="id" value="<?= e($id); ?>"><input type="hidden" name="dir" value="down"><button class="adm-btn adm-btn-ghost adm-btn-sm" title="Move down">&darr;</button></form>
                    <form method="post" style="display:inline"><?= admin_csrf_field(); ?><input type="hidden" name="do" value="toggle"><input type="hidden" name="id" value="<?= e($id); ?>"><button class="adm-btn adm-btn-ghost adm-btn-sm"><?= ($item['hidden'] ?? '') === '' ? 'Hide' : 'Show'; ?></button></form>
                    <form method="post" style="display:inline" onsubmit="return confirm('Delete this video?');"><?= admin_csrf_field(); ?><input type="hidden" name="do" value="delete"><input type="hidden" name="id" value="<?= e($id); ?>"><button class="adm-btn adm-btn-danger adm-btn-sm">&times;</button></form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </div>
</div>
<?php admin_layout_end();
