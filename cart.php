<?php
if (session_status() === PHP_SESSION_NONE) { 
    session_start(); 
}

header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
require_once 'header.php';
require_once 'data.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'add') {
        $id = $_POST['product_id'] ?? '';
        $tutor = $_POST['tutor'] ?? '';
        $return_to = $_POST['return_to'] ?? '';
        
        if ($id) {
            $_SESSION['cart'][] = ['id' => $id, 'tutor' => $tutor, 'qty' => 1];
        }
        
        if ($return_to) {
            $sep = (strpos($return_to, '?') !== false) ? '&' : '?';
            header('Location: ' . $return_to . $sep . 'added=1');
        } else {
            header('Location: cart.php');
        }
        exit;
        
    } elseif ($action === 'update') {
        foreach ($_SESSION['cart'] as $i => $item) {
            $key = 'qty_' . $i;
            if (isset($_POST[$key])) {
                $q = max(1, (int)$_POST[$key]);
                $_SESSION['cart'][$i]['qty'] = $q;
            }
        }
        header('Location: cart.php'); 
        exit;
        
    } elseif ($action === 'remove') {
        $i = (int)($_POST['index'] ?? -1);
        if (isset($_SESSION['cart'][$i])) {
            unset($_SESSION['cart'][$i]);
        }
        header('Location: cart.php'); 
        exit;
        
    } elseif ($action === 'clear') {
        $_SESSION['cart'] = [];
        header('Location: cart.php'); 
        exit;
    }
}

$items = $_SESSION['cart'];
$subtotal = 0;
?>

    <section id="cart" class="container">
        <h2 data-i18n="your_cart">Your Cart</h2>
        
        <?php if (count($items) === 0): ?>
            <p data-i18n="cart_empty">Your cart is empty.</p>
            <p><a class="btn" href="shop.php" data-i18n="go_shop">Go to Shop</a></p>
        <?php else: ?>
            <div class="course-grid">
                <?php foreach ($_SESSION['cart'] as $i => $item): $p = findProductById($item['id'], $PRODUCTS); if (!$p) continue; $line = $p['price'] * $item['qty']; $subtotal += $line; ?>
                    <div class="course-card">
                        <h3><?php echo htmlspecialchars($p['name']); ?></h3>
                        <p><?php echo htmlspecialchars($p['desc']); ?></p>
                        <p><strong><?php echo number_format($p['price']); ?> <?php echo htmlspecialchars($p['currency']); ?></strong></p>
                        
                        <?php if (!empty($item['tutor'])): $t = findTutorBySlug($item['tutor'], $TUTORS); ?>
                            <p><span data-i18n="tutor_label">Tutor:</span> <?php echo htmlspecialchars($t ? $t['name'] : $item['tutor']); ?></p>
                        <?php endif; ?>
                        
                        <div class="line-actions">
                            <label data-i18n="qty">Qty</label>
                            <input type="number" form="cartUpdateForm" name="qty_<?php echo $i; ?>" min="1" value="<?php echo (int)$item['qty']; ?>">
                            <form action="cart.php" method="post" style="margin:0;">
                                <input type="hidden" name="action" value="remove">
                                <input type="hidden" name="index" value="<?php echo (int)$i; ?>">
                                <button class="btn btn-secondary" type="submit" data-i18n="remove">Remove</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <form id="cartUpdateForm" action="#" method="post" style="margin-top:16px;display:flex;gap:10px;align-items:center;">
                <input type="hidden" name="action" value="update">
                <strong><span data-i18n="subtotal">Subtotal:</span> <?php echo number_format($subtotal); ?> HKD</strong>
                <button class="btn" type="submit" data-i18n="update_cart">Update Cart</button>
            </form>
            
            <div class="actions-center" style="margin-top:16px;">
                <?php if (!$isLoggedIn): ?>
                    <p data-i18n="please_login_checkout">Please <a href="login.php" data-i18n="log_in">log in</a> to proceed to checkout.</p>
                <?php else: ?>
                    <a class="btn" href="checkout.php" data-i18n="proceed_checkout">Proceed to Checkout</a>
                <?php endif; ?>
            </div>
            
            <form action="cart.php" method="post" style="margin-top:10px;">
                <input type="hidden" name="action" value="clear">
                <button class="btn btn-secondary" type="submit" data-i18n="remove_all">Remove All</button>
            </form>
        <?php endif; ?>
    </section>

<?php require_once 'footer.php'; ?>

