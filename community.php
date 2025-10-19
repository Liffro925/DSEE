<?php
$pageTitle = 'Community Forum | DespicableEnglish';
$metaDescription = 'Join the DespicableEnglish community forum to discuss English learning tips and share progress.';
require_once 'header.php';

$error_message = '';
$success_message = '';
$user_logged_in = ($session !== null) || ($isLoggedIn && isset($_SESSION['user']['name']));

if (!$user_logged_in && isset($_SESSION['user'])) {
    error_log('Community: User session exists but user_logged_in is false. Session: ' . print_r($_SESSION['user'], true));
}

if ($user_logged_in && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_title']) && isset($_POST['post_content'])) {
    require_once 'config.php';
    $mysqli_post = new mysqli();
    $mysqli_post->options(MYSQLI_OPT_CONNECT_TIMEOUT, 3);
    $mysqli_post->real_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    
    if ($mysqli_post->connect_errno) {
        $error_message = 'Database connection failed. Please try again later.';
    } else {
        $post_cool_down = 60;
        if (isset($_SESSION['last_post_time']) && (time() - $_SESSION['last_post_time']) < $post_cool_down) {
            $error_message = 'Please wait ' . ($post_cool_down - (time() - $_SESSION['last_post_time'])) . ' seconds before posting again.';
        } else {
            $post_title = htmlspecialchars($_POST['post_title']);
            $post_content = htmlspecialchars($_POST['post_content']);
            $forbidden_words = ['badword', 'curseword', 'harmful'];
            $contains_forbidden_word = false;
            
            foreach ($forbidden_words as $word) {
                if (stripos($post_title, $word) !== false || stripos($post_content, $word) !== false) {
                    $contains_forbidden_word = true;
                    break;
                }
            }
            
            if ($contains_forbidden_word) {
                $error_message = 'Your post contains inappropriate language and cannot be submitted.';
            } else {
                $_SESSION['last_post_time'] = time();
                $user_name = $_SESSION['user']['name'];
                $stmt = $mysqli_post->prepare("INSERT INTO posts (post_title, post_content, author_name) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $post_title, $post_content, $user_name);
                
                if ($stmt->execute()) {
                    $success_message = 'Post created successfully!';
                } else {
                    $error_message = 'Failed to create post. Please try again.';
                }
                $stmt->close();
            }
        }
        $mysqli_post->close();
    }
}
?>

    <section id="community" class="container">
        <h2 data-i18n="community_forum">Community Forum</h2>
        
        <?php if (isset($_GET['debug'])): ?>
            <div style="background-color:#2196F3;color:white;padding:10px;margin-bottom:10px;border-radius:5px;font-size:12px;">
                Debug Info: 
                User Logged In: <?php echo $user_logged_in ? 'YES' : 'NO'; ?> | 
                Session exists: <?php echo $session !== null ? 'YES' : 'NO'; ?> | 
                isLoggedIn: <?php echo $isLoggedIn ? 'YES' : 'NO'; ?> | 
                Has user name: <?php echo isset($_SESSION['user']['name']) ? 'YES (' . htmlspecialchars($_SESSION['user']['name']) . ')' : 'NO'; ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($error_message)): ?>
            <div style="background-color:#f44336;color:white;padding:15px;margin-bottom:20px;border-radius:5px;">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($success_message)): ?>
            <div style="background-color:#4CAF50;color:white;padding:15px;margin-bottom:20px;border-radius:5px;">
                <?php echo htmlspecialchars($success_message); ?>
            </div>
        <?php endif; ?>
        
        <?php if ($user_logged_in): ?>
            <div class="create-post-container">
                <h3 data-i18n="create_post">Create a New Post</h3>
                <form action="community.php" method="post">
                    <input type="text" name="post_title" placeholder="Post Title" data-i18n-placeholder="ph_post_title" required>
                    <textarea name="post_content" placeholder="What are your thoughts?" data-i18n-placeholder="ph_post_content" required></textarea>
                    <button type="submit" class="btn" data-i18n="btn_submit_post">Submit Post</button>
                </form>
            </div>
        <?php else: ?>
            <p data-i18n="please_login_create_post">Please log in to create a post.</p>
        <?php endif; ?>
        
        <div class="forum-container">
            <?php
            require_once 'db_connect.php';

            if ($conn === null) {
                echo '<p style="color:red;">Forum is temporarily unavailable (database connection failed). Please try again later.</p>';
            } else {
                $sql = "SELECT p.post_title, p.post_content, p.author_name, p.created_at, u.picture 
                        FROM posts p 
                        LEFT JOIN users u ON p.author_name = u.name 
                        ORDER BY p.created_at DESC";
                $result = $conn->query($sql);

                if ($result && $result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        $author_picture = 'https://placehold.co/50x50';
                        if (!empty($row['picture'])) {
                            $author_picture = htmlspecialchars($row['picture']);
                        }
                        
                        echo "<div class='post'>";
                        echo "    <div class='post-author'>";
                        echo "        <img src='" . $author_picture . "' alt='User Avatar' style='width:50px;height:50px;border-radius:50%;object-fit:cover;'>";
                        echo "        <a href='profile.php?user=" . htmlspecialchars($row['author_name']) . "'>" . htmlspecialchars($row['author_name']) . "</a>";
                        echo "    </div>";
                        echo "    <div class='post-content'>";
                        echo "        <h3>" . htmlspecialchars($row['post_title']) . "</h3>";
                        echo "        <p>" . htmlspecialchars($row['post_content']) . "</p>
                            <div class='post-meta'>
                                <span><i class='fas fa-arrow-up'></i> 0</span>
                                <span><i class='fas fa-arrow-down'></i> 0</span>
                                <span><i class='fas fa-comment'></i> 0 Comments</span>
                                <span>Posted on " . date('F j, Y, g:i a', strtotime($row['created_at'])) . "</span>
                            </div>";
                        echo "    </div>";
                        echo "</div>";
                    }
                } else {
                    echo "<p class='empty-state' data-i18n='forum_empty'>No posts yet. Be the first to create one!</p>";
                }
                if ($conn !== null) {
                    $conn->close();
                }
            }
            ?>

        </div>
    </section>

<?php require_once 'footer.php'; ?>