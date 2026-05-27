<?php
header('Content-Type: application/json');
echo json_encode(['service' => 'corp-api', 'version' => 'v2', 'status' => 'running']);
