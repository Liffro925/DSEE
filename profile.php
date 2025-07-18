<?php
require_once 'header.php';
require_once 'db_connect.php';

$profile_user_name = null;
$is_own_profile = false;

// Determine which user's profile to display
if (isset($_GET['user'])) {
    $profile_user_name = $_GET['user'];
    if ($session !== null && $_SESSION['user']['name'] === $profile_user_name) {
        $is_own_profile = true;
    }
} elseif ($session !== null) {
    $profile_user_name = $_SESSION['user']['name'];
    $is_own_profile = true;
} else {
    // No user specified and not logged in, redirect to login
    header('Location: login.php');
    exit;
}

// Fetch user posts
$stmt = $conn->prepare("SELECT post_title, post_content, created_at FROM posts WHERE author_name = ? ORDER BY created_at DESC");
$stmt->bind_param("s", $profile_user_name);
$stmt->execute();
$result = $stmt->get_result();
$posts = $result->fetch_all(MYSQLI_ASSOC);
$total_posts = count($posts);
$stmt->close();

// For simplicity, we'll use a placeholder for avatar and join date for other users.
// In a real application, you would fetch this from a users table.
$user_picture = 'https://placehold.co/150x150';
$joined_date = 'Not available';

if ($is_own_profile) {
    $user_picture = htmlspecialchars($_SESSION['user']['picture']);
    $joined_date_raw = $_SESSION['user']['updated_at'] ?? null;
    $joined_date = $joined_date_raw ? date('F Y', strtotime($joined_date_raw)) : 'Not available';
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
</section>

<?php
$conn->close();
require_once 'footer.php';
?>