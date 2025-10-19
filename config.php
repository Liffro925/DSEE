<?php

$is_local = (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false || 
             strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false);

if ($is_local) {
   
    define('DB_SERVER', 'localhost');
    define('DB_USERNAME', 'root');
    define('DB_PASSWORD', '');
    define('DB_NAME', 'dsee');
} else {
   
    define('DB_SERVER', 'mariadb');
    define('DB_USERNAME', 'ictsba_liffro');
    define('DB_PASSWORD', 'Amcjh2wket6hm]F0');
    define('DB_NAME', 'ictsba_liffro');
}

if (!defined('GOOGLE_CLIENT_ID')) {
    define('GOOGLE_CLIENT_ID', '');
}
?>