<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/db.php';

$success = '';
$error   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $email    = trim($_POST['email'] ?? '');

    if (empty($username) || empty($password)) {
        $error = 'Username and password are required.';
    } else {
        // VULN: MD5 hashing — yếu có chủ ý
        $hash = md5($password);
        try {
            $stmt = $pdo->prepare("INSERT INTO users (username, password_hash, email, role) VALUES (?, ?, ?, 'user')");
            $stmt->execute([$username, $hash, $email]);
            $success = 'Account created! <a href="/login.php">Login now</a>';
        } catch (PDOException $e) {
            $error = 'Username already exists.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register — Corp Portal</title>
    <link rel="stylesheet" href="/static/style.css">
</head>
<body>
<div class="container">
    <h1>🏢 Corp Employee Portal</h1>
    <div class="card">
        <h2>Create Account</h2>
        <?php if ($error): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <?php if ($success): ?>
            <p class="success"><?= $success ?></p>
        <?php endif; ?>
        <form method="POST" action="/register.php">
            <label>Username</label>
            <input type="text" name="username" id="reg_username" required>
            <label>Email</label>
            <input type="email" name="email" id="reg_email">
            <label>Password</label>
            <input type="password" name="password" id="reg_password" required>
            <button type="submit">Register</button>
        </form>
        <p class="hint">Already have an account? <a href="/login.php">Login</a></p>
    </div>
</div>
</body>
</html>
