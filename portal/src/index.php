<?php
require_once __DIR__ . '/auth.php';
if (!empty($_SESSION['user_id'])) {
    if (in_array($_SESSION['role'], ['admin', 'staff'])) {
        header('Location: http://staff.lab.local/');
    } else {
        header('Location: /dashboard.php');
    }
    exit;
}
header('Location: /login.php');
exit;
