<?php require_once 'header.php'; ?>

    <section id="feedback" class="container">
        <h2>We Value Your Feedback</h2>
        <?php
        require_once 'db_connect.php';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = htmlspecialchars($_POST['name']);
            $email = htmlspecialchars($_POST['email']);
            $feedback = htmlspecialchars($_POST['feedback']);

            $stmt = $conn->prepare("INSERT INTO feedback (name, email, feedback) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $name, $email, $feedback);

            if ($stmt->execute()) {
                echo "<p>Thank you for your feedback!</p>";
            } else {
                echo "<p>There was an error submitting your feedback. Please try again.</p>";
            }

            $stmt->close();
            $conn->close();
        } else {
        ?>
        <form action="#" method="post">
            <input type="text" name="name" placeholder="Your Name" required>
            <input type="email" name="email" placeholder="Your Email" required>
            <textarea name="feedback" placeholder="Your Feedback" rows="8" required></textarea>
            <button type="submit" class="btn">Submit Feedback</button>
        </form>
        <?php
        }
        ?>
    </section>

<?php require_once 'footer.php'; ?>