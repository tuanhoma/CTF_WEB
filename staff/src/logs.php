<?php
require_once __DIR__ . '/auth.php';
require_staff();

$log_file = '/var/log/nginx/portal_access.log';
$lines    = [];

if (file_exists($log_file)) {
    $lines = array_slice(file($log_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES), -200);
} else {
    $lines = ['Log file not found: ' . $log_file];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Access Logs — Staff Panel</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background: #f4f6f9; }
        .container { max-width: 1100px; margin: 0 auto; padding: 20px; }
        .navbar { display:flex; justify-content:space-between; align-items:center; background:#b71c1c; color:white; padding:12px 20px; border-radius:8px; margin-bottom:20px; }
        .navbar a { color:white; text-decoration:none; margin-left:16px; }
        .card { background:white; border-radius:8px; padding:24px; box-shadow:0 2px 8px rgba(0,0,0,0.1); }
        h1 { color:#b71c1c; }
        pre { background:#1a1a2e; color:#00ff41; padding:16px; border-radius:8px; overflow-x:auto; white-space:pre-wrap; max-height:600px; overflow-y:auto; font-size:12px; }
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
        <h1>📋 Nginx Access Logs (last 200 lines)</h1>
        <pre id="log_output"><?= htmlspecialchars(implode("\n", $lines)) ?></pre>
    </div>
</div>
</body>
</html>
