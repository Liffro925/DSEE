<?php
$pageTitle = 'Profile | DespicableEnglish';
$metaDescription = 'View user profile and forum activity on DespicableEnglish.';
require_once 'header.php';
require_once 'db_connect.php';

$profile_user_name = null;
$is_own_profile = false;

if (isset($_GET['user'])) {
    $profile_user_name = $_GET['user'];
    if ($session !== null && $_SESSION['user']['name'] === $profile_user_name) {
        $is_own_profile = true;
    }
} elseif ($session !== null) {
    $profile_user_name = $_SESSION['user']['name'];
    $is_own_profile = true;
} else {
    header('Location: login.php');
    exit;
}

if ($conn !== null) {
    $stmt = $conn->prepare("SELECT post_title, post_content, created_at FROM posts WHERE author_name = ? ORDER BY created_at DESC");
    if ($stmt) {
        $stmt->bind_param("s", $profile_user_name);
        $stmt->execute();
        $result = $stmt->get_result();
        $posts = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
        $total_posts = is_array($posts) ? count($posts) : 0;
        $stmt->close();
    } else {
        $posts = [];
        $total_posts = 0;
    }
} else {
    $posts = [];
    $total_posts = 0;
}

$user_picture = 'https://placehold.co/150x150';
$joined_date = 'Not available';

if ($is_own_profile) {
    $user_picture = htmlspecialchars($_SESSION['user']['picture']);
    $joined_date_raw = $_SESSION['user']['updated_at'] ?? null;
    $joined_date = $joined_date_raw ? date('F Y', strtotime($joined_date_raw)) : 'Not available';
}

if ($conn !== null && $is_own_profile) {
    $email_for_lookup = $_SESSION['user']['email'] ?? null;
    if ($email_for_lookup) {
        try {
            $stmt = $conn->prepare("SELECT picture FROM users WHERE email = ? LIMIT 1");
            if ($stmt) {
                $stmt->bind_param("s", $email_for_lookup);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result && ($row = $result->fetch_assoc())) {
                    if (!empty($row['picture'])) {
                        $user_picture = htmlspecialchars($row['picture']);
                        $_SESSION['user']['picture'] = $row['picture'];
                    }
                }
                $stmt->close();
            }
        } catch (\Exception $e) {
            error_log('Profile DB error: ' . $e->getMessage());
        }
    }
}

if ($is_own_profile && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_avatar'])) {
    $avatar_change_message = '';

    $avatar_url = isset($_POST['avatar_url']) ? trim($_POST['avatar_url']) : '';
    if ($avatar_url !== '' && filter_var($avatar_url, FILTER_VALIDATE_URL)) {
        $_SESSION['user']['picture'] = $avatar_url;
        $user_picture = htmlspecialchars($avatar_url);
        $avatar_change_message = 'Profile icon updated.';
    }

    if (isset($_FILES['avatar_file']) && is_array($_FILES['avatar_file']) && $_FILES['avatar_file']['error'] === UPLOAD_ERR_OK) {
        $tmp = $_FILES['avatar_file']['tmp_name'];
        $size = (int) $_FILES['avatar_file']['size'];
        $maxSize = 2 * 1024 * 1024;
        if ($size > 0 && $size <= $maxSize) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $tmp);
            finfo_close($finfo);
            $allowed = [
                'image/jpeg' => 'jpg',
                'image/png' => 'png',
                'image/webp' => 'webp'
            ];
            if (isset($allowed[$mime])) {
                $ext = $allowed[$mime];
                $avatarsDir = __DIR__ . '/images/avatars';
                if (!is_dir($avatarsDir)) {
                    @mkdir($avatarsDir, 0755, true);
                }
                $filename = 'avatar_' . preg_replace('/[^a-z0-9]/i', '_', $profile_user_name) . '_' . uniqid() . '.' . $ext;
                $dest = $avatarsDir . '/' . $filename;
                if (@move_uploaded_file($tmp, $dest)) {
                    $webPath = 'images/avatars/' . $filename;
                    $_SESSION['user']['picture'] = $webPath;
                    if ($conn !== null && isset($_SESSION['user']['email'])) {
                        $emailUpdate = $_SESSION['user']['email'];
                        $stmt2 = $conn->prepare("UPDATE users SET picture = ? WHERE email = ?");
                        if ($stmt2) {
                            $stmt2->bind_param("ss", $webPath, $emailUpdate);
                            $stmt2->execute();
                            $stmt2->close();
                        }
                    }
                    $user_picture = htmlspecialchars($webPath);
                    $avatar_change_message = 'Profile icon updated.';
                } else {
                    $avatar_change_message = 'Failed to save uploaded image.';
                }
            } else {
                $avatar_change_message = 'Please upload a JPG, PNG, or WEBP image.';
            }
        } else {
            if ($size > $maxSize) {
                $avatar_change_message = 'Image is too large (max 2MB).';
            }
        }
    }
}

?>

<section id="profile" class="container">
    <div class="profile-header">
        <img src="<?php echo $user_picture; ?>" alt="User Avatar" class="profile-avatar">
        <div class="profile-info">
            <h2><?php echo htmlspecialchars($profile_user_name); ?></h2>
            <p>Joined: <?php echo $joined_date; ?></p>
            <p>Total Posts: <?php echo $total_posts; ?></p>
        </div>
    </div>
    <div class="profile-posts">
        <h3><?php echo $is_own_profile ? 'Your Posts' : 'Posts by ' . htmlspecialchars($profile_user_name); ?></h3>
        <?php if ($total_posts > 0): ?>
            <?php foreach ($posts as $post): ?>
                <div class="post">
                    <div class="post-content">
                        <h3><?php echo htmlspecialchars($post['post_title']); ?></h3>
                        <p><?php echo nl2br(htmlspecialchars($post['post_content'])); ?></p>
                        <small>Posted on: <?php echo date('F j, Y, g:i a', strtotime($post['created_at'])); ?></small>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p><?php echo $is_own_profile ? 'You have not made any posts yet.' : 'This user has not made any posts yet.'; ?></p>
        <?php endif; ?>
    </div>

    <?php if ($is_own_profile): ?>
    <div id="change-avatar" class="profile-posts" style="margin-top:20px;">
        <h3>Change Icon</h3>
        <?php if (!empty($avatar_change_message)): ?>
            <p><?php echo htmlspecialchars($avatar_change_message); ?></p>
        <?php endif; ?>
        <form action="#change-avatar" method="post" enctype="multipart/form-data">
            <input type="hidden" name="change_avatar" value="1">
            <label for="avatar_url">Use image URL (e.g., Google profile photo URL)</label>
            <input type="url" id="avatar_url" name="avatar_url" placeholder="https://...">
            <div class="or-divider"><span>or</span></div>
            <label for="avatar_file">Upload image (JPG, PNG, WEBP, max 2MB)</label>
            <input type="file" id="avatar_file" name="avatar_file" accept="image/jpeg,image/png,image/webp">
            <button type="submit" class="btn">Update Icon</button>
        </form>
    </div>
    <?php endif; ?>
</section>

<?php
if ($conn !== null) {
    $conn->close();
}
require_once 'footer.php';
?>