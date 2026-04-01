<?php
session_start();
$page_title = "RPM Garage | Your Selection";
include("./inc/config.php");

// --- الجزء الخاص بإضافة منتج للسلة (AJAX/Request) ---
if (isset($_GET['add'])) {
    $product_id = intval($_GET['add']);
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    $_SESSION['cart'][$product_id] = ($_SESSION['cart'][$product_id] ?? 0) + 1;

    header('Content-Type: application/json');
    echo json_encode(['status' => 'success', 'cart_count' => array_sum($_SESSION['cart'])]);
    exit;
}

// --- الجزء الخاص بمسح أو تقليل الكمية ---
if (isset($_GET['remove'])) {
    $product_id = (int)$_GET['remove'];
    $qty_to_remove = isset($_GET['qty']) ? (int)$_GET['qty'] : 0;
    $status = "error";

    if (isset($_SESSION['cart'][$product_id])) {
        $current_qty = $_SESSION['cart'][$product_id];

        if ($qty_to_remove > 0 && $qty_to_remove < $current_qty) {
            $_SESSION['cart'][$product_id] -= $qty_to_remove;
            $status = "updated";
        } else {
            unset($_SESSION['cart'][$product_id]);
            $status = "removed";
        }
    }
    header("Location: cart.php?status=$status");
    exit;
}

include("inc/header.php");
$total = 0;
?>

<link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Barlow+Condensed:ital,wght@0,400;0,700;1,900&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    :root {
        --rpm-red: #ff0000;
        --rpm-black: #080808;
        --rpm-card: #121212;
        --rpm-text: #ffffff;
    }

    body {
        background-color: var(--rpm-black);
        color: var(--rpm-text);
        font-family: 'Barlow Condensed', sans-serif;
        letter-spacing: 1px;
    }

    .cart-section {
        padding: 100px 0;
        background: radial-gradient(circle at top right, #1a0505, transparent);
    }

    .garage-title {
        font-family: 'Orbitron', sans-serif;
        font-weight: 900;
        font-size: 3.5rem;
        text-transform: uppercase;
        color: #fff;
        margin-bottom: 60px;
        position: relative;
    }

    .garage-title span {
        color: var(--rpm-red);
        text-shadow: 0 0 20px rgba(255, 0, 0, 0.5);
    }

    .bike-entry {
        background: var(--rpm-card);
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        position: relative;
        clip-path: polygon(2% 0, 100% 0, 100% 100%, 0 100%, 0 25%);
        transition: 0.3s;
    }

    .bike-entry:hover { background: #181818; transform: scale(1.01); }

    .bike-img-container {
        width: 220px;
        height: 140px;
        background: #000;
    }

    .bike-img-container img {
        width: 100%;
        height: 100%;
        object-fit: contain; /* عشان الموتوسيكل ميبقاش ممطوط */
        filter: grayscale(0.3);
        transition: 0.5s;
    }

    .bike-entry:hover .bike-img-container img { filter: grayscale(0) scale(1.1); }

    .bike-meta { padding: 20px 40px; flex: 1; }
    .bike-name { font-family: 'Orbitron'; font-size: 1.5rem; color: #fff; text-transform: uppercase; }
    .bike-price { color: var(--rpm-red); font-size: 1.2rem; font-weight: 700; }

    .unit-qty {
        font-family: 'Orbitron';
        font-size: 1.5rem;
        padding: 0 30px;
        color: #555;
    }

    .checkout-panel {
        background: #000;
        border: 2px solid #111;
        padding: 40px;
        position: sticky;
        top: 100px;
    }

    .action-btn {
        display: block;
        width: 100%;
        background: var(--rpm-red);
        color: #fff;
        text-align: center;
        padding: 20px;
        text-transform: uppercase;
        font-weight: 900;
        text-decoration: none;
        margin-top: 30px;
        clip-path: polygon(0 0, 100% 0, 95% 100%, 5% 100%);
        transition: 0.3s;
        border: none;
        cursor: pointer;
    }

    .action-btn:hover { background: #fff; color: var(--rpm-red); }

    .empty-state {
        border: 2px dashed #222;
        padding: 80px 0;
        text-align: center;
        background: rgba(255, 0, 0, 0.02);
    }
    .empty-state i { font-size: 4rem; color: #1a1a1a; margin-bottom: 20px; }
</style>

<div class="cart-section">
    <div class="container">
        <h1 class="garage-title">Selected <span>Inventory</span></h1>

        <?php if (empty($_SESSION['cart'])): ?>
            <div class="empty-state">
                <i class="fas fa-motorcycle"></i>
                <h2 class="text-secondary uppercase">Your Garage is Empty</h2>
                <p class="text-muted">NO MACHINES DETECTED IN YOUR CURRENT MANIFEST.</p>
                <a href="index.php" class="action-btn d-inline-block px-5" style="width: auto;">
                     GO TO SHOWROOM <i class="fas fa-chevron-right ms-2"></i>
                </a>
            </div>
        <?php else: ?>
            <div class="row g-5">
                <div class="col-lg-8">
                    <?php
                    foreach ($_SESSION['cart'] as $id => $qty):
                        $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
                        $stmt->execute([$id]);
                        $p = $stmt->fetch();
                        if (!$p) continue;
                        $sub = $p['price'] * $qty;
                        $total += $sub;
                    ?>
                        <div class="bike-entry">
                            <div class="bike-img-container">
                                <img src="uploads/bikes/<?php echo $p['image']; ?>" alt="Unit_Model">
                            </div>
                            <div class="bike-meta">
                                <h3 class="bike-name"><?php echo htmlspecialchars($p['name']); ?></h3>
                                <div class="bike-price">EGP <?php echo number_format($p['price']); ?></div>
                            </div>
                            <div class="unit-qty">x<?php echo $qty; ?></div>
                            <button onclick="confirmRemove(<?php echo $id; ?>, '<?php echo addslashes($p['name']); ?>')" class="remove-trigger" style="background:transparent; border:none; color:#444; padding:0 30px; cursor:pointer;">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="col-lg-4">
                    <div class="checkout-panel">
                        <h4 class="text-white mb-4 uppercase">Summary</h4>
                        <div class="d-flex justify-content-between text-secondary mb-2">
                            <span>Subtotal</span>
                            <span>EGP <?php echo number_format($total); ?></span>
                        </div>
                        <div class="total-display d-flex justify-content-between align-items-center mt-4 pt-4 border-top border-secondary">
                            <span class="uppercase fw-bold">Grand Total</span>
                            <span class="total-val" style="color:var(--rpm-red); font-family:'Orbitron'; font-size: 2rem;">
                                EGP <?php echo number_format($total); ?>
                            </span>
                        </div>

                        <a href="checkout.php" class="action-btn">
                            Confirm Order <i class="fas fa-chevron-right ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
    function confirmRemove(productId, productName) {
        Swal.fire({
            title: 'REMOVE UNIT?',
            text: `Confirm removal of "${productName}" from manifest?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'SCRAP UNIT',
            cancelButtonText: 'KEEP IT',
            confirmButtonColor: '#ff0000',
            background: '#000',
            color: '#fff'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `cart.php?remove=${productId}`;
            }
        });
    }
</script>

<?php include("./inc/footer.php"); ?>