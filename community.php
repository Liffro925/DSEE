<?php require_once 'header.php'; ?>

    <section id="community" class="container">
        <h2>Community Forum</h2>
        <?php if ($session !== null): ?>
            <div class="create-post-container">
                <h3>Create a New Post</h3>
                <?php if (!empty($error_message)):
                    echo '<p style="color: red;">The comment has been rejected. Please do not use harmful or curse words.</p>';
                endif; ?>
                <form action="#" method="post" onkeydown="if(event.keyCode == 13) this.submit();">
                    <input type="text" name="post_title" placeholder="Post Title" required>
                    <textarea name="post_content" placeholder="What are your thoughts?" required></textarea>
                    <button type="submit" class="btn">Submit Post</button>
                </form>
            </div>
        <?php else: ?>
            <p>Please <a href="login.php">log in</a> to create a post.</p>
        <?php endif; ?>
        <div class="forum-container">
            <?php
            require_once 'db_connect.php';
            $error_message = '';

            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_title']) && isset($_POST['post_content'])) {
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
                        $stmt = $conn->prepare("INSERT INTO posts (post_title, post_content, author_name) VALUES (?, ?, ?)");
                        $stmt->bind_param("sss", $post_title, $post_content, $user_name);
                        $stmt->execute();
                        $stmt->close();
                    }
                }
            }

            $sql = "SELECT post_title, post_content, author_name, created_at FROM posts ORDER BY created_at DESC";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<div class='post'>";
                    echo "    <div class='post-author'>";
                    echo "        <img src='https://placehold.co/50x50' alt='User Avatar'>";
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
                echo "<p>No posts yet. Be the first to create one!</p>";
            }
            ?>

        </div>
    </section>

<?php require_once 'footer.php'; ?>