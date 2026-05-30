<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/db.php';
require_staff();

$result   = '';
$cdn_url  = '';
$http_code = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'fetch') {
        $url = $_POST['url'] ?? '';

        // Auto-convert GitHub blob URL → raw URL
        // Ví dụ: https://github.com/user/repo/blob/main/file.php
        //      → https://raw.githubusercontent.com/user/repo/main/file.php
        if (preg_match('#^https?://github\.com/([^/]+/[^/]+)/blob/(.+)$#', $url, $m)) {
            $url = 'https://raw.githubusercontent.com/' . $m[1] . '/' . $m[2];
        }

        // VULN: No URL validation, no private IP blocking — SSRF
        // Step 1: Tải file từ URL bên ngoài (không kiểm tra IP, scheme, ...)
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_USERAGENT, 'CorpStaff-ImageImporter/1.0');
        // Missing: no restriction on file://, no block on 127.0.0.1, no block on 172.x.x.x
        $file_content = curl_exec($ch);
        $http_code    = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error        = curl_error($ch);
        curl_close($ch);

        if ($error) {
            $result = "cURL Error: $error";
        } elseif ($http_code !== 200 || $file_content === false || strlen($file_content) === 0) {
            $result = "HTTP $http_code\n\nFailed to fetch file from URL or empty response.";
        } else {
            // Step 2: Lấy tên file từ URL (VULN: không sanitize filename)
            $filename = basename(parse_url($url, PHP_URL_PATH));
            if (empty($filename)) {
                $filename = 'imported_image_' . time();
            }

            // Step 3: Ghi file tạm
            $tmpPath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $filename;
            file_put_contents($tmpPath, $file_content);

            // Step 4: SSRF — POST file lên internal-api/upload.php (chỉ accessible từ private-net)
            // VULN: staff container nằm trong private-net nên có thể gọi được internal-api
            $ch2 = curl_init('http://internal-api/upload.php');
            $cfile = new CURLFile($tmpPath, 'application/octet-stream', $filename);
            curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch2, CURLOPT_POST, true);
            curl_setopt($ch2, CURLOPT_POSTFIELDS, ['file' => $cfile]);
            curl_setopt($ch2, CURLOPT_TIMEOUT, 10);
            $upload_response = curl_exec($ch2);
            $upload_code     = curl_getinfo($ch2, CURLINFO_HTTP_CODE);
            $upload_error    = curl_error($ch2);
            curl_close($ch2);

            @unlink($tmpPath);

            $upload_json = json_decode($upload_response, true);

            if ($upload_error) {
                $result = "Upload cURL Error: $upload_error";
            } elseif ($upload_code === 200 && isset($upload_json['url'])) {
                $cdn_url = $upload_json['url'];
                $result  = "✅ File imported successfully!\n\nFilename : {$upload_json['filename']}\nCDN URL  : {$cdn_url}\n\nYou can now access it at:\n{$cdn_url}";
            } else {
                $result = "Upload failed (HTTP $upload_code):\n\n" . $upload_response;
            }
        }

    } elseif ($action === 'webhook') {
        $url = $_POST['webhook_url'] ?? '';

        // VULN: Same SSRF vector
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['event' => 'test', 'source' => 'corp-staff']));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        $response  = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $result = "Webhook sent. HTTP $http_code\n\n" . $response;

    } elseif ($action === 'preview') {
        $url = $_POST['preview_url'] ?? '';

        // VULN: Same SSRF vector
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        $response  = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $result = "Preview HTTP $http_code\n\n" . $response;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Integration Tools — Staff Panel</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background: #f4f6f9; }
        .container { max-width: 900px; margin: 0 auto; padding: 20px; }
        .navbar { display:flex; justify-content:space-between; align-items:center; background:#b71c1c; color:white; padding:12px 20px; border-radius:8px; margin-bottom:20px; }
        .navbar a { color:white; text-decoration:none; margin-left:16px; }
        .card { background:white; border-radius:8px; padding:24px; box-shadow:0 2px 8px rgba(0,0,0,0.1); margin-bottom:16px; }
        h1,h2,h3 { color:#b71c1c; }
        input[type=text] { width:100%; padding:10px; border:1px solid #ddd; border-radius:4px; box-sizing:border-box; margin:8px 0; }
        button { background:#b71c1c; color:white; border:none; padding:10px 20px; border-radius:4px; cursor:pointer; }
        button:hover { background:#c62828; }
        pre { background:#1a1a2e; color:#00ff41; padding:16px; border-radius:8px; overflow-x:auto; white-space:pre-wrap; max-height:400px; overflow-y:auto; font-size:13px; }
    </style>
</head>
<body>
<div class="container">
    <div class="navbar">
        <span>🛡️ Staff Panel</span>
        <div>
            <a href="/index.php">Home</a>
            <a href="/tickets.php">Tickets</a>
            <a href="/integration.php">Integration</a>
            <a href="/export.php">Export</a>
            <a href="/logs.php">Logs</a>
        </div>
    </div>

    <div class="card">
        <h1>🔗 Integration Tools</h1>
        <p style="color:#666">Internal tools for system integration. Restricted to staff only.</p>
    </div>

    <!-- SSRF Vector 1: Import Image from URL -->
    <div class="card">
        <h2>Import Image from URL</h2>
        <p style="color:#888;font-size:13px;">Fetches a file from a remote URL and stores it on the CDN. Supported: jpg, jpeg, png, gif, txt, pdf, zip</p>
        <form method="POST" action="/integration.php">
            <input type="text" name="url" id="import_url" placeholder="http://example.com/image.png" value="<?= htmlspecialchars($_POST['url'] ?? '') ?>">
            <button type="submit" name="action" value="fetch" id="btn_fetch">Import</button>
        </form>
    </div>

    <!-- SSRF Vector 2: Webhook Tester -->
    <div class="card">
        <h2>Webhook Tester</h2>
        <form method="POST" action="/integration.php">
            <input type="text" name="webhook_url" id="webhook_url" placeholder="http://your-server.com/hook" value="<?= htmlspecialchars($_POST['webhook_url'] ?? '') ?>">
            <button type="submit" name="action" value="webhook" id="btn_webhook">Test Webhook</button>
        </form>
    </div>

    <!-- SSRF Vector 3: Preview External Page -->
    <div class="card">
        <h2>Preview External Page</h2>
        <form method="POST" action="/integration.php">
            <input type="text" name="preview_url" id="preview_url" placeholder="http://example.com" value="<?= htmlspecialchars($_POST['preview_url'] ?? '') ?>">
            <button type="submit" name="action" value="preview" id="btn_preview">Preview</button>
        </form>
    </div>

    <?php if ($result !== ''): ?>
    <div class="card">
        <h2>Response</h2>
        <?php if ($cdn_url !== ''): ?>
        <div style="background:#e8f5e9;border:1px solid #4caf50;border-radius:6px;padding:12px;margin-bottom:12px;">
            <strong style="color:#2e7d32;">✅ File available at CDN:</strong><br>
            <a href="<?= htmlspecialchars($cdn_url) ?>" target="_blank" id="cdn_result_link"
               style="color:#1565c0;font-family:monospace;word-break:break-all;"><?= htmlspecialchars($cdn_url) ?></a>
        </div>
        <?php endif; ?>
        <pre id="response_output"><?= htmlspecialchars($result) ?></pre>
    </div>
    <?php endif; ?>
</div>
</body>
</html>
