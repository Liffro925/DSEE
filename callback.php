<?php
ini_set('max_execution_time', '15');
ini_set('default_socket_timeout', '10');

session_start();

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
                'timeout' => 10,
                'connect_timeout' => 5,
            ]),
        ]);

        $auth0->exchange();
        $_SESSION['user'] = $auth0->getUser();
        
        if (!empty($_SESSION['user'])) {
            $email = $_SESSION['user']['email'] ?? null;
            $name = $_SESSION['user']['name'] ?? null;
            $picture = $_SESSION['user']['picture'] ?? null;
            
            if (is_string($picture)) {
                $picture = ltrim(trim($picture), '@');
                $_SESSION['user']['picture'] = $picture;
            }
            
            if ($email) {
                try {
                    require_once 'db_connect.php';
                    if ($conn !== null) {
                        $conn->options(MYSQLI_OPT_CONNECT_TIMEOUT, 3);
                        $stmt = $conn->prepare("INSERT INTO users (email, name, picture, provider) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE name = VALUES(name), picture = VALUES(picture), provider = VALUES(provider)");
                        if ($stmt) {
                            $provider = 'auth0';
                            $stmt->bind_param('ssss', $email, $name, $picture, $provider);
                            $stmt->execute();
                            $stmt->close();
                        }
                        $conn->close();
                    }
                } catch (\Exception $dbError) {
                    error_log('DB error in callback: ' . $dbError->getMessage());
                }
            }
        }
        
        header('Location: /index.php');
        exit;
        
    } catch (\Auth0\SDK\Exception\StateException $e) {
        error_log('Auth0 StateException: ' . $e->getMessage());
        $_SESSION['auth_error'] = 'Authentication failed. Please try again.';
        header('Location: /index.php');
        exit;
    } catch (\Exception $e) {
        error_log('Auth0 Exception: ' . $e->getMessage());
        $_SESSION['auth_error'] = 'Authentication error occurred.';
        header('Location: /index.php');
        exit;
    }
}

header('Location: /index.php');
exit;