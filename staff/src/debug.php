<?php
// Only allow access from 127.0.0.1
if ($_SERVER['REMOTE_ADDR'] !== '127.0.0.1' && $_SERVER['REMOTE_ADDR'] !== '::1') {
    http_response_code(403);
    die("Forbidden: Access allowed only from 127.0.0.1");
}

echo "echo
Debug Information

Upload Service Status: RUNNING
Backup Service Status: RUNNING

Last Upload Directory:
/srv/uploads

Upload Daemon PID: 53
";
?>
