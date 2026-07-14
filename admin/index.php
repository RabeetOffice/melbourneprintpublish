<?php
require_once __DIR__ . '/includes/auth.php';
admin_session_start();
admin_security_headers();
$user = admin_current_user();
header('Location: ' . ($user ? admin_home_url($user) : admin_url('login.php')));
exit;
