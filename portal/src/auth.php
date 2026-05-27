<?php
// Shared session configuration — must be set before session_start()

// Shared session save path (Docker named volume mounted at /tmp/sessions)
if (!is_dir('/tmp/sessions')) {
    @mkdir('/tmp/sessions', 0777, true);
}
ini_set('session.save_path', '/tmp/sessions');

// VULN: cookie_domain = .lab.local — allows portal & staff to share same session cookie
ini_set('session.cookie_domain', '.lab.local');

// VULN: HttpOnly = 0 — JavaScript can read PHPSESSID cookie (required for session hijack)
ini_set('session.cookie_httponly', 0);

// VULN: SameSite = None — cookie sent on cross-origin requests
// Note: Changed to Lax so modern browsers accept cookie over HTTP.
// Because portal and staff share the .lab.local domain suffix, Lax still allows session sharing.
ini_set('session.cookie_samesite', 'Lax');

// VULN: Secure = 0 — cookie sent over HTTP (no HTTPS needed)
ini_set('session.cookie_secure', 0);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Redirect to login if not authenticated.
 */
function require_login(): void
{
    if (empty($_SESSION['user_id'])) {
        header('Location: /login.php');
        exit;
    }
}
