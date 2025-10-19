<?php
$pageTitle = 'Contact Us | DespicableEnglish';
$metaDescription = 'Contact DespicableEnglish for course inquiries, enrollment, or support. We’re here to help.';
require_once 'header.php';
?>

    <section id="contact" class="container">
        <h2 data-i18n="contact_us">Contact Us</h2>
        <div class="contact-wrapper">
            <div class="contact-form">
                <h3 data-i18n="send_message">Send us a message</h3>
                <?php
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    require_once 'db_connect.php';
                    $name = htmlspecialchars($_POST['name']);
                    $email = htmlspecialchars($_POST['email']);
                    $message = htmlspecialchars($_POST['message']);

                    if ($conn === null) {
                        echo "<p style='color:red;'>We couldn't save your message due to a temporary server issue. Please email us at <a href='mailto:info@despicableenglish.com'>info@despicableenglish.com</a> or try again later.</p>";
                    } else {
                        $stmt = $conn->prepare("INSERT INTO contacts (name, email, message) VALUES (?, ?, ?)");
                        $stmt->bind_param("sss", $name, $email, $message);

                        if ($stmt->execute()) {
                            echo "<p>Thank you for your message! We will get back to you shortly.</p>";
                        } else {
                            echo "<p>There was an error sending your message. Please try again.</p>";
                        }

                        $stmt->close();
                        $conn->close();
                    }
                } else {
                ?>
                <form action="#" method="post">
                    <input type="text" name="name" placeholder="Your Name" data-i18n-placeholder="ph_name" required>
                    <input type="email" name="email" placeholder="Your Email" data-i18n-placeholder="ph_email" required>
                    <textarea name="message" placeholder="Your Message" data-i18n-placeholder="ph_message" required></textarea>
                    <button type="submit" class="btn" data-i18n="btn_send">Send Message</button>
                </form>
                <?php
                }
                ?>
            </div>
            <div class="contact-info">
                <h3 data-i18n="contact_info">Contact Information</h3>
                <p><i class="fas fa-map-marker-alt"></i> <span data-i18n="our_location">Our Location</span></p>
                <p><i class="fas fa-envelope"></i> <a href="mailto:info@despicableenglish.com">info@despicableenglish.com</a></p>
                <p><i class="fas fa-phone"></i> <a href="tel:+85291234567">+852 91234567</a></p>
                <h3 data-i18n="business_hours">Business Hours</h3>
                <p data-i18n="hours_weekdays">Monday - Friday: 9:00 AM - 6:00 PM</p>
                <p data-i18n="hours_saturday">Saturday: 10:00 AM - 4:00 PM</p>
                <p data-i18n="hours_sunday">Sunday: Closed</p>
            </div>
        </div>
        <div class="map-container">
            <iframe src="https://www.google.com/maps/embed?pb=!4v1752594330555!6m8!1m7!1skr-3LCJSwLpzE8WrdTiX4g!2m2!1d22.44423725201415!2d114.0320792964589!3f14.47275048886851!4f-9.991382616548648!5f0.7820865974627469" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </section>

<?php require_once 'footer.php'; ?>