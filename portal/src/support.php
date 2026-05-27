<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/db.php';
require_login();

$success = '';
$tickets = [];

// GET: load user's existing tickets
$stmt = $pdo->prepare("SELECT id, title, status, created_at FROM tickets WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$_SESSION['user_id']]);
$tickets = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title   = $_POST['title']   ?? '';
    $message = $_POST['message'] ?? '';

    // VULN: Không sanitize title hoặc message trước khi lưu DB
    // Raw HTML/JS payload được lưu nguyên vào database
    $stmt = $pdo->prepare("INSERT INTO tickets (user_id, title, message) VALUES (?, ?, ?)");
    $stmt->execute([$_SESSION['user_id'], $title, $message]);
    $ticket_id = $pdo->lastInsertId();

    $success = "Ticket #$ticket_id submitted successfully. Our team will review it shortly.";

    // Reload tickets
    $stmt = $pdo->prepare("SELECT id, title, status, created_at FROM tickets WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->execute([$_SESSION['user_id']]);
    $tickets = $stmt->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Support — Corp Portal</title>
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
        <h1>🎫 Submit Support Ticket</h1>

        <?php if ($success): ?>
            <p class="success"><?= htmlspecialchars($success) ?></p>
        <?php endif; ?>

        <form method="POST" action="/support.php">
            <label>Title</label>
            <input type="text" name="title" id="ticket_title" placeholder="Briefly describe the issue" required>
            <label>Message</label>
            <textarea name="message" id="ticket_message" rows="6" placeholder="Describe your issue in detail..." required></textarea>
            <button type="submit">Submit Ticket</button>
        </form>
    </div>

    <?php if (!empty($tickets)): ?>
    <div class="card">
        <h2>My Tickets</h2>
        <table>
            <thead>
                <tr><th>#</th><th>Title</th><th>Status</th><th>Date</th></tr>
            </thead>
            <tbody>
            <?php foreach ($tickets as $t): ?>
                <tr>
                    <td><?= (int)$t['id'] ?></td>
                    <td><?= htmlspecialchars($t['title']) ?></td>
                    <td><span class="badge"><?= htmlspecialchars($t['status']) ?></span></td>
                    <td><?= htmlspecialchars($t['created_at']) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>
<script src="/static/app.js"></script>
</body>
</html>
