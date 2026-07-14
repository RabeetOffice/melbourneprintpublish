<?php
require_once __DIR__ . '/includes/auth.php';

$user = admin_require_module('settings');
$isSuper = admin_is_super($user);

$SETTINGS_FILE = ADMIN_DATA_DIR . DIRECTORY_SEPARATOR . 'site-settings.json';
$OVERRIDES_FILE = ADMIN_DATA_DIR . DIRECTORY_SEPARATOR . 'settings-overrides.json';

$msg = '';
$err = '';

/* ---- nested array helpers ---- */
function sp_set(array &$arr, string $path, $val): void
{
    $segs = explode('.', $path);
    $ref = &$arr;
    foreach ($segs as $i => $seg) {
        if ($i === count($segs) - 1) { $ref[$seg] = $val; }
        else { if (!isset($ref[$seg]) || !is_array($ref[$seg])) { $ref[$seg] = []; } $ref = &$ref[$seg]; }
    }
}
function sp_unset(array &$arr, string $path): void
{
    $segs = explode('.', $path);
    $ref = &$arr;
    foreach ($segs as $i => $seg) {
        if ($i === count($segs) - 1) { unset($ref[$seg]); }
        else { if (!isset($ref[$seg]) || !is_array($ref[$seg])) { return; } $ref = &$ref[$seg]; }
    }
}
function sp_get(array $arr, string $path, $default = null)
{
    foreach (explode('.', $path) as $seg) {
        if (is_array($arr) && array_key_exists($seg, $arr)) { $arr = $arr[$seg]; }
        else { return $default; }
    }
    return $arr;
}

// Fields available to everyone with the settings module.
$textFields = [
    'contact.phone', 'contact.phone_href', 'contact.email', 'contact.abn_company', 'contact.abn',
    'contact.address', 'contact.address_map_url', 'contact.tagline', 'disclaimer',
    'analytics.gtm_id', 'analytics.ga_id', 'analytics.google_site_verification',
    'social.facebook', 'social.instagram', 'social.linkedin', 'social.pinterest', 'social.twitter', 'social.youtube',
];
// Sensitive fields — super-admin only. chat.tawk_src loads a <script src>, so it
// carries the same code-execution risk as the custom-script slots.
$superTextFields = [
    'chat.tawk_src',
    'smtp.host', 'smtp.port', 'smtp.encryption', 'smtp.user', 'smtp.pass', 'smtp.from_email', 'smtp.from_name',
    'recaptcha.site_key', 'recaptcha.secret_key',
    'db.host', 'db.name', 'db.user', 'db.pass',
    'scripts.head', 'scripts.body_open', 'scripts.footer',
    'schema.organization_json',
];
// URL fields where a dangerous scheme must be rejected on save (defence in depth
// on top of the render-time mpp_val_url guard).
$urlFields = [
    'contact.address_map_url', 'chat.tawk_src',
    'social.facebook', 'social.instagram', 'social.linkedin', 'social.pinterest', 'social.twitter', 'social.youtube',
];
$boolFields = ['recaptcha.enable_public_forms', 'schema.organization_enabled']; // super-only

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    admin_require_csrf();

    if (($_POST['form'] ?? '') === 'leads') {
        // Lead recipients live in the existing overrides file (config reads it).
        $overrides = admin_json_read($OVERRIDES_FILE, []);
        $raw = (string) ($_POST['lead_recipients'] ?? '');
        $lines = preg_split('~[\r\n,]+~', $raw) ?: [];
        $emails = [];
        $bad = [];
        foreach ($lines as $line) {
            $line = trim(strip_tags($line));
            if ($line === '') { continue; }
            if (filter_var($line, FILTER_VALIDATE_EMAIL)) { $emails[] = $line; } else { $bad[] = $line; }
        }
        if ($bad) {
            $err = 'Not a valid email: ' . implode(', ', array_map('htmlspecialchars', $bad));
        } else {
            $overrides['lead_recipients'] = $emails;
            $msg = admin_json_write($OVERRIDES_FILE, $overrides) ? 'Lead recipients saved.' : '';
            $err = $msg === '' ? 'Could not write the settings file.' : '';
        }
    } else {
        // Site settings: update only submitted fields; keep unchanged as raw
        // originals (store a value only when it differs from the default).
        $overrides = admin_json_read($SETTINGS_FILE, []);
        $defaults = mpp_settings_defaults();

        $editable = $textFields;
        if ($isSuper) { $editable = array_merge($editable, $superTextFields); }

        foreach ($editable as $path) {
            if (!array_key_exists(str_replace('.', '__', $path), $_POST)) { continue; }
            $val = (string) $_POST[str_replace('.', '__', $path)];
            // Scripts are raw; everything else is plain text.
            if (strpos($path, 'scripts.') !== 0 && strpos($path, 'schema.organization_json') !== 0) {
                $val = trim($val);
            }
            // Reject dangerous URL schemes on href/src fields (XSS defence).
            if (in_array($path, $urlFields, true) && $val !== ''
                && preg_match('~^\s*(?:javascript|data|vbscript)\s*:~i', $val)) {
                $err = 'That URL scheme is not allowed for ' . $path . '.';
                continue;
            }
            $def = (string) sp_get($defaults, $path, '');
            if ($val === '' || $val === $def) {
                sp_unset($overrides, $path);
            } else {
                sp_set($overrides, $path, $val);
            }
        }
        if ($err !== '') {
            // A bad URL was submitted — do not persist anything from this form.
            $overrides = admin_json_read($SETTINGS_FILE, []);
        }
        if ($isSuper) {
            foreach ($boolFields as $path) {
                $on = isset($_POST[str_replace('.', '__', $path)]);
                if ($on) { sp_set($overrides, $path, true); } else { sp_unset($overrides, $path); }
            }
        }
        // Prune now-empty branches so mpp_is_overridden stays accurate.
        $overrides = array_filter($overrides, fn($v) => !(is_array($v) && count($v) === 0));

        $msg = admin_json_write($SETTINGS_FILE, $overrides) ? 'Site settings saved.' : '';
        $err = $msg === '' ? 'Could not write the settings file.' : '';
        if ($err === '') {
            // Reload so config.php re-resolves the constants from the new values
            // and the connection statuses below test what was just saved.
            header('Location: settings.php?saved=1');
            exit;
        }
    }
}
if (isset($_GET['saved'])) {
    $msg = 'Site settings saved.';
}

$eff = function (string $path) { return (string) mpp_setting($path, ''); };
$overridesNow = admin_json_read($SETTINGS_FILE, []);
$boolOn = function (string $path) use ($overridesNow) { return (bool) sp_get($overridesNow, $path, false); };
$leadRecipients = (array) (admin_json_read($OVERRIDES_FILE, [])['lead_recipients'] ?? []);
global $lead_recipients;
if (!$leadRecipients) { $leadRecipients = $lead_recipients; }

function fld(string $path): string { return str_replace('.', '__', $path); }

admin_layout_start($user, 'settings', 'Site Settings');
?>
<div class="adm-page-head">
    <div>
        <div class="adm-eyebrow">Site control</div>
        <h1>Site <span>Settings</span></h1>
    </div>
</div>

<?php if ($msg): ?><div class="adm-alert adm-alert-ok"><?= e($msg); ?></div><?php endif; ?>
<?php if ($err): ?><div class="adm-alert adm-alert-error"><?= e($err); ?></div><?php endif; ?>

<?php if ($isSuper):
    require_once __DIR__ . '/includes/health.php';
    $healthChip = ['ok' => 'adm-chip-green', 'warn' => 'adm-chip-amber', 'fail' => 'adm-chip-red', 'off' => 'adm-chip-grey'];
    $healthWord = ['ok' => 'Connected', 'warn' => 'Attention', 'fail' => 'Failed', 'off' => 'Off'];
?>
<div class="adm-card">
    <h2>Connection <span>status</span></h2>
    <p class="adm-help">Tested live just now with the currently effective values (saved overrides, or the built-in config defaults). <a href="settings.php">Re-check</a></p>
    <div class="adm-tablewrap">
    <table class="adm-table">
        <tbody>
        <?php foreach (admin_health_all() as $name => $h): ?>
            <tr>
                <td style="white-space:nowrap"><strong><?= e($name); ?></strong></td>
                <td style="white-space:nowrap"><span class="adm-chip <?= $healthChip[$h['state']] ?? 'adm-chip-grey'; ?>"><?= e($h['label']); ?></span></td>
                <td><small class="adm-muted"><?= e($h['detail']); ?></small></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </div>
</div>
<?php endif; ?>

<form method="post">
    <?= admin_csrf_field(); ?>
    <input type="hidden" name="form" value="site">

    <div class="adm-card">
        <h2>Contact &amp; <span>footer</span></h2>
        <div class="adm-row">
            <div><label class="adm-label">Phone (shown)</label><input class="adm-input" name="<?= fld('contact.phone'); ?>" value="<?= e($eff('contact.phone')); ?>"></div>
            <div><label class="adm-label">Phone (tel: href)</label><input class="adm-input" name="<?= fld('contact.phone_href'); ?>" value="<?= e($eff('contact.phone_href')); ?>"></div>
        </div>
        <div class="adm-row">
            <div><label class="adm-label">Email</label><input class="adm-input" name="<?= fld('contact.email'); ?>" value="<?= e($eff('contact.email')); ?>"></div>
            <div><label class="adm-label">Company (ABN holder)</label><input class="adm-input" name="<?= fld('contact.abn_company'); ?>" value="<?= e($eff('contact.abn_company')); ?>"></div>
        </div>
        <div class="adm-row">
            <div><label class="adm-label">ABN</label><input class="adm-input" name="<?= fld('contact.abn'); ?>" value="<?= e($eff('contact.abn')); ?>"></div>
            <div><label class="adm-label">Address map URL</label><input class="adm-input" name="<?= fld('contact.address_map_url'); ?>" value="<?= e($eff('contact.address_map_url')); ?>"></div>
        </div>
        <label class="adm-label">Office address</label>
        <input class="adm-input" name="<?= fld('contact.address'); ?>" value="<?= e($eff('contact.address')); ?>">
        <label class="adm-label">Footer tagline</label>
        <textarea class="adm-textarea" name="<?= fld('contact.tagline'); ?>" rows="2"><?= e($eff('contact.tagline')); ?></textarea>
        <label class="adm-label">Top disclaimer bar</label>
        <textarea class="adm-textarea" name="<?= fld('disclaimer'); ?>" rows="3"><?= e($eff('disclaimer')); ?></textarea>
    </div>

    <div class="adm-card">
        <h2>Analytics <span>IDs</span></h2>
        <div class="adm-row">
            <div><label class="adm-label">Google Tag Manager ID</label><input class="adm-input" name="<?= fld('analytics.gtm_id'); ?>" value="<?= e($eff('analytics.gtm_id')); ?>"></div>
            <div><label class="adm-label">GA4 Measurement ID</label><input class="adm-input" name="<?= fld('analytics.ga_id'); ?>" value="<?= e($eff('analytics.ga_id')); ?>"></div>
        </div>
        <label class="adm-label">google-site-verification</label>
        <input class="adm-input" name="<?= fld('analytics.google_site_verification'); ?>" value="<?= e($eff('analytics.google_site_verification')); ?>">
        <p class="adm-help">These apply site-wide, including pages you haven't edited in the SEO manager.</p>
    </div>

    <div class="adm-card">
        <h2>Social <span>links</span></h2>
        <div class="adm-row">
            <div><label class="adm-label">Facebook</label><input class="adm-input" name="<?= fld('social.facebook'); ?>" value="<?= e($eff('social.facebook')); ?>"></div>
            <div><label class="adm-label">Instagram</label><input class="adm-input" name="<?= fld('social.instagram'); ?>" value="<?= e($eff('social.instagram')); ?>"></div>
        </div>
        <div class="adm-row">
            <div><label class="adm-label">LinkedIn</label><input class="adm-input" name="<?= fld('social.linkedin'); ?>" value="<?= e($eff('social.linkedin')); ?>"></div>
            <div><label class="adm-label">Pinterest</label><input class="adm-input" name="<?= fld('social.pinterest'); ?>" value="<?= e($eff('social.pinterest')); ?>"></div>
        </div>
        <div class="adm-row">
            <div><label class="adm-label">X / Twitter</label><input class="adm-input" name="<?= fld('social.twitter'); ?>" value="<?= e($eff('social.twitter')); ?>"></div>
            <div><label class="adm-label">YouTube</label><input class="adm-input" name="<?= fld('social.youtube'); ?>" value="<?= e($eff('social.youtube')); ?>"></div>
        </div>
    </div>

    <?php if ($isSuper): ?>
    <div class="adm-card">
        <h2>SMTP <span>email</span></h2>
        <p class="adm-help">Leave blank to use the built-in config default. Only change if you know your mail settings.</p>
        <div class="adm-row">
            <div><label class="adm-label">Host</label><input class="adm-input" name="<?= fld('smtp.host'); ?>" value="<?= e($overridesNow['smtp']['host'] ?? ''); ?>" placeholder="smtp.gmail.com"></div>
            <div><label class="adm-label">Port</label><input class="adm-input" name="<?= fld('smtp.port'); ?>" value="<?= e((string)($overridesNow['smtp']['port'] ?? '')); ?>" placeholder="587"></div>
        </div>
        <div class="adm-row">
            <div><label class="adm-label">Encryption</label><input class="adm-input" name="<?= fld('smtp.encryption'); ?>" value="<?= e($overridesNow['smtp']['encryption'] ?? ''); ?>" placeholder="tls"></div>
            <div><label class="adm-label">Username</label><input class="adm-input" name="<?= fld('smtp.user'); ?>" value="<?= e($overridesNow['smtp']['user'] ?? ''); ?>" placeholder="(config default)"></div>
        </div>
        <div class="adm-row">
            <div><label class="adm-label">Password</label><input class="adm-input" type="password" name="<?= fld('smtp.pass'); ?>" value="<?= e($overridesNow['smtp']['pass'] ?? ''); ?>" placeholder="(unchanged)"></div>
            <div><label class="adm-label">From email</label><input class="adm-input" name="<?= fld('smtp.from_email'); ?>" value="<?= e($overridesNow['smtp']['from_email'] ?? ''); ?>" placeholder="(config default)"></div>
        </div>
        <label class="adm-label">From name</label>
        <input class="adm-input" name="<?= fld('smtp.from_name'); ?>" value="<?= e($overridesNow['smtp']['from_name'] ?? ''); ?>" placeholder="Melbourne Print &amp; Publish">
    </div>

    <div class="adm-card">
        <h2>Lead <span>database</span></h2>
        <p class="adm-help">MySQL database that stores form submissions (the leads inbox). Leave blank to use the built-in config default; the password otherwise lives in <code>includes/config.secret.php</code>.</p>
        <div class="adm-row">
            <div><label class="adm-label">Host</label><input class="adm-input" name="<?= fld('db.host'); ?>" value="<?= e($overridesNow['db']['host'] ?? ''); ?>" placeholder="localhost"></div>
            <div><label class="adm-label">Database name</label><input class="adm-input" name="<?= fld('db.name'); ?>" value="<?= e($overridesNow['db']['name'] ?? ''); ?>" placeholder="melbelgx_leads"></div>
        </div>
        <div class="adm-row">
            <div><label class="adm-label">Username</label><input class="adm-input" name="<?= fld('db.user'); ?>" value="<?= e($overridesNow['db']['user'] ?? ''); ?>" placeholder="melbelgx_leadsuser"></div>
            <div><label class="adm-label">Password</label><input class="adm-input" type="password" name="<?= fld('db.pass'); ?>" value="<?= e($overridesNow['db']['pass'] ?? ''); ?>" placeholder="(unchanged)"></div>
        </div>
    </div>

    <div class="adm-card">
        <h2>reCAPTCHA <span>v3</span></h2>
        <p class="adm-help">Add keys to enable reCAPTCHA on the admin login (and public forms if ticked).</p>
        <div class="adm-row">
            <div><label class="adm-label">Site key</label><input class="adm-input" name="<?= fld('recaptcha.site_key'); ?>" value="<?= e($eff('recaptcha.site_key')); ?>"></div>
            <div><label class="adm-label">Secret key</label><input class="adm-input" type="password" name="<?= fld('recaptcha.secret_key'); ?>" value="<?= e($eff('recaptcha.secret_key')); ?>"></div>
        </div>
        <label class="adm-label"><input type="checkbox" name="<?= fld('recaptcha.enable_public_forms'); ?>" value="1" <?= $boolOn('recaptcha.enable_public_forms') ? 'checked' : ''; ?>> Also protect public contact forms</label>
    </div>

    <div class="adm-card">
        <h2>Live <span>chat</span></h2>
        <p class="adm-help">The Tawk.to loader URL is a live <code>&lt;script src&gt;</code>, so it's restricted to super-admins.</p>
        <label class="adm-label">Live chat (Tawk.to) script URL</label>
        <input class="adm-input" name="<?= fld('chat.tawk_src'); ?>" value="<?= e($eff('chat.tawk_src')); ?>">
    </div>

    <div class="adm-card">
        <h2>Custom <span>scripts</span></h2>
        <p class="adm-help"><strong>Advanced.</strong> Raw code injected verbatim. Head = inside &lt;head&gt;; Body open = just after &lt;body&gt;; Footer = before &lt;/body&gt;. Only super-admins can edit this.</p>
        <label class="adm-label">Head</label>
        <textarea class="adm-textarea" name="<?= fld('scripts.head'); ?>" rows="3" spellcheck="false"><?= e($eff('scripts.head')); ?></textarea>
        <label class="adm-label">Body open</label>
        <textarea class="adm-textarea" name="<?= fld('scripts.body_open'); ?>" rows="3" spellcheck="false"><?= e($eff('scripts.body_open')); ?></textarea>
        <label class="adm-label">Footer</label>
        <textarea class="adm-textarea" name="<?= fld('scripts.footer'); ?>" rows="3" spellcheck="false"><?= e($eff('scripts.footer')); ?></textarea>
    </div>

    <div class="adm-card">
        <h2>Organization <span>schema</span></h2>
        <label class="adm-label"><input type="checkbox" name="<?= fld('schema.organization_enabled'); ?>" value="1" <?= $boolOn('schema.organization_enabled') ? 'checked' : ''; ?>> Output a site-wide Organization JSON-LD block on every page</label>
        <label class="adm-label">Organization JSON-LD</label>
        <textarea class="adm-textarea" name="<?= fld('schema.organization_json'); ?>" rows="6" spellcheck="false" placeholder='{ "@context": "https://schema.org", "@type": "Organization", ... }'><?= e($eff('schema.organization_json')); ?></textarea>
    </div>
    <?php endif; ?>

    <p><button class="adm-btn" type="submit">Save site settings</button></p>
</form>

<div class="adm-card" style="max-width:680px">
    <h2>Lead <span>recipients</span></h2>
    <p class="adm-help" style="margin-bottom:10px">Email addresses that receive every form submission. One per line.</p>
    <form method="post">
        <?= admin_csrf_field(); ?>
        <input type="hidden" name="form" value="leads">
        <textarea class="adm-textarea" name="lead_recipients" rows="4"><?= e(implode("\n", $leadRecipients)); ?></textarea>
        <p style="margin-top:12px"><button class="adm-btn" type="submit">Save recipients</button></p>
    </form>
</div>
<?php admin_layout_end();
