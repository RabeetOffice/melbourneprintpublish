<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/authors-store.php';

$user = admin_require_module('authors');

$msg = '';
$err = '';

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    admin_require_csrf();
    $do = (string) ($_POST['do'] ?? '');
    $authors = mpp_authors_read();
    $clean = fn(string $v) => trim(strip_tags($v));

    if ($do === 'save') {
        $id = $clean((string) ($_POST['id'] ?? ''));
        $name = $clean((string) ($_POST['name'] ?? ''));
        $bio = trim(preg_replace('~\s+~', ' ', strip_tags((string) ($_POST['bio'] ?? ''))));
        if ($name === '') {
            $err = 'Author name is required.';
        } else {
            $entry = ['id' => $id, 'name' => $name, 'bio' => $bio];
            if ($id === '') {
                $entry['id'] = mpp_authors_next_id($authors);
                $authors[] = $entry;
            } else {
                $found = false;
                foreach ($authors as $i => $a) {
                    if ((string) ($a['id'] ?? '') === $id) {
                        $authors[$i] = $entry;
                        $found = true;
                        break;
                    }
                }
                if (!$found) {
                    $err = 'Author not found.';
                }
            }
            if ($err === '') {
                $msg = mpp_authors_write($authors) ? 'Author saved.' : '';
                $err = $msg === '' ? 'Could not write the authors file.' : '';
            }
        }
    } elseif ($do === 'delete') {
        $id = (string) ($_POST['id'] ?? '');
        $authors = array_values(array_filter($authors, fn($a) => (string) ($a['id'] ?? '') !== $id));
        $msg = mpp_authors_write($authors) ? 'Author deleted. Existing posts keep their embedded author box unchanged.' : '';
        $err = $msg === '' ? 'Could not write the authors file.' : '';
    }

    // Post/Redirect/Get: a refresh after this can never re-run the POST.
    admin_prg_finish('authors.php', $msg, $err);
}

$flash = admin_flash_take();
if ($flash['type'] === 'error') { $err = $flash['msg']; } else { $msg = $flash['msg']; }

$authors = mpp_authors_read();
$editId = (string) ($_GET['edit'] ?? '');
$editing = null;
foreach ($authors as $a) {
    if ($editId !== '' && (string) ($a['id'] ?? '') === $editId) {
        $editing = $a;
        break;
    }
}

admin_layout_start($user, 'authors', 'Authors');
?>
<div class="adm-page-head">
    <div>
        <div class="adm-eyebrow">Bylines</div>
        <h1>Auth<span>ors</span></h1>
    </div>
    <a class="adm-btn adm-btn-sm" href="authors.php#form">+ Add author</a>
</div>

<?php if ($msg): ?><div class="adm-alert adm-alert-ok"><?= e($msg); ?></div><?php endif; ?>
<?php if ($err): ?><div class="adm-alert adm-alert-error"><?= e($err); ?></div><?php endif; ?>

<div class="adm-card" id="form">
    <h2><?= $editing ? 'Edit <span>author</span>' : 'Add <span>author</span>'; ?></h2>
    <p class="adm-help" style="margin-bottom:12px">Authors here appear in the blog editor's author picker. Choosing one fills the post's "About the Author" box.</p>
    <form method="post">
        <?= admin_csrf_field(); ?>
        <input type="hidden" name="do" value="save">
        <input type="hidden" name="id" value="<?= e((string) ($editing['id'] ?? '')); ?>">
        <label class="adm-label">Name</label>
        <input class="adm-input" type="text" name="name" required value="<?= e((string) ($editing['name'] ?? '')); ?>">
        <label class="adm-label">Bio</label>
        <textarea class="adm-textarea" name="bio" rows="4"><?= e((string) ($editing['bio'] ?? '')); ?></textarea>
        <p style="margin-top:16px">
            <button class="adm-btn" type="submit"><?= $editing ? 'Save author' : 'Add author'; ?></button>
            <?php if ($editing): ?><a class="adm-btn adm-btn-ghost" href="authors.php">Cancel</a><?php endif; ?>
        </p>
    </form>
</div>

<div class="adm-card">
    <h2>All <span>authors</span> <small class="adm-muted" style="font-weight:400">(<?= count($authors); ?>)</small></h2>
    <div class="adm-tablewrap">
    <table class="adm-table">
        <thead><tr><th>Name</th><th>Bio</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($authors as $a): $id = (string) ($a['id'] ?? ''); ?>
            <tr>
                <td><strong><?= e((string) $a['name']); ?></strong></td>
                <td><small class="adm-muted"><?= e(mb_strimwidth((string) ($a['bio'] ?? ''), 0, 110, '…')); ?></small></td>
                <td style="white-space:nowrap">
                    <a class="adm-btn adm-btn-ghost adm-btn-sm" href="?edit=<?= e(rawurlencode($id)); ?>#form">Edit</a>
                    <form method="post" style="display:inline" onsubmit="return confirm('Delete this author? Existing posts are not affected.');"><?= admin_csrf_field(); ?><input type="hidden" name="do" value="delete"><input type="hidden" name="id" value="<?= e($id); ?>"><button class="adm-btn adm-btn-danger adm-btn-sm">&times;</button></form>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if (!$authors): ?><tr><td colspan="3" class="adm-muted">No authors yet.</td></tr><?php endif; ?>
        </tbody>
    </table>
    </div>
</div>
<?php admin_layout_end();
