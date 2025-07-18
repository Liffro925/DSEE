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

$session = $auth0->getCredentials();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DespicableEnglish</title>
    <link rel="stylesheet" href="styles.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header>
        <div class="container">
            <img src="images/logo.jpg?v=<?php echo time(); ?>" alt="DespicableEnglish Logo" class="logo">
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="courses.php">Our Courses</a></li>
                    <li><a href="tutors.php">Our Tutors</a></li>
                    <li><a href="community.php">Community</a></li>
                    <li><a href="feedback.php">Feedback</a></li>
                    <li><a href="contact.php">Contact Us</a></li>
                    <?php
                    if ($session === null) {
                        echo '<li class="profile-icon"><a href="login.php"><i class="fa-solid fa-user-circle"></i></a></li>';
                    } else {
                        $user = $_SESSION['user'];
                        echo '<li><a href="profile.php">Profile</a></li>';
                        echo '<li><a href="logout.php">Logout</a></li>';
                    }
                    ?>
                </ul>
            </nav>
        </div>
    </header>