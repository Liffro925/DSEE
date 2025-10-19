<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$authEnabled = false;
$session = null;

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
        ]);
        $session = $auth0->getCredentials();
        $authEnabled = true;
    } catch (\Throwable $e) {
        $session = null;
        $authEnabled = false;
    }
}

$isLoggedIn = ($session !== null) || (isset($_SESSION['user']) && is_array($_SESSION['user']));

$currentPage = basename($_SERVER['PHP_SELF']);
$siteName = 'DespicableEnglish';
if (!isset($pageTitle) || $pageTitle === '') {
    $pageTitle = $siteName;
}
if (!isset($metaDescription) || $metaDescription === '') {
    $metaDescription = 'Top-quality English tutoring: DSE English, IELTS prep, and Junior English with experienced tutors.';
}
$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$canonicalUrl = $scheme . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$ogImage = 'images/logo.jpg';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle, ENT_QUOTES); ?></title>
    <link rel="icon" href="images/logo.jpg">
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    <meta name="description" content="<?php echo htmlspecialchars($metaDescription, ENT_QUOTES); ?>">
    <link rel="canonical" href="<?php echo htmlspecialchars($canonicalUrl, ENT_QUOTES); ?>">
    <?php if ($currentPage === 'index.php') { echo '<link rel="preload" as="image" href="images/banner.jpg">'; } ?>
    <meta property="og:title" content="<?php echo htmlspecialchars($pageTitle, ENT_QUOTES); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($metaDescription, ENT_QUOTES); ?>">
    <meta property="og:image" content="<?php echo htmlspecialchars($ogImage, ENT_QUOTES); ?>">
    <meta property="og:url" content="<?php echo htmlspecialchars($canonicalUrl, ENT_QUOTES); ?>">
    <meta property="og:type" content="website">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo htmlspecialchars($pageTitle, ENT_QUOTES); ?>">
    <meta name="twitter:description" content="<?php echo htmlspecialchars($metaDescription, ENT_QUOTES); ?>">
    <meta name="twitter:image" content="<?php echo htmlspecialchars($ogImage, ENT_QUOTES); ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <?php if (defined('GOOGLE_CLIENT_ID') && GOOGLE_CLIENT_ID !== ''): ?>
    <script src="https://accounts.google.com/gsi/client" async defer></script>
    <script>
        window.handleCredentialResponse = function(response){
            try {
                const payload = JSON.parse(atob(response.credential.split('.')[1]));
                if (payload && payload.picture) {
                    const el = document.querySelector('.profile-icon img');
                    if (el) { el.src = payload.picture; }
                }
            } catch(e) { }
        };
    </script>
    <?php endif; ?>
    
</head>
<body>
    <header>
        <div class="container">
            <img src="images/logo.jpg?v=<?php echo time(); ?>" alt="DespicableEnglish Logo" class="logo" width="100" height="100" decoding="async" fetchpriority="high">
            <button class="mobile-menu-toggle" aria-label="Toggle mobile menu">
                <i class="fa-solid fa-bars"></i>
            </button>
            <nav>
                <ul>
                    <li><a href="index.php"<?php echo $currentPage === 'index.php' ? ' class="active" aria-current="page"' : ''; ?> data-i18n="home">Home</a></li>
                    <li><a href="courses.php"<?php echo $currentPage === 'courses.php' ? ' class="active" aria-current="page"' : ''; ?> data-i18n="courses">Our Courses</a></li>
                    <li><a href="tutors.php"<?php echo $currentPage === 'tutors.php' ? ' class="active" aria-current="page"' : ''; ?> data-i18n="tutors">Our Tutors</a></li>
                    <li><a href="community.php"<?php echo $currentPage === 'community.php' ? ' class="active" aria-current="page"' : ''; ?> data-i18n="community">Community</a></li>
                    <li><a href="feedback.php"<?php echo $currentPage === 'feedback.php' ? ' class="active" aria-current="page"' : ''; ?> data-i18n="feedback">Feedback</a></li>
                    <li><a href="contact.php"<?php echo $currentPage === 'contact.php' ? ' class="active" aria-current="page"' : ''; ?> data-i18n="contact">Contact Us</a></li>
                    <?php $cartCount = 0; if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) { foreach ($_SESSION['cart'] as $ci) { $cartCount += (int)($ci['qty'] ?? 1); } } ?>
                    <li class="profile-icon">
                        <a href="cart.php" title="Cart"<?php echo $currentPage === 'cart.php' ? ' class="active" aria-current="page"' : ''; ?>><i class="fa-solid fa-cart-shopping"></i></a><?php echo $cartCount>0?'<span style="font-size:0.8rem;margin-left:6px;">'.$cartCount.'</span>':''; ?>
                    </li>
                    <?php
                    if (!$isLoggedIn) {
                        echo '<li class="profile-icon">';
                        if (defined('GOOGLE_CLIENT_ID') && GOOGLE_CLIENT_ID !== '') {
                            echo '<div id="g_id_onload" data-client_id="' . htmlspecialchars(GOOGLE_CLIENT_ID, ENT_QUOTES) . '" data-context="signin" data-ux_mode="popup" data-callback="handleCredentialResponse" data-auto_prompt="false"></div>';
                            echo '<div class="g_id_signin" data-type="icon" data-size="large" data-theme="outline" data-shape="circle"></div>';
                        } else {
                            echo '<a href="login.php" title="Log in"><i class="fa-solid fa-user-circle"></i></a>';
                        }
                        echo '</li>';
                        echo '<li id="language-switcher" style="margin-left: 12px;"><select id="siteLang" aria-label="Site language" style="padding:6px 8px;border-radius:6px;"><option value="en">English</option><option value="zh">中文 (简)</option><option value="zh-TW">中文 (繁)</option></select></li>';
                    } else {
                        $user = $_SESSION['user'] ?? [];
                        $avatar = '';
                        if (!empty($user['picture'])) {
                            $raw = trim($user['picture']);
                            if (strlen($raw) > 0 && $raw[0] === '@') { $raw = substr($raw, 1); }
                            $avatar = htmlspecialchars($raw, ENT_QUOTES);
                        }
                        if ($avatar) {
                            echo '<li class="profile-icon"><a href="profile.php" title="Profile"><img src="' . $avatar . '" alt="User" class="profile-picture" /></a></li>';
                        } else {
                            echo '<li class="profile-icon"><a href="profile.php" title="Profile"><i class="fa-solid fa-user-circle"></i></a></li>';
                        }
                        echo '<li><a href="logout.php" data-i18n="logout">Logout</a></li>';
                        echo '<li id="language-switcher" style="margin-left: 12px;"><select id="siteLang" aria-label="Site language" style="padding:6px 8px;border-radius:6px;"><option value="en">English</option><option value="zh">中文 (简)</option><option value="zh-TW">中文 (繁)</option></select></li>';
                    }
                    ?>
                </ul>
            </nav>
        </div>
    </header>
    <div class="mobile-overlay"></div>
    <?php
    if (isset($_SESSION['auth_error'])) {
        echo '<div style="background-color:#f44336;color:white;padding:15px;text-align:center;margin:0;">';
        echo htmlspecialchars($_SESSION['auth_error']);
        echo '</div>';
        unset($_SESSION['auth_error']);
    }
    ?>