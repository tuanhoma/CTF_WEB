<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../models/User.php';

class AuthController
{
    public function login()
    {
        $message = "";
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            if (empty($username) || empty($password)) {
                return "Vui lòng nhập đầy đủ username và password!";
            }

            $userModel = new User();
            $result = $userModel->login($username, $password);

            if ($result === true) {
                // Lấy thông tin user từ session (được set trong model)
                $login_user = $_SESSION['username'];
                if ($login_user === "admin" || $_SESSION['role'] === "admin") {
                    $message = "Wow you can log in as admin, here is your flag CBJS{FAKE_FLAG_FAKE_FLAG}, but how about <a href='level2.php'>THIS LEVEL</a>!";
                } else {
                    $message = "You log in as $login_user, but then what? You are not an admin";
                }
            } else {
                $message = $result; // Thông báo lỗi
            }
        }
        return $message;
    }
}
