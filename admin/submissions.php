<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/leads-db.php';

$user = admin_require_module('submissions');

$q = trim((string) ($_GET['q'] ?? ''));
$formType = trim((string) ($_GET['form_type'] ?? ''));
$page = max(1, (int) ($_GET['page'] ?? 1));
$perPage = 25;

// state toggles (read / star) + CSV export
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    admin_require_csrf();
    $id = (int) ($_POST['id'] ?? 0);
    $do = (string) ($_POST['do'] ?? '');
    if ($id > 0 && in_array($do, ['read', 'unread', 'star', 'unstar'], true)) {
        leads_state_set($id, $do === 'read' || $do === 'unread' ? 'read' : 'star', $do === 'read' || $do === 'star');
    }
    header('Content-Type: application/json');
    echo json_encode(['ok' => true]);
    exit;
}
if (($_GET['export'] ?? '') === 'csv') {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="leads-' . date('Ymd-His') . '.csv"');
    echo leads_csv($q, $formType);
    exit;
}

$dbUp = leads_available();
$result = $dbUp ? leads_fetch($q, $formType, $perPage, ($page - 1) * $perPage) : ['rows' => [], 'total' => 0];
$state = leads_state();
$types = $dbUp ? leads_form_types() : [];
$pages = max(1, (int) ceil($result['total'] / $perPage));

admin_layout_start($user, 'submissions', 'Submissions');
?>
<div class="adm-page-head">
    <div>
        <div class="adm-eyebrow">Inbox</div>
        <h1>Form <span>Submissions</span></h1>
    </div>
    <?php if ($dbUp): ?>
    <a class="adm-btn adm-btn-dark adm-btn-sm" href="?export=csv&amp;q=<?= e(rawurlencode($q)); ?>&amp;form_type=<?= e(rawurlencode($formType)); ?>">Export CSV</a>
    <?php endif; ?>
</div>

<?php if (!$dbUp): ?>
<div class="adm-alert adm-alert-info">
    The leads database (<code><?= e(defined('DB_NAME') ? DB_NAME : 'leads'); ?></code>) is not reachable from this
    environment. This is expected on localhost &mdash; on the live server the inbox reads the same table your
    forms already write to.
</div>
<?php else: ?>
<div class="adm-card">
    <form method="get" class="adm-row" style="align-items:flex-end">
        <div>
            <label class="adm-label" for="q">Search</label>
            <input class="adm-input" type="text" id="q" name="q" value="<?= e($q); ?>" placeholder="Name, email, phone, message&hellip;">
        </div>
        <div style="max-width:190px">
            <label class="adm-label" for="form_type">Form</label>
            <select class="adm-select" id="form_type" name="form_type">
                <option value="">All forms</option>
                <?php foreach ($types as $type): ?>
                <option value="<?= e($type); ?>" <?= $type === $formType ? 'selected' : ''; ?>><?= e(ucfirst($type)); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div style="max-width:120px">
            <button class="adm-btn adm-btn-dark adm-btn-sm" type="submit" style="margin-bottom:2px">Filter</button>
        </div>
    </form>
</div>

<div class="adm-card">
    <p class="adm-muted" style="margin-top:0"><?= (int) $result['total']; ?> lead(s)</p>
    <div class="adm-tablewrap">
    <table class="adm-table">
        <thead><tr><th></th><th>Name</th><th>Contact</th><th>Form</th><th>Service</th><th>Received</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($result['rows'] as $lead):
            $id = (int) $lead['id'];
            $ls = $state[(string) $id] ?? [];
            $isRead = !empty($ls['read']);
            $isStar = !empty($ls['star']);
        ?>
            <tr style="<?= $isRead ? '' : 'font-weight:600'; ?>">
                <td>
                    <button class="adm-tool star-btn" data-id="<?= $id; ?>" data-on="<?= $isStar ? '1' : '0'; ?>" title="Star" style="color:<?= $isStar ? '#ff7f3e' : '#bbb'; ?>">&#9733;</button>
                </td>
                <td><?= e((string) $lead['name']); ?></td>
                <td><?= e((string) $lead['email']); ?><br><small class="adm-muted"><?= e((string) $lead['phone']); ?></small></td>
                <td><span class="adm-chip adm-chip-accent"><?= e((string) $lead['form_type']); ?></span></td>
                <td><?= e((string) ($lead['service'] ?? '')); ?></td>
                <td><small><?= e((string) $lead['created_at']); ?></small></td>
                <td><button class="adm-btn adm-btn-ghost adm-btn-sm detail-btn" data-id="<?= $id; ?>">Detail</button></td>
            </tr>
            <tr class="lead-detail" id="detail-<?= $id; ?>" style="display:none">
                <td colspan="7" style="background:#fffaf5">
                    <strong>Message</strong>
                    <p style="white-space:pre-wrap;margin:6px 0 10px"><?= e((string) ($lead['message'] ?? '')); ?></p>
                    <small class="adm-muted">
                        Page: <?= e((string) ($lead['page_url'] ?? '')); ?> &middot;
                        IP: <?= e((string) ($lead['ip_address'] ?? '')); ?> &middot;
                        UA: <?= e(mb_strimwidth((string) ($lead['user_agent'] ?? ''), 0, 110, '…')); ?>
                    </small>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if (!$result['rows']): ?>
            <tr><td colspan="7" class="adm-muted">No leads match.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
    </div>
    <?php if ($pages > 1): ?>
    <p style="margin-top:14px">
        <?php for ($p = 1; $p <= $pages; $p++): ?>
            <?php if ($p === $page): ?><strong style="padding:0 6px"><?= $p; ?></strong>
            <?php else: ?><a style="padding:0 6px" href="?q=<?= e(rawurlencode($q)); ?>&amp;form_type=<?= e(rawurlencode($formType)); ?>&amp;page=<?= $p; ?>"><?= $p; ?></a><?php endif; ?>
        <?php endfor; ?>
    </p>
    <?php endif; ?>
</div>

<script>
document.addEventListener('click', function (ev) {
    var btn = ev.target.closest('.detail-btn');
    if (btn) {
        var row = document.getElementById('detail-' + btn.dataset.id);
        var show = row.style.display === 'none';
        row.style.display = show ? '' : 'none';
        if (show) { leadState(btn.dataset.id, 'read'); btn.closest('tr').style.fontWeight = '400'; }
        return;
    }
    var star = ev.target.closest('.star-btn');
    if (star) {
        var on = star.dataset.on === '1';
        star.dataset.on = on ? '0' : '1';
        star.style.color = on ? '#bbb' : '#ff7f3e';
        leadState(star.dataset.id, on ? 'unstar' : 'star');
    }
});
function leadState(id, action) {
    var fd = new FormData();
    fd.append('id', id);
    fd.append('do', action);
    fd.append('csrf', window.ADMIN_CSRF);
    fetch('submissions.php', {method: 'POST', body: fd, credentials: 'same-origin'});
}
window.ADMIN_CSRF = <?= json_encode(admin_csrf_token()); ?>;
</script>
<?php endif; ?>
<?php admin_layout_end();
