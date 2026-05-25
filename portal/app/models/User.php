<?php
require_once __DIR__ . '/../config/db.php';
class User
{
    public function __construct()
    {
        // Có thể khởi tạo một số thứ nếu cần
    }

    /**
     * Tìm user theo username (không cần password)
     */
    public function findUserByUsername($username)
    {
        try {
            $database = make_connection("myapp_db");
            $sql = "SELECT username FROM users WHERE username = '$username'";
            $query = $database->query($sql);
            if (!$query) {
                return false;
            }
            $row = $query->fetch_assoc();

            if ($row === NULL) {
                return false; // hoặc throw exception
            }

            return true;
        } catch (mysqli_sql_exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Đăng nhập và set session
     */
    public function login($username, $password)
    {
        try {
            $database = make_connection("myapp_db");
            $sql = "SELECT username FROM users WHERE username='$username' AND password='$password'";
            $query = $database->query($sql);
            if (!$query) {
                return "Database query failed";
            }
            $row = $query->fetch_assoc();

            if ($row === NULL) {
                return "Wrong username or password";
            }

            $login_user = $row["username"];

            // Bắt đầu session
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            // Set session
            $_SESSION['username'] = $login_user;
            $_SESSION['logged_in'] = true;
            $_SESSION['role'] = ($login_user === 'admin') ? 'admin' : 'user';

            return true; // Login thành công

        } catch (mysqli_sql_exception $e) {
            return "Database error: " . $e->getMessage();
        }
    }
}
