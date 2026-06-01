<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/db.php';
require_login();

$id = $_GET['id'] ?? null;
if (!$id) {
    http_response_code(400);
    die('Missing ticket ID.');
}

$stmt = $pdo->prepare("
    SELECT t.*, u.username
    FROM tickets t
    LEFT JOIN users u ON t.user_id = u.id
    WHERE t.id = ? AND t.user_id = ?
");
$stmt->execute([$id, $_SESSION['user_id']]);
$ticket = $stmt->fetch();

if (!$ticket) {
    http_response_code(404);
    die('Ticket not found or you do not have permission to view it.');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ticket #<?= (int)$ticket['id'] ?> — Corp Portal</title>
    <link rel="stylesheet" href="/static/style.css">
    <style>
        .meta { color: #666; font-size: 14px; margin-bottom: 16px; }
        .message-box { background: #f9f9f9; border-left: 4px solid #0056b3; padding: 16px; border-radius: 0 8px 8px 0; min-height: 60px; margin-bottom: 20px; white-space: pre-wrap; }
        .back-btn { background: #6c757d; color: white; padding: 8px 16px; border-radius: 4px; text-decoration: none; display: inline-block; }
        .back-btn:hover { background: #5a6268; }
    </style>
</head>
<body>
    <div class="container">
        <div class="navbar">
            <span>🏢 Corp Portal</span>
            <div>
                <a href="/dashboard.php">Home</a>
                <a href="/support.php">← Back to Support</a>
            </div>
        </div>

        <div class="card">
            <h1>Ticket #<?= (int)$ticket['id'] ?>: <?= htmlspecialchars($ticket['title']) ?></h1>
            <div class="meta">
                <strong>Status:</strong> <span class="badge"><?= htmlspecialchars($ticket['status']) ?></span> &nbsp;|&nbsp;
                <strong>Date:</strong> <?= htmlspecialchars($ticket['created_at']) ?>
            </div>

            <h3>Message:</h3>
            <div class="message-box"><?php
                // VULN: echo message directly — NO htmlspecialchars()
                // Any HTML/JS payload stored by user is rendered raw in admin's browser
                // This triggers stored XSS when admin views the ticket
                $content = $ticket['message'];

                // Weak blacklist filter (intentionally vulnerable)
                if (
                    stripos($content, 'script') > 0 ||
                    preg_match('/on\w+\s*=/i', $content)
                ) {
                    echo "Hack detected";
                    return;
                }

                // weak sanitization
                $sanitized_q = preg_replace(
                    [
                        '/<script>|<\/script>/i',
                        '/on\w+\s*=/i'
                    ],
                    '',
                    $content
                );

                echo $sanitized_q;
                ?></div>
            
            <a class="back-btn" href="/support.php">← Back to Tickets</a>
        </div>
    </div>
    <script src="/static/app.js"></script>
</body>
</html>
