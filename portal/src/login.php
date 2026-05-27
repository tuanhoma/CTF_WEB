<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    // VULN: password stored as MD5 (weak hashing)
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? AND password_hash = MD5(?)");
    $stmt->execute([$username, $password]);
    $user = $stmt->fetch();

    if ($user) {
        $_SESSION['user_id']  = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role']     = $user['role'];

        // Redirect admin/staff to staff panel
        if (in_array($user['role'], ['admin', 'staff'])) {
            header('Location: http://staff.lab.local/');
        } else {
            header('Location: /dashboard.php');
        }
        exit;
    } else {
        $error = 'Invalid username or password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login — Corp Portal</title>
    <link rel="stylesheet" href="/static/style.css">
</head>
<body>
<div class="container">
    <h1>🏢 Corp Employee Portal</h1>
    <div class="card">
        <h2>Sign In</h2>
        <?php if ($error): ?>
            <p class="error"><?= $error ?></p>
        <?php endif; ?>
        <form method="POST" action="/login.php">
            <label>Username</label>
            <input type="text" name="username" id="username" required autofocus>
            <label>Password</label>
            <input type="password" name="password" id="password" required>
            <button type="submit">Login</button>
        </form>
        <p class="hint">No account? <a href="/register.php">Register here</a></p>
    </div>
</div>
<script src="/static/app.js"></script>
</body>
</html>
