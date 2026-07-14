<?php
/**
 * Roles & Users (super-admin only). Guardrails enforced server-side:
 *  - the super-admin role is locked: no edit / rename / delete
 *  - custom roles can NEVER be granted '*' (module list built from
 *    checkboxes validated against admin_module_keys() only)
 *  - can't delete a role that still has users
 *  - can't delete your own account, can't delete/demote the last super-admin
 *  - a user whose role vanished has zero modules (fail closed, in auth.php)
 */
require_once __DIR__ . '/includes/auth.php';

$user = admin_require_admin();

$msg = '';
$err = '';

function users_with_role(string $roleName): int
{
    $n = 0;
    foreach (admin_load_users() as $u) {
        if (($u['role'] ?? '') === $roleName) {
            $n++;
        }
    }
    return $n;
}

function super_admin_count(): int
{
    $n = 0;
    foreach (admin_load_users() as $u) {
        $role = admin_get_role((string) ($u['role'] ?? ''));
        if ($role && in_array('*', (array) ($role['modules'] ?? []), true)) {
            $n++;
        }
    }
    return $n;
}

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    admin_require_csrf();
    $do = (string) ($_POST['do'] ?? '');

    if ($do === 'role_save') {
        $name = admin_slugify((string) ($_POST['role_name'] ?? ''));
        $label = trim(strip_tags((string) ($_POST['role_label'] ?? '')));
        $isNew = ($_POST['role_original'] ?? '') === '';
        $original = (string) ($_POST['role_original'] ?? $name);
        $modules = array_values(array_intersect((array) ($_POST['modules'] ?? []), admin_module_keys()));

        if ($name === '' || $label === '') {
            $err = 'Role name and label are required.';
        } elseif ($name === 'super-admin' || $original === 'super-admin') {
            $err = 'The super-admin role is locked.';
        } else {
            $roles = admin_load_roles();
            $existsAt = null;
            foreach ($roles as $i => $role) {
                if (($role['name'] ?? '') === ($isNew ? $name : $original)) {
                    $existsAt = $i;
                    break;
                }
            }
            if ($isNew && $existsAt !== null) {
                $err = 'A role with that name already exists.';
            } elseif (!$isNew && $existsAt === null) {
                $err = 'Role not found.';
            } else {
                $entry = ['name' => $isNew ? $name : $original, 'label' => $label, 'modules' => $modules, 'locked' => false];
                if ($isNew) {
                    $roles[] = $entry;
                } else {
                    $roles[$existsAt] = $entry;
                }
                $msg = admin_save_roles($roles) ? 'Role saved.' : '';
                $err = $msg === '' ? 'Could not save roles.' : '';
            }
        }
    } elseif ($do === 'role_delete') {
        $name = (string) ($_POST['role_name'] ?? '');
        if ($name === 'super-admin') {
            $err = 'The super-admin role is locked.';
        } elseif (users_with_role($name) > 0) {
            $err = 'That role still has users assigned to it.';
        } else {
            $roles = array_values(array_filter(admin_load_roles(), fn($r) => ($r['name'] ?? '') !== $name));
            $msg = admin_save_roles($roles) ? 'Role deleted.' : '';
            $err = $msg === '' ? 'Could not save roles.' : '';
        }
    } elseif ($do === 'user_save') {
        $username = trim((string) ($_POST['username'] ?? ''));
        $original = trim((string) ($_POST['user_original'] ?? ''));
        $roleName = (string) ($_POST['role'] ?? '');
        $password = (string) ($_POST['password'] ?? '');
        $isNew = $original === '';

        if (!preg_match('~^[a-zA-Z0-9._-]{3,40}$~', $username)) {
            $err = 'Username: 3-40 letters, digits, dots, dashes.';
        } elseif (!admin_get_role($roleName)) {
            $err = 'Pick a valid role.';
        } elseif ($isNew && admin_find_user($username)) {
            $err = 'That username is taken.';
        } elseif ($isNew && strlen($password) < 10) {
            $err = 'New accounts need a password of at least 10 characters.';
        } else {
            $target = $isNew ? null : admin_find_user($original);
            if (!$isNew && !$target) {
                $err = 'User not found.';
            } else {
                // Demotion guard: never remove the last super-admin.
                if (!$isNew && admin_is_super($target) && super_admin_count() <= 1) {
                    $newRole = admin_get_role($roleName);
                    if (!$newRole || !in_array('*', (array) ($newRole['modules'] ?? []), true)) {
                        $err = 'You cannot demote the last super-admin.';
                    }
                }
                if ($err === '') {
                    $users = admin_load_users();
                    if ($isNew) {
                        $users[] = [
                            'username' => $username,
                            'password' => password_hash($password, PASSWORD_BCRYPT),
                            'role'     => $roleName,
                            'created'  => date('c'),
                        ];
                    } else {
                        foreach ($users as $i => $u) {
                            if (strcasecmp((string) $u['username'], $original) === 0) {
                                $users[$i]['role'] = $roleName;
                                if ($password !== '') {
                                    if (strlen($password) < 10) {
                                        $err = 'Password must be at least 10 characters.';
                                        break;
                                    }
                                    $users[$i]['password'] = password_hash($password, PASSWORD_BCRYPT);
                                }
                            }
                        }
                    }
                    if ($err === '') {
                        $msg = admin_save_users($users) ? 'User saved.' : '';
                        $err = $msg === '' ? 'Could not save users.' : '';
                    }
                }
            }
        }
    } elseif ($do === 'user_delete') {
        $username = (string) ($_POST['username'] ?? '');
        $target = admin_find_user($username);
        if (!$target) {
            $err = 'User not found.';
        } elseif (strcasecmp($username, (string) $user['username']) === 0) {
            $err = 'You cannot delete your own account.';
        } elseif (admin_is_super($target) && super_admin_count() <= 1) {
            $err = 'You cannot delete the last super-admin.';
        } else {
            $users = array_values(array_filter(
                admin_load_users(),
                fn($u) => strcasecmp((string) $u['username'], $username) !== 0
            ));
            $msg = admin_save_users($users) ? 'User deleted.' : '';
            $err = $msg === '' ? 'Could not save users.' : '';
        }
    } elseif ($do === 'user_reset_2fa') {
        $username = (string) ($_POST['username'] ?? '');
        if (!admin_find_user($username)) {
            $err = 'User not found.';
        } else {
            $users = admin_load_users();
            foreach ($users as $i => $u) {
                if (strcasecmp((string) $u['username'], $username) === 0) {
                    unset($users[$i]['totp_secret'], $users[$i]['totp_backup']);
                }
            }
            $msg = admin_save_users($users) ? '2FA was reset for ' . $username . '.' : '';
            $err = $msg === '' ? 'Could not save users.' : '';
        }
    }

    // Post/Redirect/Get: a refresh never re-runs the POST. On error, re-open the
    // form the action came from (user vs role) so the message has context.
    $suffix = '';
    if ($err !== '') {
        if (strncmp($do, 'user', 4) === 0 && ($_GET['edit_user'] ?? '') !== '') {
            $suffix = '?edit_user=' . rawurlencode((string) $_GET['edit_user']) . '#userform';
        } elseif (strncmp($do, 'role', 4) === 0 && ($_GET['edit_role'] ?? '') !== '') {
            $suffix = '?edit_role=' . rawurlencode((string) $_GET['edit_role']) . '#roleform';
        }
    }
    admin_flash($err !== '' ? $err : $msg, $err !== '' ? 'error' : 'ok');
    admin_redirect('users.php' . $suffix);
}

$flash = admin_flash_take();
if ($flash['type'] === 'error') { $err = $flash['msg']; } else { $msg = $flash['msg']; }

$roles = admin_load_roles();
$users = admin_load_users();
$editRole = null;
if (($_GET['edit_role'] ?? '') !== '') {
    foreach ($roles as $role) {
        if (($role['name'] ?? '') === $_GET['edit_role'] && empty($role['locked'])) {
            $editRole = $role;
        }
    }
}
$editUser = ($_GET['edit_user'] ?? '') !== '' ? admin_find_user((string) $_GET['edit_user']) : null;

admin_layout_start($user, 'users', 'Roles & Users');
?>
<div class="adm-page-head">
    <div>
        <div class="adm-eyebrow">Access control</div>
        <h1>Roles &amp; <span>Users</span></h1>
    </div>
</div>

<?php if ($msg): ?><div class="adm-alert adm-alert-ok"><?= e($msg); ?></div><?php endif; ?>
<?php if ($err): ?><div class="adm-alert adm-alert-error"><?= e($err); ?></div><?php endif; ?>

<div class="adm-editor-grid">
    <div>
        <div class="adm-card">
            <h2>Users</h2>
            <div class="adm-tablewrap">
            <table class="adm-table">
                <thead><tr><th>Username</th><th>Role</th><th>2FA</th><th>Last login</th><th></th></tr></thead>
                <tbody>
                <?php foreach ($users as $u): $roleObj = admin_get_role((string) ($u['role'] ?? '')); ?>
                    <tr>
                        <td><strong><?= e((string) $u['username']); ?></strong></td>
                        <td>
                            <?php if ($roleObj): ?>
                                <span class="adm-chip <?= in_array('*', (array) $roleObj['modules'], true) ? 'adm-chip-accent' : 'adm-chip-grey'; ?>"><?= e((string) ($roleObj['label'] ?? $roleObj['name'])); ?></span>
                            <?php else: ?>
                                <span class="adm-chip adm-chip-red">missing role &middot; no access</span>
                            <?php endif; ?>
                        </td>
                        <td><?= !empty($u['totp_secret']) ? '<span class="adm-chip adm-chip-green">on</span>' : '<span class="adm-chip adm-chip-grey">off</span>'; ?></td>
                        <td><small class="adm-muted"><?= e((string) ($u['last_login'] ?? 'never')); ?></small></td>
                        <td style="white-space:nowrap">
                            <a class="adm-btn adm-btn-ghost adm-btn-sm" href="?edit_user=<?= e(rawurlencode((string) $u['username'])); ?>#userform">Edit</a>
                            <?php if (!empty($u['totp_secret'])): ?>
                            <form method="post" style="display:inline" onsubmit="return confirm('Reset 2FA for this user?');"><?= admin_csrf_field(); ?><input type="hidden" name="do" value="user_reset_2fa"><input type="hidden" name="username" value="<?= e((string) $u['username']); ?>"><button class="adm-btn adm-btn-ghost adm-btn-sm">Reset 2FA</button></form>
                            <?php endif; ?>
                            <form method="post" style="display:inline" onsubmit="return confirm('Delete this user?');"><?= admin_csrf_field(); ?><input type="hidden" name="do" value="user_delete"><input type="hidden" name="username" value="<?= e((string) $u['username']); ?>"><button class="adm-btn adm-btn-danger adm-btn-sm">&times;</button></form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            </div>
        </div>

        <div class="adm-card" id="userform">
            <h2><?= $editUser ? 'Edit <span>user</span>' : 'Add <span>user</span>'; ?></h2>
            <form method="post" autocomplete="off">
                <?= admin_csrf_field(); ?>
                <input type="hidden" name="do" value="user_save">
                <input type="hidden" name="user_original" value="<?= e((string) ($editUser['username'] ?? '')); ?>">
                <div class="adm-row">
                    <div>
                        <label class="adm-label">Username</label>
                        <input class="adm-input" type="text" name="username" required value="<?= e((string) ($editUser['username'] ?? '')); ?>" <?= $editUser ? 'readonly' : ''; ?>>
                    </div>
                    <div>
                        <label class="adm-label">Role</label>
                        <select class="adm-select" name="role">
                            <?php foreach ($roles as $role): ?>
                            <option value="<?= e((string) $role['name']); ?>" <?= ($editUser['role'] ?? '') === $role['name'] ? 'selected' : ''; ?>><?= e((string) ($role['label'] ?? $role['name'])); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <label class="adm-label"><?= $editUser ? 'New password (leave blank to keep)' : 'Password (min 10 chars)'; ?></label>
                <input class="adm-input" type="password" name="password" autocomplete="new-password" <?= $editUser ? '' : 'required'; ?>>
                <p style="margin-top:16px">
                    <button class="adm-btn" type="submit"><?= $editUser ? 'Save user' : 'Add user'; ?></button>
                    <?php if ($editUser): ?><a class="adm-btn adm-btn-ghost" href="users.php">Cancel</a><?php endif; ?>
                </p>
            </form>
        </div>
    </div>

    <div>
        <div class="adm-card">
            <h2>Roles</h2>
            <?php foreach ($roles as $role): $locked = !empty($role['locked']); ?>
            <div style="border-bottom:1px solid var(--adm-rule);padding:10px 0">
                <strong><?= e((string) ($role['label'] ?? $role['name'])); ?></strong>
                <?php if ($locked): ?><span class="adm-chip adm-chip-accent">locked</span><?php endif; ?>
                <br><small class="adm-muted">
                    <?= in_array('*', (array) ($role['modules'] ?? []), true)
                        ? 'All modules'
                        : e(implode(', ', array_map(fn($m) => admin_modules()[$m] ?? $m, (array) ($role['modules'] ?? []))) ?: 'No modules'); ?>
                    &middot; <?= users_with_role((string) $role['name']); ?> user(s)
                </small>
                <?php if (!$locked): ?>
                <div style="margin-top:6px">
                    <a class="adm-btn adm-btn-ghost adm-btn-sm" href="?edit_role=<?= e(rawurlencode((string) $role['name'])); ?>#roleform">Edit</a>
                    <form method="post" style="display:inline" onsubmit="return confirm('Delete this role?');"><?= admin_csrf_field(); ?><input type="hidden" name="do" value="role_delete"><input type="hidden" name="role_name" value="<?= e((string) $role['name']); ?>"><button class="adm-btn adm-btn-danger adm-btn-sm">&times;</button></form>
                </div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="adm-card" id="roleform">
            <h2><?= $editRole ? 'Edit <span>role</span>' : 'Add <span>role</span>'; ?></h2>
            <form method="post">
                <?= admin_csrf_field(); ?>
                <input type="hidden" name="do" value="role_save">
                <input type="hidden" name="role_original" value="<?= e((string) ($editRole['name'] ?? '')); ?>">
                <label class="adm-label">Label</label>
                <input class="adm-input" type="text" name="role_label" required value="<?= e((string) ($editRole['label'] ?? '')); ?>">
                <?php if (!$editRole): ?>
                <label class="adm-label">Name (slug)</label>
                <input class="adm-input" type="text" name="role_name" required placeholder="e.g. publisher">
                <?php else: ?>
                <input type="hidden" name="role_name" value="<?= e((string) $editRole['name']); ?>">
                <?php endif; ?>
                <label class="adm-label">Modules</label>
                <div class="adm-checkgrid">
                    <?php foreach (admin_modules() as $key => $label): ?>
                    <label><input type="checkbox" name="modules[]" value="<?= e($key); ?>" <?= in_array($key, (array) ($editRole['modules'] ?? []), true) ? 'checked' : ''; ?>> <?= e($label); ?></label>
                    <?php endforeach; ?>
                </div>
                <p class="adm-help">Roles &amp; Users itself is always super-admin only and cannot be granted here.</p>
                <p style="margin-top:14px">
                    <button class="adm-btn" type="submit"><?= $editRole ? 'Save role' : 'Add role'; ?></button>
                    <?php if ($editRole): ?><a class="adm-btn adm-btn-ghost" href="users.php">Cancel</a><?php endif; ?>
                </p>
            </form>
        </div>
    </div>
</div>
<?php admin_layout_end();
