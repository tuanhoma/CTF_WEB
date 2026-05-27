<?php
require_once __DIR__ . '/auth.php';
require_login();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard — Corp Portal</title>
    <link rel="stylesheet" href="/static/style.css">
</head>
<body>
<div class="container">
    <div class="navbar">
        <span>🏢 Corp Portal</span>
        <div>
            <a href="/dashboard.php">Home</a>
            <a href="/support.php">Support</a>
            <a href="/notes.php">Notes</a>
            <a href="/profile.php">Profile</a>
            <a href="/logout.php">Logout</a>
        </div>
    </div>

    <div class="card">
        <h1>Welcome, <?= htmlspecialchars($_SESSION['username']) ?>! 👋</h1>
        <p>Role: <strong><?= htmlspecialchars($_SESSION['role']) ?></strong></p>
        <hr>
        <div class="menu-grid">
            <a href="/support.php" class="menu-item">
                <span>🎫</span>
                <strong>Support Tickets</strong>
                <p>Submit a request to the IT team</p>
            </a>
            <a href="/notes.php" class="menu-item">
                <span>📝</span>
                <strong>My Notes</strong>
                <p>Personal notes workspace</p>
            </a>
            <a href="/profile.php" class="menu-item">
                <span>👤</span>
                <strong>Profile</strong>
                <p>Manage your account settings</p>
            </a>
        </div>
    </div>
</div>
<script src="/static/app.js"></script>
</body>
</html>
