<?php
$pageTitle = 'Our Tutors | DespicableEnglish';
$metaDescription = 'Meet our qualified English tutors with HKU and UK credentials, dedicated to student success.';
require_once 'header.php';
require_once 'data.php';
?>

    <section id="tutors" class="container">
        <h2 data-i18n="nav_tutors">Our Tutors</h2>
        <div class="tutor-grid">
            <?php foreach ($TUTORS as $tutor): ?>
                <a class="tutor-card" href="tutor.php?slug=<?php echo urlencode($tutor['slug']); ?>">
                    <img src="<?php echo htmlspecialchars($tutor['photo']); ?>" alt="<?php echo htmlspecialchars($tutor['name']); ?>" loading="lazy" width="200" height="200">
                    <h4><?php echo htmlspecialchars($tutor['name']); ?></h4>
                    <p data-i18n="cred_<?php echo htmlspecialchars($tutor['slug']); ?>"><?php echo htmlspecialchars($tutor['credentials']); ?></p>
                </a>
            <?php endforeach; ?>
        </div>
    </section>

<?php require_once 'footer.php'; ?>