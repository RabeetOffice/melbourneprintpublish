<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/post-store.php';
require_once __DIR__ . '/includes/leads-db.php';

$user = admin_require_module('dashboard');

$posts = mpp_post_index();
$published = count(array_filter($posts, fn($p) => $p['status'] === 'published'));
$drafts = count(array_filter($posts, fn($p) => $p['status'] === 'draft'));

$portfolioItems = mpp_portfolio_read();
$portfolioVisible = count(array_filter($portfolioItems, fn($i) => ($i['hidden'] ?? '') === ''));
$testimonialItems = mpp_testimonials_read();
$testimonialVisible = count(array_filter($testimonialItems, fn($i) => ($i['hidden'] ?? '') === ''));

$dbUp = leads_available();
$leads7 = $dbUp ? leads_count_since(7) : null;
$leads30 = $dbUp ? leads_count_since(30) : null;
$freshLeads = $dbUp ? leads_fetch('', '', 6, 0)['rows'] : [];

usort($posts, fn($a, $b) => strcmp($b['updated'] ?: '0', $a['updated'] ?: '0'));
$latestPosts = array_slice($posts, 0, 6);

admin_layout_start($user, 'dashboard', 'Dashboard');
?>
<div class="adm-page-head">
    <div>
        <div class="adm-eyebrow">Overview</div>
        <h1>Dash<span>board</span></h1>
    </div>
    <a class="adm-btn" href="post-edit.php">+ New Post</a>
</div>

<div class="adm-grid adm-grid-stats" style="margin-bottom:22px">
    <div class="adm-stat"><div class="num"><?= (int) $published; ?></div><div class="lbl">Published posts</div></div>
    <div class="adm-stat"><div class="num"><?= (int) $drafts; ?></div><div class="lbl">Drafts</div></div>
    <div class="adm-stat"><div class="num"><?= $dbUp ? (int) $leads7 : '&ndash;'; ?></div><div class="lbl">Leads &middot; 7 days</div></div>
    <div class="adm-stat"><div class="num"><?= $dbUp ? (int) $leads30 : '&ndash;'; ?></div><div class="lbl">Leads &middot; 30 days</div></div>
    <div class="adm-stat"><div class="num"><?= (int) $portfolioVisible; ?><small style="font-size:14px;color:var(--adm-muted)">/<?= count($portfolioItems); ?></small></div><div class="lbl">Portfolio live / total</div></div>
    <div class="adm-stat"><div class="num"><?= (int) $testimonialVisible; ?><small style="font-size:14px;color:var(--adm-muted)">/<?= count($testimonialItems); ?></small></div><div class="lbl">Testimonials live / total</div></div>
</div>

<?php if (!$dbUp): ?>
<div class="adm-alert adm-alert-info">The leads database is not reachable from this environment (normal on localhost) &mdash; lead stats will appear on the live server.</div>
<?php endif; ?>

<div class="adm-editor-grid">
    <div class="adm-card">
        <h2>Latest <span>posts</span></h2>
        <div class="adm-tablewrap">
        <table class="adm-table">
            <thead><tr><th>Title</th><th>Date</th><th>Status</th><th></th></tr></thead>
            <tbody>
            <?php foreach ($latestPosts as $post): ?>
                <tr>
                    <td><strong><?= e($post['title']); ?></strong></td>
                    <td><?= e($post['date']); ?></td>
                    <td><span class="adm-chip <?= $post['status'] === 'published' ? 'adm-chip-green' : 'adm-chip-amber'; ?>"><?= e($post['status']); ?></span></td>
                    <td><a class="adm-btn adm-btn-ghost adm-btn-sm" href="post-edit.php?slug=<?= e(rawurlencode($post['slug'])); ?>">Edit</a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        </div>
    </div>

    <div class="adm-card">
        <h2>Fresh <span>leads</span></h2>
        <?php if (!$dbUp): ?>
            <p class="adm-muted">Unavailable while the database is offline.</p>
        <?php elseif (!$freshLeads): ?>
            <p class="adm-muted">No leads yet.</p>
        <?php else: ?>
            <?php foreach ($freshLeads as $lead): ?>
            <div style="border-bottom:1px solid var(--adm-rule);padding:9px 0">
                <strong><?= e((string) $lead['name']); ?></strong>
                <span class="adm-chip adm-chip-accent"><?= e((string) $lead['form_type']); ?></span><br>
                <small class="adm-muted"><?= e((string) $lead['email']); ?> &middot; <?= e((string) $lead['created_at']); ?></small>
            </div>
            <?php endforeach; ?>
            <p style="margin-top:12px"><a href="submissions.php">Open the inbox &rarr;</a></p>
        <?php endif; ?>
    </div>
</div>
<?php admin_layout_end();
