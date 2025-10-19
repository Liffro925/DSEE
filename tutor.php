<?php
$pageTitle = 'Tutor Profile | DespicableEnglish';
$metaDescription = 'View tutor profile, bio, and courses.';
require_once 'header.php';
require_once 'data.php';

$slug = isset($_GET['slug']) ? $_GET['slug'] : null;
$tutor = $slug ? findTutorBySlug($slug, $TUTORS) : null;

if (!$tutor) {
    http_response_code(404);
    echo '<section class="container"><h2>Tutor not found</h2><p>The tutor you are looking for does not exist.</p></section>';
    require_once 'footer.php';
    exit;
}

$pageTitle = htmlspecialchars($tutor['name']) . ' | Tutor Profile';
$metaDescription = htmlspecialchars($tutor['name']) . ' — ' . htmlspecialchars($tutor['credentials']);
?>

    <section id="tutor-profile" class="container">
        <div class="tutor-header">
            <img src="<?php echo htmlspecialchars($tutor['photo']); ?>" alt="<?php echo htmlspecialchars($tutor['name']); ?>" class="tutor-avatar" width="180" height="180">
            <div class="tutor-info">
                <h2><?php echo htmlspecialchars($tutor['name']); ?></h2>
                <p data-i18n="cred_<?php echo htmlspecialchars($tutor['slug']); ?>"><?php echo htmlspecialchars($tutor['credentials']); ?></p>
                <div class="tutor-contact-cta">
                    <a href="shop.php?tutor=<?php echo urlencode($tutor['slug']); ?>" class="btn" data-i18n="enquire_lessons">Enquire about lessons</a>
                </div>
            </div>
        </div>
        
        <div class="tutor-bio">
            <p data-i18n="bio_<?php echo htmlspecialchars($tutor['slug']); ?>"><?php echo nl2br(htmlspecialchars($tutor['bio'])); ?></p>
        </div>
        
        <div class="tutor-courses">
            <h3 data-i18n="courses_with_<?php echo htmlspecialchars($tutor['slug']); ?>">Courses with <?php echo htmlspecialchars($tutor['name']); ?></h3>
            <div class="course-grid">
                <?php foreach ($tutor['courses'] as $courseId): $course = getCourseById($courseId, $COURSE_CATALOG); if (!$course) continue; ?>
                    <a class="course-card" href="courses.php#<?php echo htmlspecialchars($course['id']); ?>">
                        <h4 data-i18n="course_name_<?php echo htmlspecialchars($course['id']); ?>"><?php echo htmlspecialchars($course['name']); ?></h4>
                        <p data-i18n="course_desc_<?php echo htmlspecialchars($course['id']); ?>"><?php echo htmlspecialchars($course['description']); ?></p>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

<?php require_once 'footer.php'; ?>

