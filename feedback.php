<?php
$pageTitle = 'Feedback | DespicableEnglish';
$metaDescription = 'Send feedback to help us improve our English courses and student experience.';
require_once 'header.php';
?>

    <section id="feedback" class="container">
        <h2 data-i18n="we_value_feedback">We Value Your Feedback</h2>
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once 'db_connect.php';
            $name = htmlspecialchars($_POST['name']);
            $email = htmlspecialchars($_POST['email']);
            $feedback = htmlspecialchars($_POST['feedback']);

            if ($conn !== null) {
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
                echo "<p style='color:red;'>Unable to submit feedback at this time. Please try again later.</p>";
            }
        } else {
        ?>
        <form action="#" method="post">
            <input type="text" name="name" placeholder="Your Name" data-i18n-placeholder="ph_your_name" required>
            <input type="email" name="email" placeholder="Your Email" data-i18n-placeholder="ph_your_email" required>
            <textarea name="feedback" placeholder="Your Feedback" data-i18n-placeholder="ph_your_feedback" rows="8" required></textarea>
            <button type="submit" class="btn" data-i18n="btn_submit_feedback">Submit Feedback</button>
        </form>
        <?php
        }
        ?>
    </section>

<?php require_once 'footer.php'; ?>