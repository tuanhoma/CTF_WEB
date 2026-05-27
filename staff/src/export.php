<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/db.php';
require_staff();

$users = $pdo->query("SELECT id, username, email, role, created_at FROM users ORDER BY id")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Export Users — Staff Panel</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background: #f4f6f9; }
        .container { max-width: 1000px; margin: 0 auto; padding: 20px; }
        .navbar { display:flex; justify-content:space-between; align-items:center; background:#b71c1c; color:white; padding:12px 20px; border-radius:8px; margin-bottom:20px; }
        .navbar a { color:white; text-decoration:none; margin-left:16px; }
        .card { background:white; border-radius:8px; padding:24px; box-shadow:0 2px 8px rgba(0,0,0,0.1); }
        h1 { color:#b71c1c; }
        table { width:100%; border-collapse:collapse; }
        th,td { padding:10px 12px; text-align:left; border-bottom:1px solid #eee; }
        th { background:#ffebee; color:#b71c1c; }
    </style>
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
        </div>
    </div>
    <div class="card">
        <h1>📊 User Export</h1>
        <table>
            <thead>
                <tr><th>#</th><th>Username</th><th>Email</th><th>Role</th><th>Created</th></tr>
            </thead>
            <tbody>
            <?php foreach ($users as $u): ?>
                <tr>
                    <td><?= (int)$u['id'] ?></td>
                    <td><?= htmlspecialchars($u['username']) ?></td>
                    <td><?= htmlspecialchars($u['email']) ?></td>
                    <td><?= htmlspecialchars($u['role']) ?></td>
                    <td><?= htmlspecialchars($u['created_at']) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
