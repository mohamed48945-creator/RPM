<?php
$page_title = "Technical Specs | Product Details";
include("./inc/config.php");
include("./inc/header.php");

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    echo "<div class='container mt-5 text-center'><h3 class='text-white'>DATA_NOT_FOUND</h3><a href='index.php' class='btn btn-danger'>Back to Showroom</a></div>";
    include("inc/footer.php");
    exit();
}
?>

<link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Barlow+Condensed:ital,wght@0,400;0,700;1,900&display=swap" rel="stylesheet">

<style>
    :root {
        --rpm-red: #ff0000;
        --rpm-dark: #080808;
        --rpm-card: #111111;
        --border-color: #222;
    }

    body {
        background-color: var(--rpm-dark);
        color: #fff;
        font-family: 'Barlow Condensed', sans-serif;
    }

    .content-wrapper {
        min-height: 100vh;
        display: flex;
        align-items: center;
        padding: 80px 0;
    }

    .product-details-card {
        background: var(--rpm-card);
        border: 1px solid var(--border-color);
        border-radius: 4px;
        overflow: hidden;
        position: relative;
    }

    .product-details-card::after {
        content: '';
        position: absolute;
        top: 0; left: 0;
        width: 100%; height: 4px;
        background: var(--rpm-red);
    }

    .image-section {
        position: relative;
        background: #000;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
        min-height: 550px;
        border-right: 1px solid var(--border-color);
        padding: 20px;
    }

    .product-main-img {
        width: 85%;
        height: auto;
        max-height: 500px;
        object-fit: contain;
        z-index: 2;
        filter: drop-shadow(0 0 30px rgba(255, 0, 0, 0.25));
    }

    .info-section {
        padding: 50px !important;
        position: relative;
    }

    .category-badge {
        font-family: 'Orbitron';
        color: var(--rpm-red);
        text-transform: uppercase;
        letter-spacing: 2px;
        font-size: 0.7rem;
        font-weight: 900;
        margin-bottom: 15px;
        display: inline-block;
        border-left: 3px solid var(--rpm-red);
        padding-left: 10px;
    }

    .product-title {
        font-family: 'Orbitron';
        font-size: 3.5rem;
        font-weight: 900;
        margin-bottom: 20px;
        line-height: 1;
        text-transform: uppercase;
        letter-spacing: -2px;
    }

    .product-description {
        color: #888;
        font-size: 1.1rem;
        line-height: 1.6;
        margin-bottom: 40px;
        font-weight: 400;
    }

    .price-display {
        font-family: 'Orbitron';
        font-size: 3rem;
        font-weight: 900;
        color: #fff;
        margin-bottom: 45px;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .price-display small {
        font-size: 0.8rem;
        color: var(--rpm-red);
        letter-spacing: 2px;
    }

    .action-buttons {
        display: flex;
        gap: 15px;
    }

    .btn-cart-main {
        background: var(--rpm-red);
        color: #fff;
        border: none;
        padding: 20px 40px;
        font-family: 'Orbitron';
        font-weight: 900;
        flex-grow: 1;
        transition: 0.3s;
        text-transform: uppercase;
        clip-path: polygon(5% 0%, 100% 0%, 95% 100%, 0% 100%);
        cursor: pointer;
    }

    .btn-cart-main:hover {
        background: #fff;
        color: #000;
        transform: translateY(-5px);
    }

    .btn-back-circle {
        background: transparent;
        color: #444;
        border: 1px solid #222;
        width: 70px; height: 70px;
        display: flex; align-items: center; justify-content: center;
        transition: 0.3s;
        text-decoration: none;
    }

    .btn-back-circle:hover {
        color: var(--rpm-red);
        border-color: var(--rpm-red);
    }

    .specs-grid {
        margin-top: 50px;
        padding-top: 30px;
        border-top: 1px solid #222;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .spec-item { display: flex; flex-direction: column; }
    .spec-label { font-family: 'Orbitron'; font-size: 0.6rem; color: #444; text-transform: uppercase; margin-bottom: 2px; }
    .spec-value { font-weight: 700; font-size: 0.9rem; color: #fff; text-transform: uppercase; }

    @media (max-width: 991px) {
        .product-title { font-size: 2.5rem; }
        .image-section { min-height: 350px; border-right: none; border-bottom: 1px solid var(--border-color); }
        .info-section { padding: 30px !important; }
        .action-buttons { flex-direction: column; }
        .btn-back-circle { width: 100%; height: 60px; order: 2; }
        .btn-cart-main { order: 1; }
    }
</style>

<div class="content-wrapper">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-11">
                <div class="product-details-card">
                    <div class="row g-0">
                        <div class="col-lg-6">
                            <div class="image-section">
                                <?php
                                $img_name = $product['image'];
                                $img_path = 'uploads/bikes/' . $img_name;

                                if (!empty($img_name) && file_exists($img_path)): ?>
                                    <img src="<?= $img_path ?>" alt="<?= htmlspecialchars($product['name']); ?>" class="product-main-img">
                                <?php else: ?>
                                    <div class="text-center text-muted">
                                        <i class="fas fa-motorcycle fa-6x mb-3"></i>
                                        <p class="font-monospace">IMAGE_MISSING_IN_BIKES</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="info-section">
                                <span class="category-badge">Hardware Manifest</span>
                                <h1 class="product-title"><?= htmlspecialchars($product['name']); ?></h1>

                                <p class="product-description">
                                    <?= nl2br(htmlspecialchars($product['description'])); ?>
                                </p>

                                <div class="price-display">
                                    <small>MSRP</small> EGP <?= number_format($product['price']); ?>
                                </div>

                                <div class="action-buttons">
                                    <button onclick="addToCart(<?= $product['id']; ?>)" class="btn-cart-main">
                                        <i class="fas fa-plus-circle me-2"></i> Acquire Unit
                                    </button>

                                    <a href="index.php" class="btn-back-circle">
                                        <i class="fas fa-arrow-left fa-lg"></i>
                                    </a>
                                </div>

                                <div class="specs-grid">
                                    <div class="spec-item"><span class="spec-label">Warranty</span><span class="spec-value">2-Year Limited</span></div>
                                    <div class="spec-item"><span class="spec-label">Shipping</span><span class="spec-value">Global Logistics</span></div>
                                    <div class="spec-item"><span class="spec-label">Authenticity</span><span class="spec-value">100% Genuine</span></div>
                                    <div class="spec-item"><span class="spec-label">Status</span><span class="spec-value">In Stock</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function addToCart(productId) {
        fetch('cart.php?add=' + productId)
            .then(response => {
                console.log('Unit Added');
                alert('SYSTEM_CONFIRMATION: Machine added to your garage.');
            });
    }
</script>

<?php include("./inc/footer.php"); ?>