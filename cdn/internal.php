<?php
require_once __DIR__ . '/auth.php';

$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Trích xuất filename từ đường dẫn /uploads/internal/filename
if (strpos($requestUri, '/uploads/internal/') === 0) {
    $filename = substr($requestUri, strlen('/uploads/internal/'));
    
    if ($filename === '' || $filename === '/') {
        $dirPath = '/var/www/uploads/internal';
        
        echo "<html>\n<head>\n<title>Index of /uploads/internal/</title>\n</head>\n<body>\n";
        echo "<h1>Index of /uploads/internal/</h1>\n";
        echo "<hr>\n<pre>\n";
        
        // Cột tiêu đề
        echo sprintf("%-50s %-20s %-10s\n", "Name", "Last modified", "Size");
        echo "<hr>\n";
        
        if (is_dir($dirPath)) {
            $files = scandir($dirPath);
            foreach ($files as $file) {
                if ($file === '.' || $file === '..' || $file === '.gitkeep') {
                    continue;
                }
                
                $filePath = $dirPath . '/' . $file;
                if (is_file($filePath)) {
                    $mtime = date('Y-m-d H:i:s', filemtime($filePath));
                    $size = filesize($filePath) . " B";
                    
                    // Link tải file
                    echo sprintf("<a href=\"/uploads/internal/%s\">%-50s</a> %-20s %-10s\n", 
                        urlencode($file), 
                        htmlspecialchars($file), 
                        $mtime, 
                        $size
                    );
                }
            }
        }
        
        echo "</pre>\n<hr>\n</body>\n</html>";
        exit;
    } else {
        $filename = basename($filename); // Ngăn chặn Path Traversal
        $filePath = '/var/www/uploads/internal/' . $filename;

        if (file_exists($filePath) && is_file($filePath)) {
            $mimeType = mime_content_type($filePath);
            if (!$mimeType) {
                $mimeType = 'application/octet-stream';
            }
            header('Content-Type: ' . $mimeType);
            readfile($filePath);
            exit;
        } else {
            http_response_code(404);
            exit("404 Not Found");
        }
    }
} else {
    http_response_code(400);
    exit("400 Bad Request");
}
