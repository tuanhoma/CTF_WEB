<?php
// Cấu hình chia sẻ Session để khớp với Portal và Staff
if (!is_dir('/tmp/sessions')) {
    @mkdir('/tmp/sessions', 0777, true);
}
ini_set('session.save_path', '/tmp/sessions');
ini_set('session.cookie_domain', '.lab.local');
ini_set('session.cookie_httponly', 0);
ini_set('session.cookie_samesite', 'Lax');
ini_set('session.cookie_secure', 0);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

error_log("Cookie received: " . print_r($_COOKIE, true));
error_log("Session loaded: " . print_r($_SESSION, true));

if (!isset($_SESSION['role'])) {
    http_response_code(403);
    exit("403 Forbidden - Missing session role");
}

if ($_SESSION['role'] !== 'staff' && $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    exit("403 Forbidden - Invalid role");
}
