<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/db.php';
require_staff();

// Handle delete ticket
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $ticket_id = $_POST['ticket_id'] ?? null;
    if ($ticket_id) {
        $stmt = $pdo->prepare("DELETE FROM tickets WHERE id = ?");
        $stmt->execute([$ticket_id]);
    }
    header('Location: /tickets.php');
    exit;
}

$stmt = $pdo->query("
    SELECT t.id, t.title, t.status, t.created_at, u.username
    FROM tickets t
    LEFT JOIN users u ON t.user_id = u.id
    ORDER BY t.created_at DESC
");
$tickets = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Support Tickets — Staff Panel</title>
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
        a.view-btn { background:#b71c1c; color:white; padding:4px 12px; border-radius:4px; text-decoration:none; font-size:13px; }
        a.view-btn:hover { background:#c62828; }
        .delete-btn { background:#d32f2f; color:white; padding:4px 12px; border:none; border-radius:4px; font-size:13px; cursor:pointer; }
        .delete-btn:hover { background:#b71c1c; }
        .action-cell { display:flex; gap:6px; align-items:center; }
        .badge-open { background:#fff3e0; color:#e65100; padding:2px 8px; border-radius:12px; font-size:12px; }
        .badge-closed { background:#e8f5e9; color:#2e7d32; padding:2px 8px; border-radius:12px; font-size:12px; }
        .empty { color:#999; font-style:italic; padding:20px; }
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
        <h1>🎫 All Support Tickets</h1>
        <?php if (empty($tickets)): ?>
            <p class="empty">No tickets found.</p>
        <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Submitted By</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($tickets as $ticket): ?>
                <tr>
                    <td><?= (int)$ticket['id'] ?></td>
                    <td><?= htmlspecialchars($ticket['title']) ?></td>
                    <td><?= htmlspecialchars($ticket['username'] ?? 'unknown') ?></td>
                    <td>
                        <span class="badge-<?= htmlspecialchars($ticket['status']) ?>">
                            <?= htmlspecialchars($ticket['status']) ?>
                        </span>
                    </td>
                    <td><?= htmlspecialchars($ticket['created_at']) ?></td>
                    <td class="action-cell">
                        <a class="view-btn" href="/ticket_view.php?id=<?= (int)$ticket['id'] ?>">View</a>
                        <form method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete ticket #<?= (int)$ticket['id'] ?>?');">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="ticket_id" value="<?= (int)$ticket['id'] ?>">
                            <button type="submit" class="delete-btn">🗑 Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
