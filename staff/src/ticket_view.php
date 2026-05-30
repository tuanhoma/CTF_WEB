<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/db.php';
require_staff();

$id = $_GET['id'] ?? null;
if (!$id) {
    http_response_code(400);
    die('Missing ticket ID.');
}

$stmt = $pdo->prepare("
    SELECT t.*, u.username
    FROM tickets t
    LEFT JOIN users u ON t.user_id = u.id
    WHERE t.id = ?
");
$stmt->execute([$id]);
$ticket = $stmt->fetch();

if (!$ticket) {
    http_response_code(404);
    die('Ticket not found.');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Ticket #<?= (int)$ticket['id'] ?> — Staff Panel</title>
    <!-- VULN: No Content-Security-Policy header — allows XSS to execute freely -->
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background: #f4f6f9;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #b71c1c;
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            margin-left: 16px;
        }

        .card {
            background: white;
            border-radius: 8px;
            padding: 24px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 16px;
        }

        h1 {
            color: #b71c1c;
        }

        .meta {
            color: #666;
            font-size: 14px;
            margin-bottom: 16px;
        }

        .message-box {
            background: #fff8f8;
            border-left: 4px solid #b71c1c;
            padding: 16px;
            border-radius: 0 8px 8px 0;
            min-height: 60px;
        }

        .back-btn {
            background: #b71c1c;
            color: white;
            padding: 8px 16px;
            border-radius: 4px;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="navbar">
            <span>🛡️ Staff Panel</span>
            <div>
                <a href="/index.php">Home</a>
                <a href="/tickets.php">← All Tickets</a>
            </div>
        </div>

        <div class="card">
            <h1>Ticket #<?= (int)$ticket['id'] ?>: <?= htmlspecialchars($ticket['title']) ?></h1>
            <div class="meta">
                <strong>From:</strong> <?= htmlspecialchars($ticket['username'] ?? 'unknown') ?> &nbsp;|&nbsp;
                <strong>Status:</strong> <?= htmlspecialchars($ticket['status']) ?> &nbsp;|&nbsp;
                <strong>Date:</strong> <?= htmlspecialchars($ticket['created_at']) ?>
            </div>

            <h3>Message:</h3>
            <div class="message-box">
                <?php
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
                ?>
            </div>
        </div>

        <a class="back-btn" href="/tickets.php">← Back to Tickets</a>
    </div>
</body>

</html>