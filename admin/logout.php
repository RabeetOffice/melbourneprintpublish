<?php
require_once __DIR__ . '/includes/auth.php';
admin_security_headers();
admin_logout();
header('Location: login.php');
exit;
