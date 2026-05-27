<?php
header('Content-Type: application/json');
$q = $_GET['q'] ?? '';
echo json_encode(['query' => $q, 'results' => []]);
