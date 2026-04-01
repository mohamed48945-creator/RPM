<?php
session_start();
$page_title = "Mission Brief | Order Details";
include("./inc/config.php");
include("./inc/header.php");

$order_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$order_id) {
    echo "<div class='container mt-5 text-center'><h3 style='color:#fff;'>INVALID_IDENTIFIER</h3><a href='index.php' class='btn-store' style='max-width:200px; margin:20px auto;'>Back to Base</a></div>";
    include("inc/footer.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->execute([$order_id]);
$order = $stmt->fetch();

if (!$order) {
    echo "<div class='container mt-5 text-center'><h3 style='color:#fff;'>DATA_NOT_FOUND: #$order_id</h3></div>";
    include("inc/footer.php");
    exit;
}

$stmt = $pdo->prepare("
    SELECT oi.*, p.name 
    FROM order_items oi
    JOIN products p ON p.id = oi.product_id
    WHERE oi.order_id = ?
");
$stmt->execute([$order_id]);
$items = $stmt->fetchAll();

$status = $order['status'] ?? 'Pending';
$statusMap = [
    "Pending"   => ["color" => "#ff0000", "bg" => "rgba(255, 0, 0, 0.1)", "icon" => "fa-bolt"],
    "Completed" => ["color" => "#00ff00", "bg" => "rgba(0, 255, 0, 0.1)", "icon" => "fa-check-double"],
    "Cancelled" => ["color" => "#444", "bg" => "rgba(255, 255, 255, 0.05)", "icon" => "fa-ban"]
];
$config = $statusMap[$status] ?? ["color" => "#888", "bg" => "rgba(255,255,255,0.05)", "icon" => "fa-info-circle"];
?>

<link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Barlow+Condensed:wght@400;700;900&display=swap" rel="stylesheet">

<style>
    :root {
        --rpm-red: #ff0000;
        --rpm-black: #050505;
        --rpm-grey: #111111;
        --border-tech: rgba(255, 0, 0, 0.2);
    }

    body {
        background-color: var(--rpm-black);
        color: #fff;
        font-family: 'Barlow Condensed', sans-serif;
        letter-spacing: 0.5px;
    }

    .order-container { padding: 80px 0; }

    .order-card {
        background: var(--rpm-grey);
        border: 1px solid #222;
        border-top: 4px solid var(--rpm-red);
        padding: 50px;
        position: relative;
        overflow: hidden;
    }

    .order-card::before {
        content: 'RPM_LOGISTICS';
        position: absolute;
        top: 20px;
        right: -30px;
        font-family: 'Orbitron';
        font-weight: 900;
        font-size: 4rem;
        color: rgba(255, 255, 255, 0.02);
        transform: rotate(-5deg);
        pointer-events: none;
    }

    .status-badge {
        padding: 10px 20px;
        font-family: 'Orbitron';
        font-weight: 900;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        font-size: 0.75rem;
        text-transform: uppercase;
        clip-path: polygon(10% 0, 100% 0, 90% 100%, 0 100%);
    }

    .info-section {
        border-bottom: 1px solid #222;
        padding-bottom: 30px;
        margin-bottom: 30px;
    }

    .section-label {
        font-family: 'Orbitron';
        color: var(--rpm-red);
        font-weight: 900;
        font-size: 0.7rem;
        text-transform: uppercase;
        margin-bottom: 20px;
        display: block;
        letter-spacing: 2px;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 30px;
    }

    .info-item label {
        display: block;
        color: #555;
        font-weight: 900;
        text-transform: uppercase;
        font-size: 0.7rem;
        margin-bottom: 5px;
    }

    .info-item p { 
        font-weight: 700; 
        margin: 0; 
        font-size: 1.1rem; 
        text-transform: uppercase;
    }

    .product-row {
        display: flex;
        align-items: center;
        padding: 20px 0;
        border-bottom: 1px solid #1a1a1a;
    }

    .product-name { flex: 2; font-family: 'Orbitron'; }
    .product-meta { flex: 1; text-align: center; }
    .product-total { flex: 1; text-align: right; font-weight: 900; color: #fff; font-size: 1.2rem; }

    .summary-box {
        background: #000;
        padding: 30px;
        margin-top: 30px;
        border: 1px solid #1a1a1a;
    }

    .summary-line {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
        text-transform: uppercase;
        font-weight: 700;
    }

    .total-line {
        margin-top: 15px;
        padding-top: 15px;
        border-top: 2px solid var(--rpm-red);
        font-size: 1.8rem;
        font-family: 'Orbitron';
        color: var(--rpm-red);
    }

    .btn-store {
        background: var(--rpm-red);
        color: #fff !important;
        padding: 20px;
        width: 100%;
        text-decoration: none;
        display: block;
        text-align: center;
        font-family: 'Orbitron';
        font-weight: 900;
        text-transform: uppercase;
        transition: 0.3s;
        margin-top: 30px;
        clip-path: polygon(5% 0, 100% 0, 95% 100%, 0 100%);
    }

    .btn-store:hover {
        background: #fff;
        color: #000 !important;
        transform: translateY(-3px);
    }

    @media (max-width: 576px) {
        .order-card { padding: 30px 20px; }
        .product-total { font-size: 1rem; }
    }
</style>

<div class="order-container">
    <div class="container d-flex justify-content-center">
        <div class="order-card w-100" style="max-width: 900px;">

            <div class="d-flex justify-content-between align-items-end mb-5 flex-wrap gap-4">
                <div>
                    <h1 style="font-family: 'Orbitron'; font-weight: 900; margin:0; text-transform: uppercase; letter-spacing: -2px;">MISSION_ID: #<?= $order_id ?></h1>
                    <p style="color:#555; margin:0; font-weight:700; text-transform: uppercase;">TIMESTAMP: <?= date('d.m.Y // H:i', strtotime($order['created_at'] ?? 'now')) ?></p>
                </div>
                <div class="status-badge" style="color: <?= $config['color'] ?>; background: <?= $config['bg'] ?>; border: 1px solid <?= $config['color'] ?>;">
                    <i class="fas <?= $config['icon'] ?>"></i> <?= $status ?>
                </div>
            </div>

            <div class="info-section">
                <span class="section-label">LOGISTICS_COORDINATES</span>
                <div class="info-grid">
                    <div class="info-item">
                        <label>Operator</label>
                        <p><?= htmlspecialchars($order['customer_name']) ?></p>
                    </div>
                    <div class="info-item">
                        <label>Comms_ID</label>
                        <p><?= htmlspecialchars($order['customer_phone']) ?></p>
                    </div>
                    <div class="info-item">
                        <label>Protocol</label>
                        <p><?= strtoupper(htmlspecialchars($order['payment_method'] ?? 'COD')) ?></p>
                    </div>
                </div>
                <div class="info-item mt-4">
                    <label>Drop_Zone</label>
                    <p style="font-size: 0.9rem;"><?= htmlspecialchars($order['customer_address']) ?></p>
                </div>
            </div>

            <div class="product-list">
                <span class="section-label">HARDWARE_MANIFEST</span>
                <?php 
                $subtotal = 0;
                foreach ($items as $item): 
                    $line_total = $item['price'] * $item['quantity'];
                    $subtotal += $line_total;
                ?>
                    <div class="product-row">
                        <div class="product-name">
                            <div style="color:var(--rpm-red); font-weight:900; text-transform: uppercase;"><?= htmlspecialchars($item['name']) ?></div>
                            <div style="font-size: 0.75rem; color: #555; font-family: 'Barlow Condensed';">UNIT_PRICE: $<?= number_format($item['price'], 2) ?></div>
                        </div>
                        <div class="product-meta">
                            <span style="border: 1px solid #333; padding: 4px 12px; font-weight: 900;">QTY: <?= $item['quantity'] ?></span>
                        </div>
                        <div class="product-total">
                            $<?= number_format($line_total, 2) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="summary-box">
                <div class="summary-line" style="color: #555;">
                    <span>Loadout_Subtotal</span>
                    <span>$<?= number_format($subtotal, 2) ?></span>
                </div>
                
                <?php if ($order['discount'] > 0): ?>
                    <div class="summary-line" style="color: var(--rpm-red);">
                        <span>Voucher_Adjustment <?= !empty($order['coupon_code']) ? '['.strtoupper($order['coupon_code']).']' : '' ?></span>
                        <span>-$<?= number_format($order['discount'], 2) ?></span>
                    </div>
                <?php endif; ?>

                <div class="summary-line total-line">
                    <span>FINAL_TOTAL</span>
                    <span>$<?= number_format($order['total_price'], 2) ?></span>
                </div>
            </div>

            <a href="index.php" class="btn-store">
                <i class="fas fa-undo-alt me-2"></i> RETURN_TO_SHOWROOM
            </a>

        </div>
    </div>
</div>

<?php include("./inc/footer.php"); ?>