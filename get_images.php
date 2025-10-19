<?php
$dir = __DIR__ . '/rotating';
$files = [];

if (is_dir($dir)) {
    foreach (scandir($dir) as $file) {
        if ($file === '.' || $file === '..') {
            continue;
        }
        if (preg_match('/\.(jpe?g|png|gif|webp)$/i', $file)) {
            $files[] = $file;
        }
    }
    natsort($files);
    $files = array_values($files);
}

header('Content-Type: application/json');
echo json_encode($files);
?>