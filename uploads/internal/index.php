<?php
header("Content-Type: text/html; charset=UTF-8");

$dirPath = __DIR__;

echo "<html>\n<head>\n<title>Index of /</title>\n</head>\n<body>\n";
echo "<h1>Index of /</h1>\n";
echo "<hr>\n<pre>\n";

// Hiển thị upload.php
echo "<a href=\"/upload.php\">upload.php</a>\n";
// Hiển thị thư mục files/
echo "<a href=\"/files/\">files/</a>\n";

echo "\n";

// Liệt kê các file đã upload
if (is_dir($dirPath)) {
    $files = scandir($dirPath);
    foreach ($files as $file) {
        if ($file === '.' || $file === '..' || $file === '.gitkeep' || $file === '.htaccess'
            || $file === 'index.php' || $file === 'upload.php') {
            continue;
        }

        $filePath = $dirPath . '/' . $file;
        if (is_file($filePath)) {
            $mtime = date('Y-m-d H:i:s', filemtime($filePath));
            $size  = filesize($filePath);
            echo sprintf("<a href=\"/%s\">%s</a>  %s  %d B\n",
                urlencode($file),
                htmlspecialchars($file),
                $mtime,
                $size
            );
        }
    }
}

echo "</pre>\n<hr>\n</body>\n</html>";
?>
