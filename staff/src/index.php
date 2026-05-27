<?php
require_once __DIR__ . '/auth.php';
require_staff();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Staff Dashboard</title>
    <link rel="stylesheet" href="/static/style.css">
</head>
<body>
<div class="container">
    <div class="navbar">
        <span>🛡️ Staff Panel</span>
        <div>
            <a href="/index.php">Home</a>
            <a href="/tickets.php">Tickets</a>
            <a href="/integration.php">Integration</a>
            <a href="/export.php">Export</a>
            <a href="/logs.php">Logs</a>
            <a href="http://portal.lab.local/logout.php">Logout</a>
        </div>
    </div>

    <div class="card">
        <h1>Welcome, <?= htmlspecialchars($_SESSION['username']) ?> 👋</h1>
        <p>Role: <strong><?= htmlspecialchars($_SESSION['role']) ?></strong></p>
    </div>

    <div class="menu-grid" style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;">
        <a href="/tickets.php" class="menu-item" style="display:block;padding:20px;background:#e8eaf6;border-radius:8px;text-decoration:none;color:#1a237e;text-align:center;">
            <span style="font-size:32px;display:block;">🎫</span>
            <strong>Support Tickets</strong>
        </a>
        <a href="/integration.php" class="menu-item" style="display:block;padding:20px;background:#e8eaf6;border-radius:8px;text-decoration:none;color:#1a237e;text-align:center;">
            <span style="font-size:32px;display:block;">🔗</span>
            <strong>Integration Tools</strong>
        </a>
        <a href="/export.php" class="menu-item" style="display:block;padding:20px;background:#e8eaf6;border-radius:8px;text-decoration:none;color:#1a237e;text-align:center;">
            <span style="font-size:32px;display:block;">📊</span>
            <strong>Export Users</strong>
        </a>
        <a href="/logs.php" class="menu-item" style="display:block;padding:20px;background:#e8eaf6;border-radius:8px;text-decoration:none;color:#1a237e;text-align:center;">
            <span style="font-size:32px;display:block;">📋</span>
            <strong>Access Logs</strong>
        </a>
    </div>
</div>
</body>
</html>
