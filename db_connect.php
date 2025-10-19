<?php
require_once 'config.php';

mysqli_report(MYSQLI_REPORT_OFF);

$conn = null;
$db_error = '';

$mysqli = @new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
if ($mysqli && !$mysqli->connect_errno) {
    $conn = $mysqli;
    $conn->set_charset('utf8mb4');
} else {
    $db_error = $mysqli ? $mysqli->connect_error : 'Connection failed';
    if ($mysqli) {
        @$mysqli->close();
    }
}
