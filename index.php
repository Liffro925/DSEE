<?php
$pageTitle = 'DespicableEnglish | Home';
$metaDescription = 'Master English with DespicableEnglish: DSE, IELTS, and Junior programs taught by experienced tutors.';
require_once 'header.php';
?>

    <section class="hero">
        <div class="slider-container" role="region" aria-label="Homepage banner">
            <div class="slider" aria-live="polite">
                <noscript>
                    <div class="slide"><img src="images/banner.jpg" alt="English learning banner" width="1200" height="408" loading="eager" fetchpriority="high"></div>
                </noscript>
            </div>
            <div class="hero-overlay"></div>
            <button class="prev" aria-label="Previous slide">&#10094;</button>
            <button class="next" aria-label="Next slide">&#10095;</button>
            <div class="dots-container"></div>
        </div>
        <div class="hero-text">
            <h1 data-i18n="hero_title">Welcome to DespicableEnglish</h1>
            <a href="courses.php" class="btn" data-i18n="explore_courses">Explore Courses</a>
        </div>
    </section>

    <section id="about" class="container">
        <div class="about-wrap">
            <div class="about-col about-text">
                <h2 data-i18n="about_us">About Us</h2>
                <p data-i18n="about_p1">At DespicableEnglish, we combine expert teaching with practical frameworks so students can see progress every week. Our tutors are exam specialists who turn complex skills into clear, repeatable steps.</p>
                <div class="about-features">
                    <div class="about-card">
                        <div class="about-icon"><i class="fa-solid fa-check-circle"></i></div>
                        <div>
                            <h3 data-i18n="proven_methods">Proven Methods</h3>
                            <p data-i18n="proven_p">Task templates, band‑descriptor checklists, and model answers help students practise with purpose.</p>
                        </div>
                    </div>
                    <div class="about-card">
                        <div class="about-icon"><i class="fa-solid fa-pen"></i></div>
                        <div>
                            <h3 data-i18n="personal_feedback">Personal Feedback</h3>
                            <p data-i18n="personal_p">Every piece of writing gets line‑by‑line comments and clear next steps to improve.</p>
                        </div>
                    </div>
                    <div class="about-card">
                        <div class="about-icon"><i class="fa-solid fa-chart-line"></i></div>
                        <div>
                            <h3 data-i18n="weekly_tracking">Weekly Tracking</h3>
                            <p data-i18n="weekly_p">Simple dashboards show growth in grammar, vocabulary, and task achievement.</p>
                        </div>
                    </div>
                </div>
                <div class="about-ctas">
                    <a href="courses.php" class="btn" data-i18n="view_courses">View Courses</a>
                    <a href="contact.php" class="btn btn-secondary" data-i18n="speak_to_us">Speak to Us</a>
                </div>
                <div class="about-stats">
                    <div class="stat"><strong>1,200+</strong><span data-i18n="stat_students">students coached</span></div>
                    <div class="stat"><strong>92%</strong><span data-i18n="stat_target">achieved target</span></div>
                    <div class="stat"><strong>7.0+</strong><span data-i18n="stat_band">avg IELTS band</span></div>
                </div>
            </div>
            <div class="about-col about-media">
                <div class="about-image-card">
                    <img src="images/banner.jpg" alt="Students learning at DespicableEnglish" loading="lazy">
                </div>
            </div>
        </div>
    </section>



    <section id="testimonials" class="container">
        <h2>Feedbacks</h2>
        <div class="testimonials-viewport" role="region" aria-label="Student feedbacks">
            <div class="testimonials-slider">
                <div class="testimonial"><div class="testimonial-card">
                    <p data-i18n="t1_text">"The best English learning center! Tutors actually explain strategies that work. After a month I could see my writing improve and I finally felt confident speaking in class."</p>
                    <span data-i18n="t1_author">- Mr A</span>
                </div></div>
                <div class="testimonial"><div class="testimonial-card">
                    <p data-i18n="t2_text">"I improved significantly thanks to the weekly feedback and mock tests. The lessons are structured, but also fun, and I always know exactly what to practice next."</p>
                    <span data-i18n="t2_author">- Mr B</span>
                </div></div>
                <div class="testimonial"><div class="testimonial-card">
                    <p data-i18n="t3_text">"Got Band 7.5 in IELTS after 8 weeks. Speaking drills and the band-descriptor checklists were game changers—now I know how to hit the criteria every time."</p>
                    <span data-i18n="t3_author">- Mr C</span>
                </div></div>
                <div class="testimonial"><div class="testimonial-card">
                    <p data-i18n="t4_text">"My DSE Paper 2 jumped from Level 3 to 5. The tutor’s templates and line-by-line feedback helped me fix grammar and build stronger arguments."</p>
                    <span data-i18n="t4_author">- Mr D</span>
                </div></div>
                <div class="testimonial"><div class="testimonial-card">
                    <p data-i18n="t5_text">"My daughter now loves English. She reads more, writes weekly journals, and her teacher says her confidence has grown a lot."</p>
                    <span data-i18n="t5_author">- Mr E</span>
                </div></div>
            </div>
            <button class="testimonials-prev" aria-label="Previous testimonial">&#10094;</button>
            <button class="testimonials-next" aria-label="Next testimonial">&#10095;</button>
            <div class="testimonials-dots"></div>
        </div>
    </section>

    <section id="contact" class="container">
        <h2 data-i18n="contact_us">Contact Us</h2>
        <p><span data-i18n="contact_teaser_prefix">If you would like to get in touch, please visit our</span> <a href="contact.php" data-i18n="contact_page_link">Contact Page</a>.</p>
    </section>

<?php require_once 'footer.php'; ?>