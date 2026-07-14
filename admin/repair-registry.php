<?php
/**
 * ONE-OFF REPAIR TOOL — blog registry / sitemap cleanup.
 *
 * Fixes the fallout of the old slug-rename bug: duplicate registry rows and
 * dead cards that point at a /blogs/<slug>/ with no backing blogs/<slug>.php
 * file (e.g. an abandoned autosave slug). It is safe and idempotent:
 *   - a row is REMOVED only when its link has no live post file (dead card), or
 *     it is a second+ row for a slug already kept (duplicate);
 *   - every legitimate published post keeps its file, so it is always kept.
 * Dry-run by default; nothing changes until you press "Apply cleanup".
 *
 * Delete this file once the registry is clean — it is not part of the CMS.
 */

require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/post-store.php';

$user = admin_require_module('posts');

/** Split the current registry into kept rows + removable rows (with reason). */
function repair_scan(): array
{
    $kept = [];
    $remove = [];
    $seen = [];
    foreach (mpp_registry_read() as $row) {
        $link = (string) ($row['link'] ?? '');
        $slug = mpp_slug_from_link($link);
        $name = (string) ($row['name'] ?? $slug);
        if ($slug === '') {
            $remove[] = ['row' => $row, 'slug' => '', 'name' => $name, 'reason' => 'Malformed link (no /blogs/<slug>/).'];
            continue;
        }
        if (!is_file(mpp_post_php_path($slug))) {
            $remove[] = ['row' => $row, 'slug' => $slug, 'name' => $name, 'reason' => 'Dead card — no blogs/' . $slug . '.php on disk.'];
            continue;
        }
        if (isset($seen[$slug])) {
            $remove[] = ['row' => $row, 'slug' => $slug, 'name' => $name, 'reason' => 'Duplicate of a row already kept.'];
            continue;
        }
        $seen[$slug] = true;
        $kept[] = $row;
    }
    return [$kept, $remove];
}

$applied = false;
$error = '';
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    if (!admin_verify_csrf()) {
        $error = 'Session expired. Reload the page and try again.';
    } else {
        [$kept, $remove] = repair_scan();
        if (!$remove) {
            $applied = true; // nothing to do
        } else {
            $ok = mpp_data_file_write(ADMIN_SITE_ROOT . '/data/blogs_post.php', 'blogs', $kept, mpp_registry_fields());
            if (!$ok) {
                $error = 'Could not write the cleaned registry. Nothing was changed.';
            } else {
                // Drop sitemap URLs only for genuinely dead slugs (never for a
                // live duplicate whose single correct URL must stay).
                $liveSlugs = [];
                foreach ($kept as $r) {
                    $s = mpp_slug_from_link((string) ($r['link'] ?? ''));
                    if ($s !== '') {
                        $liveSlugs[$s] = true;
                    }
                }
                foreach ($remove as $r) {
                    if ($r['slug'] !== '' && empty($liveSlugs[$r['slug']])) {
                        mpp_sitemap_remove($r['slug']);
                    }
                }
                $applied = true;
            }
        }
    }
}

[$kept, $remove] = repair_scan();

admin_layout_start($user, 'posts', 'Registry repair');
?>
<div class="adm-page-head">
    <div>
        <div class="adm-eyebrow">Maintenance</div>
        <h1>Registry <span>repair</span></h1>
    </div>
    <a class="adm-btn adm-btn-ghost adm-btn-sm" href="posts.php">&larr; All posts</a>
</div>

<?php if ($error): ?>
<div class="adm-alert adm-alert-danger"><?= e($error); ?></div>
<?php elseif ($applied): ?>
<div class="adm-alert adm-alert-info">Cleanup applied. The blog listing and sitemap now show one card per live post. You can delete <code>admin/repair-registry.php</code> now.</div>
<?php endif; ?>

<div class="adm-card" style="padding:16px 18px">
    <p class="adm-help" style="margin-top:0">
        This removes blog-listing rows that point at a URL with no live post file
        (dead cards) and collapses duplicate rows. Legitimate posts always have a
        file, so they are never touched.
    </p>

    <?php if (!$remove): ?>
        <p><strong>Nothing to clean.</strong> Every registry row maps to a live post file — <?= count($kept); ?> post<?= count($kept) === 1 ? '' : 's'; ?> in total.</p>
    <?php else: ?>
        <p><strong><?= count($remove); ?></strong> row<?= count($remove) === 1 ? '' : 's'; ?> will be removed; <strong><?= count($kept); ?></strong> kept.</p>
        <table class="adm-table" style="width:100%;border-collapse:collapse;margin:12px 0">
            <thead><tr>
                <th style="text-align:left;padding:8px;border-bottom:1px solid #eee">Title</th>
                <th style="text-align:left;padding:8px;border-bottom:1px solid #eee">Link</th>
                <th style="text-align:left;padding:8px;border-bottom:1px solid #eee">Reason</th>
            </tr></thead>
            <tbody>
            <?php foreach ($remove as $r): ?>
                <tr>
                    <td style="padding:8px;border-bottom:1px solid #f3f3f3"><?= e($r['name']); ?></td>
                    <td style="padding:8px;border-bottom:1px solid #f3f3f3"><code><?= e((string) ($r['row']['link'] ?? '')); ?></code></td>
                    <td style="padding:8px;border-bottom:1px solid #f3f3f3"><?= e($r['reason']); ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <form method="post" onsubmit="return confirm('Remove <?= count($remove); ?> registry row(s)? This updates the live blog listing and sitemap.');">
            <?= admin_csrf_field(); ?>
            <button class="adm-btn adm-btn-dark adm-btn-sm" type="submit">Apply cleanup</button>
        </form>
    <?php endif; ?>
</div>

<div class="adm-card" style="padding:16px 18px">
    <h2>Kept <span>posts</span> (<?= count($kept); ?>)</h2>
    <ul class="adm-help" style="margin:0;padding-left:18px">
        <?php foreach ($kept as $r): ?>
            <li><?= e((string) ($r['name'] ?? '')); ?> — <code><?= e((string) ($r['link'] ?? '')); ?></code></li>
        <?php endforeach; ?>
    </ul>
</div>
<?php admin_layout_end();
