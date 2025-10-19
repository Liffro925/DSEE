<?php
$pageTitle = 'Shop | DespicableEnglish';
$metaDescription = 'Book English lessons and packages with our tutors.';
require_once 'header.php';
require_once 'data.php';

$filter_course = $_GET['course'] ?? '';
$filter_tutor = $_GET['tutor'] ?? '';

function productVisible($p, $course, $tutor) {
    if ($course && $p['course'] !== $course) {
        return false;
    }
    
    if ($tutor && !in_array($tutor, $p['tutors'])) {
        return false;
    }
    
    return true;
}
?>

    <section id="shop" class="container">
        <h2 data-i18n="book_lessons">Book Lessons</h2>
        
        <dialog id="addToCartDialog" style="max-width:640px;width:90%;background:var(--elev);color:var(--text);border:1px solid var(--border);border-radius:8px;">
            <h3 style="margin-top:0;" data-i18n="added_to_cart">Added to cart</h3>
            <div style="display:flex;gap:8px;justify-content:flex-end;">
                <a class="btn" href="cart.php" data-i18n="go_to_cart">Go to Cart</a>
                <a class="btn btn-secondary" href="index.php" data-i18n="go_home">Go Home</a>
                <a class="btn btn-secondary" href="shop.php<?php echo $filter_course ? '?course=' . urlencode($filter_course) : ''; ?>" data-i18n="keep_buying">Keep Buying</a>
            </div>
        </dialog>
        
        <form action="#" method="get" style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:12px;">
            <select name="course">
                <option value="">All Courses</option>
                <?php foreach ($COURSE_CATALOG as $c): ?>
                    <option value="<?php echo htmlspecialchars($c['id']); ?>"<?php echo $filter_course === $c['id'] ? ' selected' : ''; ?>><?php echo htmlspecialchars($c['name']); ?></option>
                <?php endforeach; ?>
            </select>
            <select name="tutor">
                <option value="">All Tutors</option>
                <?php foreach ($TUTORS as $t): ?>
                    <option value="<?php echo htmlspecialchars($t['slug']); ?>"<?php echo $filter_tutor === $t['slug'] ? ' selected' : ''; ?>><?php echo htmlspecialchars($t['name']); ?></option>
                <?php endforeach; ?>
            </select>
            <button class="btn" type="submit" data-i18n="filter">Filter</button>
        </form>
        
        <div class="course-grid">
            <?php foreach ($PRODUCTS as $p): if (!productVisible($p, $filter_course, $filter_tutor)) continue; ?>
                <div class="course-card">
                    <h3 data-i18n="product_name_<?php echo htmlspecialchars($p['id']); ?>"><?php echo htmlspecialchars($p['name']); ?></h3>
                    <p data-i18n="product_desc_<?php echo htmlspecialchars($p['id']); ?>"><?php echo htmlspecialchars($p['desc']); ?></p>
                    <p><strong><span data-i18n="price_<?php echo htmlspecialchars($p['id']); ?>"><?php echo number_format($p['price']); ?> <?php echo htmlspecialchars($p['currency']); ?></span></strong></p>
                    
                    <div class="card-actions">
                        <form action="cart.php" method="post" class="add-to-cart-form">
                            <input type="hidden" name="action" value="add">
                            <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($p['id']); ?>">
                            
                            <?php if (count($p['tutors']) > 0): ?>
                                <select name="tutor" required>
                                    <option value="" data-i18n="choose_tutor">Choose Tutor</option>
                                    <?php foreach ($p['tutors'] as $slug): $t = findTutorBySlug($slug, $TUTORS); if (!$t) continue; ?>
                                        <option value="<?php echo htmlspecialchars($slug); ?>"><?php echo htmlspecialchars($t['name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            <?php endif; ?>
                            
                            <button class="btn" type="submit" data-i18n="add_to_cart">Add to Cart</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

<?php require_once 'footer.php'; ?>

