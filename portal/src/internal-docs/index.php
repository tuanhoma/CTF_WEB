<?php
// internal-docs — Listed in robots.txt
// Contains internal API documentation leaked to attacker via recon
?>
<!DOCTYPE html>
<html>
<head><title>Internal Docs</title></head>
<body>
<h1>Internal API Documentation</h1>
<p><strong>Internal API Base:</strong> http://internal-api/</p>
<h2>Endpoints</h2>
<ul>
    <li>GET /debug.php — System debug info</li>
    <li>GET /health.php — Health check</li>
    <li>GET /export.php?user_id=ID — User data export</li>
    <li>POST /upload.php — File upload</li>
    <li>GET /backup.php — Backup status</li>
</ul>
<p><em>Note: This service is only accessible from within the internal network.</em></p>
</body>
</html>
