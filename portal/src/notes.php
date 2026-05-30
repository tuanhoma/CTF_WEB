<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/db.php';
require_login();

$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $content = $_POST['content'] ?? '';

    // VULN: no sanitization — raw HTML stored in DB
    $stmt = $pdo->prepare("INSERT INTO notes (user_id, content) VALUES (?, ?)");
    $stmt->execute([$_SESSION['user_id'], $content]);
    $success = 'Note saved.';
}

$stmt = $pdo->prepare("SELECT id, content, created_at FROM notes WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$_SESSION['user_id']]);
$notes = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Notes — Corp Portal</title>
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
            <h1>📝 My Notes</h1>
            <?php if ($success): ?>
                <p class="success"><?= htmlspecialchars($success) ?></p>
            <?php endif; ?>
            <form method="POST" action="/notes.php">
                <label>New Note</label>
                <textarea name="content" id="note_content" rows="5" placeholder="Write your note here..."></textarea>
                <button type="submit">Save Note</button>
            </form>
        </div>

        <?php if (!empty($notes)): ?>
            <div class="card">
                <h2>Saved Notes</h2>
                <?php foreach ($notes as $note): ?>
                    <div class="note-item">
                        <small><?= htmlspecialchars($note['created_at']) ?></small>
                        <div class="note-content">
                            <?php
                            // VULN: echo message directly — NO htmlspecialchars()
                            // Any HTML/JS payload stored by user is rendered raw in admin's browser
                            // This triggers stored XSS when admin views the ticket
                            $content = $note['content'];

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
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    <script src="/static/app.js"></script>
</body>

</html>