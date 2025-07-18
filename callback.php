<?php
session_start();

require __DIR__ . '/vendor/autoload.php';

$config = require __DIR__ . '/auth_config.php';

$auth0 = new \Auth0\SDK\Auth0([
    'domain' => $config['domain'],
    'clientId' => $config['clientId'],
    'clientSecret' => $config['clientSecret'],
    'cookieSecret' => $config['cookieSecret'],
    'redirectUri' => $config['redirectUri'],
]);

$auth0->exchange();

// Store the user's profile in the session
$_SESSION['user'] = $auth0->getUser();

// Redirect to the homepage
header('Location: /index.php');
exit;

header('Location: /index.php');