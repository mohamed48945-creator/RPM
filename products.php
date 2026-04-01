<?php
include("./inc/config.php");
include("./inc/header.php");

// 1. استلام قيم التصفية
$category = isset($_GET['category']) ? $_GET['category'] : '';
$price_sort = isset($_GET['price_sort']) ? $_GET['price_sort'] : '';

// 2. بناء الاستعلام
$sql = "SELECT * FROM products WHERE 1=1";
$params = [];

if (!empty($category)) {
    $sql .= " AND category = ?";
    $params[] = $category;
}

if ($price_sort == 'low') {
    $sql .= " ORDER BY price ASC";
} elseif ($price_sort == 'high') {
    $sql .= " ORDER BY price DESC";
} else {
    $sql .= " ORDER BY id DESC";
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();

// جلب الفئات المتاحة للفلتر
$cat_stmt = $pdo->query("SELECT DISTINCT category FROM products");
$categories = $cat_stmt->fetchAll(PDO::FETCH_COLUMN);
?>

<link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Barlow+Condensed:ital,wght@0,400;0,700;1,900&display=swap" rel="stylesheet">

<style>
    :root {
        --rpm-red: #ff0000;
        --rpm-dark: #080808;
        --rpm-card: #111111;
    }

    body { background-color: var(--rpm-dark); color: #fff; font-family: 'Barlow Condensed', sans-serif; }

    /* استايل لوحة التصفية - نفس روح التصميم القديم */
    .filter-panel {
        background: var(--rpm-card);
        border: 1px solid #222;
        padding: 30px;
        margin-bottom: 50px;
        position: relative;
        clip-path: polygon(0 0, 100% 0, 98% 100%, 2% 100%);
    }

    .filter-panel::after {
        content: '';
        position: absolute;
        top: 0; left: 50%;
        transform: translateX(-50%);
        width: 100px; height: 3px;
        background: var(--rpm-red);
    }

    .filter-label {
        font-family: 'Orbitron';
        font-size: 0.7rem;
        color: var(--rpm-red);
        text-transform: uppercase;
        letter-spacing: 2px;
        margin-bottom: 10px;
        display: block;
    }

    .rpm-input {
        background: #000 !important;
        border: 1px solid #333 !important;
        color: #fff !important;
        border-radius: 0 !important;
        font-family: 'Barlow Condensed';
        text-transform: uppercase;
    }

    .btn-rpm-filter {
        background: var(--rpm-red);
        color: #fff;
        font-family: 'Orbitron';
        font-weight: 900;
        border: none;
        padding: 10px 25px;
        text-transform: uppercase;
        clip-path: polygon(15% 0, 100% 0, 85% 100%, 0 100%);
        transition: 0.3s;
        cursor: pointer;
    }

    .btn-rpm-filter:hover { background: #fff; color: #000; }

    /* استايل الكروت القديم */
    .bike-card {
        background: var(--rpm-card);
        border: 1px solid #222;
        transition: 0.4s;
        margin-bottom: 30px;
        position: relative;
    }

    .bike-card:hover {
        border-color: var(--rpm-red);
        transform: translateY(-10px);
    }

    .img-wrapper {
        height: 250px;
        background: #000;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        overflow: hidden;
    }

    .bike-img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        filter: drop-shadow(0 0 15px rgba(255,0,0,0.1));
    }

    .bike-info { padding: 25px; text-align: center; }
    
    .bike-name {
        font-family: 'Orbitron';
        font-size: 1.4rem;
        font-weight: 900;
        text-transform: uppercase;
        margin-bottom: 10px;
    }

    .bike-price {
        color: var(--rpm-red);
        font-size: 1.5rem;
        font-weight: 700;
        font-family: 'Orbitron';
    }

    .btn-view {
        display: block;
        width: 100%;
        padding: 12px;
        background: #222;
        color: #fff;
        text-decoration: none;
        font-family: 'Orbitron';
        font-size: 0.8rem;
        text-transform: uppercase;
        margin-top: 15px;
        transition: 0.3s;
    }

    .btn-view:hover { background: var(--rpm-red); color: #fff; }
</style>

<div class="container py-5">
    <div class="text-center mb-5">
        <h1 style="font-family:'Orbitron'; font-weight:900; font-size:3.5rem;">RPM <span style="color:var(--rpm-red)">SHOWROOM</span></h1>
        <p class="text-muted">SELECT YOUR NEXT GENERATION MACHINE</p>
    </div>

    <div class="filter-panel">
        <form action="" method="GET" class="row g-3 align-items-end justify-content-center">
            <div class="col-md-3">
                <label class="filter-label">Category</label>
                <select name="category" class="form-select rpm-input">
                    <option value="">All Fleets</option>
                    <?php foreach($categories as $cat): ?>
                        <option value="<?= $cat ?>" <?= $category == $cat ? 'selected' : '' ?>><?= strtoupper($cat) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="filter-label">Sort By Price</label>
                <select name="price_sort" class="form-select rpm-input">
                    <option value="">Standard</option>
                    <option value="low" <?= $price_sort == 'low' ? 'selected' : '' ?>>Low to High</option>
                    <option value="high" <?= $price_sort == 'high' ? 'selected' : '' ?>>High to Low</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn-rpm-filter w-100">Filter</button>
            </div>
            <?php if(!empty($category) || !empty($price_sort)): ?>
            <div class="col-md-1">
                <a href="index.php" class="text-danger d-block text-center mb-2"><i class="fas fa-sync-alt"></i></a>
            </div>
            <?php endif; ?>
        </form>
    </div>

    <div class="row">
        <?php if(count($products) > 0): ?>
            <?php foreach($products as $p): ?>
                <div class="col-lg-4 col-md-6">
                    <div class="bike-card">
                        <div class="img-wrapper">
                            <img src="uploads/bikes/<?= $p['image'] ?>" class="bike-img" alt="<?= $p['name'] ?>">
                        </div>
                        <div class="bike-info">
                            <h3 class="bike-name"><?= htmlspecialchars($p['name']) ?></h3>
                            <div class="bike-price">EGP <?= number_format($p['price']) ?></div>
                            <a href="product.php?id=<?= $p['id'] ?>" class="btn-view">
                                View Specs <i class="fas fa-bolt ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center py-5">
                <h2 style="font-family:'Orbitron'; color:#333;">NO MACHINES FOUND</h2>
                <a href="index.php" class="btn-rpm-filter d-inline-block mt-3" style="text-decoration:none;">Reset Search</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include("./inc/footer.php"); ?>