<?php
header('Content-Type: application/json');
// Public user profile endpoint — no auth (Phase 1 JS leak target)
echo json_encode(['id' => 0, 'username' => 'guest', 'role' => 'user']);
