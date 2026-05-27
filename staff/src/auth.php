<?php
// Shared session configuration — mirrors portal/src/auth.php exactly
// Both portal and staff read sessions from the same Docker volume (/tmp/sessions)

if (!is_dir('/tmp/sessions')) {
    @mkdir('/tmp/sessions', 0777, true);
}
ini_set('session.save_path', '/tmp/sessions');
ini_set('session.cookie_domain', '.lab.local');

// VULN: HttpOnly = 0 — JS readable cookie (mirrors portal config)
ini_set('session.cookie_httponly', 0);
ini_set('session.cookie_samesite', 'Lax');
ini_set('session.cookie_secure', 0);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Require admin or staff role.
 * VULN: Only checks role in session — does NOT verify IP or User-Agent.
 * An attacker with a stolen PHPSESSID can access the staff panel from any IP.
 */
function require_staff(): void
{
    if (empty($_SESSION['user_id'])) {
        header('Location: http://portal.lab.local/login.php');
        exit;
    }

    // VULN: chỉ check role trong session, không verify IP hay User-Agent
    if (!in_array($_SESSION['role'] ?? '', ['admin', 'staff'])) {
        http_response_code(403);
        die('<h1>403 Forbidden</h1><p>Staff access only.</p>');
    }
}
