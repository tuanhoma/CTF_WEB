<?php
header('Content-Type: application/json');

// VULN: Leaks full internal system info — accessible via SSRF from integration.php
echo json_encode([
    'env'         => 'production',
    'upload_path' => '/var/www/uploads',
    'debug'       => true,
    'db_host'     => getenv('DB_HOST'),
    'db_user'     => getenv('DB_USER'),
    'db_name'     => getenv('DB_NAME'),
    'version'     => 'internal-0.1',
    'php_version' => PHP_VERSION,
    'server_addr' => $_SERVER['SERVER_ADDR'] ?? 'unknown',
    'hostname'    => gethostname(),
    'server'      => $_SERVER,
], JSON_PRETTY_PRINT);
