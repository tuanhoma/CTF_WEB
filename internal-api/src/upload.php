<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['file'])) {
        $filename = $_FILES['file']['name'];

        // Task 6: Cho phép upload .htaccess để override Apache config (challenge path)
        $isHtaccess = ($filename === '.htaccess');

        $parts = explode('.', $filename);

        // VULN: Chỉ kiểm tra extension thứ hai (index 1) — phần "visible" mà
        // staff nhìn thấy — chứ không phải extension cuối cùng.
        // → shell.txt.php: $parts[1] = "txt" → pass được filter
        // → Apache AddHandler thực thi file vì vẫn có ".php" trong tên (double extension)
        $checked_ext = strtolower(isset($parts[1]) ? $parts[1] : end($parts));

        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'txt', 'pdf', 'zip'];

        if ($isHtaccess || in_array($checked_ext, $allowed)) {
            $destDir = '/var/www/uploads/internal/';
            if (!is_dir($destDir)) {
                mkdir($destDir, 0777, true);
            }
            $dest = $destDir . $filename;
            if (move_uploaded_file($_FILES['file']['tmp_name'], $dest)) {
                http_response_code(200);
                echo json_encode(['status' => 'success', 'filename' => $filename, 'url' => 'http://cdn.lab.local/uploads/internal/' . $filename]);
                exit;
            } else {
                http_response_code(500);
                echo json_encode(['error' => 'Failed to write file']);
                exit;
            }
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Extension not allowed: ' . htmlspecialchars($checked_ext)]);
            exit;
        }
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'No file uploaded']);
        exit;
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}
