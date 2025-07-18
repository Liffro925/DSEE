<?php

require __DIR__ . '/vendor/autoload.php';

$config = require __DIR__ . '/auth_config.php';

$auth0 = new \Auth0\SDK\Auth0([
    'domain' => $config['domain'],
    'clientId' => $config['clientId'],
    'clientSecret' => $config['clientSecret'],
    'cookieSecret' => $config['cookieSecret'],
    'redirectUri' => $config['redirectUriForLogout'],
]);

header('Location: ' . $auth0->logout());
