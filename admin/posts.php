<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/post-store.php';

$user = admin_require_module('posts');

$q = trim((string) ($_GET['q'] ?? ''));
$statusFilter = (string) ($_GET['status'] ?? '');

$posts = mpp_post_index();
usort($posts, function ($a, $b) {
    $da = strtotime(str_replace('-', '-', $a['updated'] ?: '1970-01-01')) ?: 0;
    $db = strtotime($b['updated'] ?: '1970-01-01') ?: 0;
    if ($da !== $db) {
        return $db <=> $da;
    }
    return strcmp($b['date'], $a['date']);
});

$filtered = array_filter($posts, function ($post) use ($q, $statusFilter) {
    if ($statusFilter !== '' && $post['status'] !== $statusFilter) {
        return false;
    }
    if ($q !== '' && stripos($post['title'], $q) === false && stripos($post['slug'], $q) === false) {
        return false;
    }
    return true;
});

admin_layout_start($user, 'posts', 'Blog Posts');
?>
<div class="adm-page-head">
    <div>
        <div class="adm-eyebrow">Content</div>
        <h1>Blog <span>Posts</span></h1>
    </div>
    <a class="adm-btn" href="post-edit.php">+ New Post</a>
</div>

<div class="adm-card">
    <form method="get" class="adm-row" style="align-items:flex-end">
        <div>
            <label class="adm-label" for="q">Search</label>
            <input class="adm-input" type="text" id="q" name="q" value="<?= e($q); ?>" placeholder="Title or slug&hellip;">
        </div>
        <div style="max-width:200px">
            <label class="adm-label" for="status">Status</label>
            <select class="adm-select" id="status" name="status">
                <option value="">All</option>
                <option value="published" <?= $statusFilter === 'published' ? 'selected' : ''; ?>>Published</option>
                <option value="draft" <?= $statusFilter === 'draft' ? 'selected' : ''; ?>>Draft</option>
            </select>
        </div>
        <div style="max-width:130px">
            <button class="adm-btn adm-btn-dark adm-btn-sm" type="submit" style="margin-bottom:2px">Filter</button>
        </div>
    </form>
</div>

<div class="adm-card">
    <div class="adm-tablewrap">
    <table class="adm-table">
        <thead>
            <tr>
                <th></th>
                <th>Title</th>
                <th>Slug</th>
                <th>Date</th>
                <th>Status</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        <?php if (!$filtered): ?>
            <tr><td colspan="6" class="adm-muted">No posts match.</td></tr>
        <?php endif; ?>
        <?php foreach ($filtered as $post): ?>
            <tr>
                <td><?php if ($post['image'] !== ''): ?><img class="thumb" src="<?= e(brand_asset($post['image'])); ?>" alt="" loading="lazy"><?php endif; ?></td>
                <td><strong><?= e($post['title']); ?></strong>
                    <?php if (!$post['managed']): ?><br><small class="adm-muted">legacy &middot; imported on first edit</small><?php endif; ?>
                </td>
                <td><code><?= e($post['slug']); ?></code></td>
                <td><?= e($post['date']); ?></td>
                <td>
                    <?php if ($post['status'] === 'published'): ?>
                        <span class="adm-chip adm-chip-green">Published</span>
                    <?php elseif ($post['status'] === 'draft'): ?>
                        <span class="adm-chip adm-chip-amber">Draft</span>
                    <?php else: ?>
                        <span class="adm-chip adm-chip-red"><?= e($post['status']); ?></span>
                    <?php endif; ?>
                </td>
                <td style="white-space:nowrap">
                    <a class="adm-btn adm-btn-ghost adm-btn-sm" href="post-edit.php?slug=<?= e(rawurlencode($post['slug'])); ?>">Edit</a>
                    <?php if ($post['status'] === 'published'): ?>
                        <a class="adm-btn adm-btn-ghost adm-btn-sm" href="<?= e(brand_asset('/blogs/' . $post['slug'] . '.php')); ?>" target="_blank" rel="noopener">View</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </div>
</div>
<?php admin_layout_end();
