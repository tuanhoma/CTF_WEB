<?php
require_once __DIR__ . '/auth.php';
require_login();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Profile — Corp Portal</title>
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
            <h1>👤 Profile</h1>
            <p><strong>Username:</strong> <?= htmlspecialchars($_SESSION['username']) ?></p>
            <p><strong>Role:</strong> <?= htmlspecialchars($_SESSION['role']) ?></p>
            <hr>
            <h3>Upload Avatar (chức năng đang được phát triển)!</h3>
            <form method="POST" enctype="multipart/form-data" action="/profile.php">
                <!-- VULN nhẹ: accept image/svg+xml cho phép SVG XSS -->
                <input type="file" name="avatar" id="avatar_upload" accept="image/*,image/svg+xml">
                <button type="submit">Upload</button>
            </form>
        </div>
    </div>
</body>

</html>