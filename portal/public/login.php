<?php
require_once __DIR__ . '/../app/controllers/AuthController.php';

$authController = new AuthController();
$message = $authController->login();

include('../app/views/login.php');
