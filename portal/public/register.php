<?php
require_once __DIR__ . '/../app/controllers/AuthController.php';

$authController = new AuthController();
$message = $authController->register();

include('../app/views/register.php');
