<?php
$dir = 'rotating';
$files = array_diff(scandir($dir), array('..', '.'));

header('Content-Type: application/json');
echo json_encode(array_values($files));
?>