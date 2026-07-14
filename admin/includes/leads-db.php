<?php
/**
 * Read-only reader for this brand's `leads` MySQL table (written by
 * form-submission.php). Columns: id, form_type, name, email, phone, service,
 * message, page_url, ip_address, user_agent, created_at.
 *
 * The admin never writes to the table; read/star flags live in
 * admin/data/leads-state.json keyed by lead id. When the DB is unreachable
 * (typical on localhost) every function degrades gracefully.
 */

require_once __DIR__ . '/helpers.php';

function leads_pdo(): ?PDO
{
    static $pdo = null;
    static $tried = false;
    if ($tried) {
        return $pdo;
    }
    $tried = true;
    if (!defined('DB_HOST') || !defined('DB_NAME')) {
        return null;
    }
    try {
        $pdo = new PDO(
            'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . (defined('DB_CHARSET') ? DB_CHARSET : 'utf8mb4'),
            DB_USER,
            DB_PASS,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_TIMEOUT => 3,
            ]
        );
    } catch (Throwable $e) {
        $pdo = null;
    }
    return $pdo;
}

function leads_available(): bool
{
    return leads_pdo() !== null;
}

function leads_form_types(): array
{
    $pdo = leads_pdo();
    if (!$pdo) {
        return [];
    }
    try {
        $rows = $pdo->query('SELECT DISTINCT form_type FROM leads WHERE form_type IS NOT NULL ORDER BY form_type')->fetchAll();
        return array_values(array_filter(array_map(fn($r) => (string) $r['form_type'], $rows)));
    } catch (Throwable $e) {
        return [];
    }
}

function leads_fetch(string $search = '', string $formType = '', int $limit = 50, int $offset = 0): array
{
    $pdo = leads_pdo();
    if (!$pdo) {
        return ['rows' => [], 'total' => 0];
    }
    $where = [];
    $params = [];
    if ($search !== '') {
        $where[] = '(name LIKE :q OR email LIKE :q OR phone LIKE :q OR message LIKE :q OR service LIKE :q)';
        $params[':q'] = '%' . $search . '%';
    }
    if ($formType !== '') {
        $where[] = 'form_type = :ft';
        $params[':ft'] = $formType;
    }
    $whereSql = $where ? (' WHERE ' . implode(' AND ', $where)) : '';
    try {
        $count = $pdo->prepare('SELECT COUNT(*) AS c FROM leads' . $whereSql);
        $count->execute($params);
        $total = (int) $count->fetch()['c'];

        $stmt = $pdo->prepare(
            'SELECT id, form_type, name, email, phone, service, message, page_url, ip_address, user_agent, created_at'
            . ' FROM leads' . $whereSql . ' ORDER BY id DESC LIMIT :lim OFFSET :off'
        );
        foreach ($params as $k => $v) {
            $stmt->bindValue($k, $v);
        }
        $stmt->bindValue(':lim', max(1, min(500, $limit)), PDO::PARAM_INT);
        $stmt->bindValue(':off', max(0, $offset), PDO::PARAM_INT);
        $stmt->execute();
        return ['rows' => $stmt->fetchAll(), 'total' => $total];
    } catch (Throwable $e) {
        return ['rows' => [], 'total' => 0];
    }
}

function leads_count_since(int $days): int
{
    $pdo = leads_pdo();
    if (!$pdo) {
        return 0;
    }
    try {
        $stmt = $pdo->prepare('SELECT COUNT(*) AS c FROM leads WHERE created_at >= DATE_SUB(NOW(), INTERVAL :d DAY)');
        $stmt->bindValue(':d', $days, PDO::PARAM_INT);
        $stmt->execute();
        return (int) $stmt->fetch()['c'];
    } catch (Throwable $e) {
        return 0;
    }
}

/* --------------------------- read / star state --------------------------- */

function leads_state_file(): string
{
    return ADMIN_DATA_DIR . DIRECTORY_SEPARATOR . 'leads-state.json';
}

function leads_state(): array
{
    return admin_json_read(leads_state_file(), []);
}

function leads_state_set(int $id, string $key, bool $value): void
{
    $state = leads_state();
    $entry = is_array($state[(string) $id] ?? null) ? $state[(string) $id] : [];
    $entry[$key] = $value;
    $state[(string) $id] = $entry;
    admin_json_write(leads_state_file(), $state);
}

function leads_csv(string $search = '', string $formType = ''): string
{
    $data = leads_fetch($search, $formType, 500, 0);
    $fh = fopen('php://temp', 'r+');
    fputcsv($fh, ['id', 'form_type', 'name', 'email', 'phone', 'service', 'message', 'page_url', 'ip_address', 'created_at']);
    foreach ($data['rows'] as $row) {
        fputcsv($fh, [
            $row['id'], $row['form_type'], $row['name'], $row['email'], $row['phone'],
            $row['service'], $row['message'], $row['page_url'], $row['ip_address'], $row['created_at'],
        ]);
    }
    rewind($fh);
    $csv = (string) stream_get_contents($fh);
    fclose($fh);
    return $csv;
}
