<?php require_once 'header.php'; ?>

    <section id="contact" class="container">
        <h2>Contact Us</h2>
        <div class="contact-wrapper">
            <div class="contact-form">
                <h3>Send us a message</h3>
                <?php
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    require_once 'db_connect.php';
                    $name = htmlspecialchars($_POST['name']);
                    $email = htmlspecialchars($_POST['email']);
                    $message = htmlspecialchars($_POST['message']);

                    $stmt = $conn->prepare("INSERT INTO contacts (name, email, message) VALUES (?, ?, ?)");
                    $stmt->bind_param("sss", $name, $email, $message);

                    if ($stmt->execute()) {
                        echo "<p>Thank you for your message! We will get back to you shortly.</p>";
                    } else {
                        echo "<p>There was an error sending your message. Please try again.</p>";
                    }

                    $stmt->close();
                    $conn->close();
                } else {
                ?>
                <form action="#" method="post">
                    <input type="text" name="name" placeholder="Your Name" required>
                    <input type="email" name="email" placeholder="Your Email" required>
                    <textarea name="message" placeholder="Your Message" required></textarea>
                    <button type="submit" class="btn">Send Message</button>
                </form>
                <?php
                }
                ?>
            </div>
            <div class="contact-info">
                <h3>Contact Information</h3>
                <p><i class="fas fa-map-marker-alt"></i> Our Location</p>
                <p><i class="fas fa-envelope"></i> <a href="mailto:info@despicableenglish.com">info@despicableenglish.com</a></p>
                <p><i class="fas fa-phone"></i> <a href="tel:+85298050441">+852 98050441</a></p>
                <h3>Business Hours</h3>
                <p>Monday - Friday: 9:00 AM - 6:00 PM</p>
                <p>Saturday: 10:00 AM - 4:00 PM</p>
                <p>Sunday: Closed</p>
            </div>
        </div>
        <div class="map-container">
            <iframe src="https://www.google.com/maps/embed?pb=!4v1752594330555!6m8!1m7!1skr-3LCJSwLpzE8WrdTiX4g!2m2!1d22.44423725201415!2d114.0320792964589!3f14.47275048886851!4f-9.991382616548648!5f0.7820865974627469" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </section>

<?php require_once 'footer.php'; ?>