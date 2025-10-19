<?php
$pageTitle = 'Courses | DespicableEnglish';
$metaDescription = 'Explore DSE English, IELTS Preparation, and Junior English courses designed for real results.';
require_once 'header.php';
require_once 'data.php';
?>

    <section id="courses" class="container">
        <h2 data-i18n="nav_courses">Our Courses</h2>
        
        <div class="tutor-list-inline" aria-label="Tutors quick list" style="margin-bottom:16px;">
            <?php foreach ($TUTORS as $tutor): ?>
                <a class="tutor-chip" href="tutor.php?slug=<?php echo urlencode($tutor['slug']); ?>" title="<?php echo htmlspecialchars($tutor['name']); ?>">
                    <?php echo htmlspecialchars($tutor['name']); ?>
                </a>
            <?php endforeach; ?>
        </div>
        
        <div class="course-grid">
            <?php foreach ($COURSE_CATALOG as $course): ?>
                <div class="course-card" id="<?php echo htmlspecialchars($course['id']); ?>">
                    <h3 data-i18n="course_name_<?php echo htmlspecialchars($course['id']); ?>"><?php echo htmlspecialchars($course['name']); ?></h3>
                    <p data-i18n="course_desc_<?php echo htmlspecialchars($course['id']); ?>"><?php echo htmlspecialchars($course['description']); ?></p>
                    
                    <div class="course-tutors">
                        <strong data-i18n="available_tutors">Available tutors:</strong>
                        <div class="course-tutor-list">
                            <?php foreach ($TUTORS as $tutor): if (!in_array($course['id'], $tutor['courses'])) continue; ?>
                                <a class="course-tutor" href="shop.php?course=<?php echo urlencode($course['id']); ?>&tutor=<?php echo urlencode($tutor['slug']); ?>">
                                    <img src="<?php echo htmlspecialchars($tutor['photo']); ?>" alt="<?php echo htmlspecialchars($tutor['name']); ?>" width="48" height="48">
                                    <span><?php echo htmlspecialchars($tutor['name']); ?></span>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <div class="card-actions" style="margin-top:10px;">
                        <a class="btn" href="shop.php?course=<?php echo urlencode($course['id']); ?>" data-i18n="book_now">Book now</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

<?php require_once 'footer.php'; ?>