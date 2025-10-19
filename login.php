<?php
ini_set('max_execution_time', '10');
ini_set('default_socket_timeout', '10');

$vendorAutoload = __DIR__ . '/vendor/autoload.php';
$authConfigPath = __DIR__ . '/auth_config.php';

if (file_exists($vendorAutoload) && file_exists($authConfigPath)) {
    require $vendorAutoload;
    $config = require $authConfigPath;
    try {
        $auth0 = new \Auth0\SDK\Auth0([
            'domain' => $config['domain'],
            'clientId' => $config['clientId'],
            'clientSecret' => $config['clientSecret'],
            'cookieSecret' => $config['cookieSecret'],
            'redirectUri' => $config['redirectUri'],
            'httpClient' => new \GuzzleHttp\Client([
                'timeout' => 8,
                'connect_timeout' => 4,
            ]),
        ]);
        $loginUrl = $auth0->login();
        header('Location: ' . $loginUrl);
        exit;
    } catch (\Exception $e) {
        error_log('Login error: ' . $e->getMessage());
        die('Auth0 Error: Unable to initiate login. Please try again later.');
    }
} else {
    die('Auth0 configuration not found. Missing: ' . 
        (!file_exists($vendorAutoload) ? 'vendor/autoload.php ' : '') .
        (!file_exists($authConfigPath) ? 'auth_config.php' : ''));
}