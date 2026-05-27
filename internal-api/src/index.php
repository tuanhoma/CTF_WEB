<?php
header('Content-Type: application/json');
echo json_encode(['service' => 'internal-api', 'status' => 'running']);
