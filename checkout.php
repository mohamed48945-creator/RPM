<?php
session_start();
$page_title = "Final Step | Secure Checkout";
include("./inc/config.php");
include("./inc/header.php");

// 1. التحقق من وجود العربة
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])): ?>
    <style>
        @keyframes enginePulse {
            0% { transform: scale(1); opacity: 0.8; }
            50% { transform: scale(1.1); opacity: 1; text-shadow: 0 0 20px #ff0000; }
            100% { transform: scale(1); opacity: 0.8; }
        }
        .empty-garage-lockup { 
            background: #0a0a0a; 
            border: 2px solid #1a1a1a;
            padding: 100px 40px; 
            border-radius: 4px;
            position: relative;
            overflow: hidden;
        }
    </style>
    <div class='container text-center' style='padding:150px 0;'>
        <div class='empty-garage-lockup'>
            <div style="position:relative; z-index:1;">
                <i class='fas fa-motorcycle fa-5x mb-4' style="color:#ff0000; animation: enginePulse 1.5s infinite;"></i>
                <h2 style='color:#fff; font-weight:900; text-transform:uppercase; letter-spacing:5px;'>No Machines Found</h2>
                <a href='index.php' class='btn mt-4' style="background:#ff0000; color:#fff; padding:15px 40px; font-weight:900; clip-path: polygon(10% 0, 100% 0, 90% 100%, 0 100%); text-decoration:none;">RETURN TO SHOWROOM</a>
            </div>
        </div>
    </div>
<?php include("inc/footer.php"); exit; endif;

// 2. تجهيز بيانات المنتجات
$total = 0;
$cart_items = [];
$product_ids = array_keys($_SESSION['cart']);
$placeholders = implode(',', array_fill(0, count($product_ids), '?'));

$stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
$stmt->execute($product_ids);
$products = $stmt->fetchAll();

foreach ($products as $product) {
    $qty = $_SESSION['cart'][$product['id']];
    $item_total = $product['price'] * $qty;
    $total += $item_total;
    $cart_items[] = [
        'id' => $product['id'],
        'name' => $product['name'],
        'price' => $product['price'],
        'quantity' => $qty,
        'subtotal' => $item_total
    ];
}

$discount = $_SESSION['applied_coupon']['discount'] ?? 0;
$total_after_discount = max(0, $total - $discount);

// 3. معالجة إرسال الطلب (التعديل الجوهري هنا)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['place_order'])) {
    try {
        $pdo->beginTransaction();
        
        $name = strip_tags(trim($_POST['name']));
        $phone = strip_tags(trim($_POST['phone']));
        $city = strip_tags(trim($_POST['city']));
        $address = strip_tags(trim($_POST['address']));
        $payment_method = $_POST['payment_method'] ?? 'cod';
        $full_address = $city . " - " . $address;

        // تأكد أن أسماء الأعمدة (customer_name, customer_phone, الخ) موجودة فعلاً في جدول orders عندك
        $sql = "INSERT INTO orders (customer_name, customer_phone, customer_address, total_price, payment_method, status, created_at) 
                VALUES (?, ?, ?, ?, ?, 'pending', NOW())";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$name, $phone, $full_address, $total_after_discount, $payment_method]);
        
        $order_id = $pdo->lastInsertId();

        // إدخال تفاصيل المنتجات في جدول order_items
        $itemStmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        foreach ($cart_items as $item) {
            $itemStmt->execute([$order_id, $item['id'], $item['quantity'], $item['price']]);
        }

        $pdo->commit();
        
        // مسح العربة بعد النجاح
        $_SESSION['cart'] = [];
        unset($_SESSION['applied_coupon']);

        // صفحة النجاح
        echo "<div class='container text-center' style='padding:120px 0;'>
                <div style='background:#000; border:2px solid #ff0000; padding:80px;'>
                    <h1 style='color:#fff; font-family:Orbitron; font-weight:900;'>MISSION ACCOMPLISHED</h1>
                    <p style='color:#ff0000; letter-spacing:3px;'>ORDER #$order_id HAS BEEN DEPLOYED</p>
                    <a href='index.php' class='btn mt-4' style='background:#fff; color:#000; font-weight:bold; padding:10px 30px;'>BACK TO HANGAR</a>
                </div>
              </div>";
        include("inc/footer.php");
        exit;

    } catch (Exception $e) {
        $pdo->rollBack();
        // إظهار الخطأ الحقيقي للمطور (امسح السطر ده بعد ما تخلص برمجة)
        echo "<div class='alert alert-danger'>DATABASE ERROR: " . $e->getMessage() . "</div>";
    }
}
?>

<style>
    :root { --rpm-red: #ff0000; --bg: #050505; }
    body { background: var(--bg); color: #fff; font-family: 'Barlow Condensed', sans-serif; }
    .checkout-card { background: #0c0c0c; border: 1px solid #1a1a1a; padding: 30px; border-radius: 0; }
    .rpm-input { 
        background: #000; border: 1px solid #333; color: #fff; padding: 15px; 
        width: 100%; font-family: 'Orbitron'; font-size: 0.8rem; margin-top: 5px;
    }
    .rpm-input:focus { border-color: var(--rpm-red); outline: none; }
    .summary-sticky { position: sticky; top: 20px; background: #000; border-top: 5px solid var(--rpm-red); padding: 25px; }
    .payment-option { 
        border: 1px solid #222; padding: 20px; margin-bottom: 10px; cursor: pointer; transition: 0.3s;
    }
    .payment-option.active { border-color: var(--rpm-red); background: rgba(255,0,0,0.05); }
    .deploy-btn {
        background: var(--rpm-red); color: #fff; width: 100%; border: none; padding: 20px;
        font-family: 'Orbitron'; font-weight: 900; clip-path: polygon(5% 0, 100% 0, 95% 100%, 0 100%);
    }
</style>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-8">
            <div class="checkout-card">
                <h3 class="mb-4" style="font-family:Orbitron; font-weight:900;"><i class="fas fa-shipping-fast me-2 text-danger"></i> 01. DELIVERY DATA</h3>
                <form method="POST" id="main_checkout_form">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="small text-muted fw-bold">OPERATOR FULL NAME</label>
                            <input type="text" name="name" class="rpm-input" required placeholder="Ex: Mohamed Ahmed">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="small text-muted fw-bold">COMMS (PHONE)</label>
                            <input type="text" name="phone" class="rpm-input" required placeholder="01xxxxxxxxx">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="small text-muted fw-bold">SECTOR (CITY)</label>
                            <input type="text" name="city" class="rpm-input" required placeholder="Cairo">
                        </div>
                        <div class="col-md-12 mb-4">
                            <label class="small text-muted fw-bold">DROP ZONE (ADDRESS)</label>
                            <textarea name="address" class="rpm-input" rows="3" required placeholder="Street, Building, Apartment..."></textarea>
                        </div>
                    </div>

                    <h3 class="mb-4 mt-2" style="font-family:Orbitron; font-weight:900;"><i class="fas fa-wallet me-2 text-danger"></i> 02. PAYMENT</h3>
                    <div class="payment-option active" onclick="selectPay('cod')">
                        <input type="radio" name="payment_method" value="cod" checked class="d-none">
                        <div class="d-flex justify-content-between align-items-center">
                            <span>CASH ON DELIVERY</span>
                            <i class="fas fa-money-bill-wave text-success"></i>
                        </div>
                    </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="summary-sticky">
                <h4 class="fw-bold mb-4" style="font-family:Orbitron;">ORDER SUMMARY</h4>
                <?php foreach ($cart_items as $item): ?>
                <div class="d-flex justify-content-between small mb-2">
                    <span class="text-muted"><?php echo $item['quantity']; ?>x <?php echo $item['name']; ?></span>
                    <span>$<?php echo number_format($item['subtotal']); ?></span>
                </div>
                <?php endforeach; ?>
                
                <hr style="border-color: #333;">
                <div class="d-flex justify-content-between h5 fw-900 mt-3">
                    <span>TOTAL</span>
                    <span class="text-danger">$<?php echo number_format($total_after_discount); ?></span>
                </div>
                
                <button type="submit" name="place_order" class="deploy-btn mt-4">
                    CONFIRM & ORDER <i class="fas fa-bolt ms-2"></i>
                </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function selectPay(val) {
        document.querySelectorAll('.payment-option').forEach(el => el.classList.remove('active'));
        event.currentTarget.classList.add('active');
    }
</script>

<?php include("./inc/footer.php"); ?>