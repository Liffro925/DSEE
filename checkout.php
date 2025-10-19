<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
$pageTitle = 'Checkout | DespicableEnglish';
$metaDescription = 'Enter your details to complete your booking.';
require_once 'header.php';
require_once 'data.php';

if (!isset($_SESSION['cart']) || count($_SESSION['cart'])===0) {
    echo '<section class="container"><p data-i18n="cart_empty">Your cart is empty.</p><p><a class="btn" href="shop.php" data-i18n="go_shop">Go to Shop</a></p></section>';
    require_once 'footer.php';
    exit;
}

if (!isset($_SESSION['user'])) {
    echo '<section class="container"><p data-i18n="please_login_checkout">Please <a href="login.php" data-i18n="log_in">log in</a> to continue to checkout.</p></section>';
    require_once 'footer.php';
    exit;
}

$errors = [];
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $fullName = trim($_POST['fullName'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $card = preg_replace('/\s+/', '', $_POST['card'] ?? '');
    $exp = trim($_POST['exp'] ?? '');
    $cvc = trim($_POST['cvc'] ?? '');
    if ($fullName==='') $errors[] = ['key'=>'error_full_name_required','text'=>'Full Name is required'];
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = ['key'=>'error_valid_email','text'=>'Valid email is required'];
    if (!preg_match('/^\d{13,19}$/', $card)) $errors[] = ['key'=>'error_card_digits','text'=>'Card number must be 13-19 digits'];
    if (!preg_match('/^(0[1-9]|1[0-2])\/(\d{2})$/', $exp)) $errors[] = ['key'=>'error_exp_format','text'=>'Expiry must be MM/YY'];
    if (!preg_match('/^\d{3,4}$/', $cvc)) $errors[] = ['key'=>'error_cvc_digits','text'=>'CVC must be 3-4 digits'];

    if (empty($errors)) {
        $_SESSION['cart'] = [];
        echo '<section class="container"><h2>Thank you!</h2><p>Your purchase has been completed.</p><p><a class="btn" href="index.php" data-i18n="go_home">Back to Home</a></p></section>';
        require_once 'footer.php';
        exit;
    }
}

?>

    <section class="container">
        <h2 data-i18n="checkout">Checkout</h2>
        <?php if (!empty($errors)): ?>
            <div class="notice" style="color:#ff8a8a;">
                <ul>
                    <?php foreach ($errors as $e): ?>
                        <?php if (is_array($e)): ?>
                            <li data-i18n="<?php echo htmlspecialchars($e['key']); ?>"><?php echo htmlspecialchars($e['text']); ?></li>
                        <?php else: ?>
                            <li><?php echo htmlspecialchars($e); ?></li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        <div id="clientErrors" class="notice" style="color:#ff8a8a;display:none;"><ul></ul></div>
        <form action="#" method="post">
            <input type="text" name="fullName" placeholder="Full Name" data-i18n-placeholder="full_name" required>
            <input type="email" name="email" placeholder="Email" data-i18n-placeholder="email" required>
            <input type="text" name="card" placeholder="Card Number" data-i18n-placeholder="card_number" inputmode="numeric" required>
            <div style="display:flex;gap:10px;">
                <input type="text" name="exp" placeholder="MM/YY" data-i18n-placeholder="mm_yy" inputmode="numeric" required>
                <input type="text" name="cvc" placeholder="CVC" data-i18n-placeholder="cvc" inputmode="numeric" required>
            </div>
            <button class="btn" type="submit" data-i18n="pay_now">Pay Now</button>
        </form>
        <script>
        (function(){
            var form = document.querySelector('section.container form');
            if(!form) return;
            var errBox = document.getElementById('clientErrors');
            var ul = errBox ? errBox.querySelector('ul') : null;
            form.addEventListener('submit', function(e){
                if(!ul) return;
                ul.innerHTML='';
                var errs=[];
                var fullName=form.querySelector('input[name="fullName"]');
                var email=form.querySelector('input[name="email"]');
                var card=form.querySelector('input[name="card"]');
                var exp=form.querySelector('input[name="exp"]');
                var cvc=form.querySelector('input[name="cvc"]');
                [fullName,email,card,exp,cvc].forEach(function(i){ if(i) i.style.borderColor=''; });
                if(fullName && !fullName.value.trim()) { errs.push({k:'error_full_name_required', t:'Full Name is required', el:fullName}); }
                var ev = email ? email.value.trim() : '';
                if(!ev) { errs.push({k:'error_valid_email', t:'Valid email is required', el:email}); }
                else { var re=/^[^\s@]+@[^\s@]+\.[^\s@]+$/; if(!re.test(ev)) errs.push({k:'error_valid_email', t:'Valid email is required', el:email}); }
                var cv = card ? card.value.replace(/\s+/g,'') : '';
                if(!/^\d{13,19}$/.test(cv)) { errs.push({k:'error_card_digits', t:'Card number must be 13-19 digits', el:card}); }
                var xv = exp ? exp.value.trim() : '';
                if(!/^(0[1-9]|1[0-2])\/(\d{2})$/.test(xv)) { errs.push({k:'error_exp_format', t:'Expiry must be MM/YY', el:exp}); }
                var cc = cvc ? cvc.value.trim() : '';
                if(!/^\d{3,4}$/.test(cc)) { errs.push({k:'error_cvc_digits', t:'CVC must be 3-4 digits', el:cvc}); }
                if(errs.length){
                    e.preventDefault();
                    errs.forEach(function(er){ var li=document.createElement('li'); li.setAttribute('data-i18n', er.k); li.textContent=er.t; ul.appendChild(li); if(er.el) er.el.style.borderColor='#EF4444'; });
                    errBox.style.display='block';
                    errBox.scrollIntoView({behavior:'smooth'});
                }
            });
        })();
        </script>
    </section>

<?php require_once 'footer.php'; ?>

