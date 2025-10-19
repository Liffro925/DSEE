<?php
ini_set('max_execution_time', '10');
ini_set('default_socket_timeout', '10');

session_start();
$_SESSION = [];
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
}
session_destroy();

$vendorAutoload = __DIR__ . '/vendor/autoload.php';
$authConfigPath = __DIR__ . '/auth_config.php';

if (file_exists($vendorAutoload) && file_exists($authConfigPath)) {
    try {
        require $vendorAutoload;
        $config = require $authConfigPath;
        $auth0 = new \Auth0\SDK\Auth0([
            'domain' => $config['domain'],
            'clientId' => $config['clientId'],
            'clientSecret' => $config['clientSecret'],
            'cookieSecret' => $config['cookieSecret'],
            'redirectUri' => $config['redirectUriForLogout'],
            'httpClient' => new \GuzzleHttp\Client([
                'timeout' => 8,
                'connect_timeout' => 4,
            ]),
        ]);
        header('Location: ' . $auth0->logout());
        exit;
    } catch (\Exception $e) {
        error_log('Logout error: ' . $e->getMessage());
        header('Location: /index.php');
        exit;
    }
} else {
    header('Location: /index.php');
    exit;
}
