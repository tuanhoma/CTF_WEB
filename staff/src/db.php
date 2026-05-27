<?php
$host = getenv('DB_HOST') ?: 'db';
$name = getenv('DB_NAME') ?: 'corpdb';
$user = getenv('DB_USER') ?: 'appuser';
$pass = getenv('DB_PASS') ?: 'SuperSecret123!';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$name;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    http_response_code(500);
    die('Database connection failed.');
}
