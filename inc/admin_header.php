<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$cart_count = array_sum($_SESSION['cart']);
$base_path = '../';
$page_title = isset($page_title) ? $page_title : 'RPM Motorsports';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Barlow:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="shortcut icon" type="image/x-icon" href="../img/rpm-logo.png">

    <style>
        :root {
            --primary-red: #ff0000;
            --deep-black: #050505;
            --carbon-gray: #1a1a1a;
            --pure-white: #ffffff;
        }

        body {
            font-family: 'Barlow', sans-serif;
            background-color: var(--deep-black);
            color: var(--pure-white);
            margin: 0;
            padding: 0;
        }

        #loading {
            background: var(--deep-black);
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            transition: 0.5s;
        }

        .loader {
            width: 80px;
            height: 80px;
            border: 5px solid #222;
            border-top-color: var(--primary-red);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            100% { transform: rotate(360deg); }
        }

        .admin-navbar {
            background: rgba(10, 10, 10, 0.95) !important;
            border-bottom: 2px solid var(--primary-red);
            padding: 10px 0;
        }

        .navbar-brand {
            font-family: 'Orbitron', sans-serif;
            color: var(--primary-red) !important;
            font-weight: 800;
            font-size: 1.4rem;
            text-transform: uppercase;
        }
        .nav-link {
            font-family: 'Orbitron', sans-serif;
            color: #ccc !important;
            font-size: 0.8rem;
            margin: 0 10px;
            transition: 0.3s;
            text-transform: uppercase;
        }

        .nav-link:hover {
            color: var(--primary-red) !important;
        }

        .logout-btn {
            background: var(--primary-red) !important;
            color: white !important;
            padding: 8px 20px !important;
            font-weight: bold;
            clip-path: polygon(10% 0, 100% 0, 90% 100%, 0 100%);
            margin-left: 15px;
        }

        .cart-badge {
            background: var(--primary-red);
            font-size: 10px;
            padding: 3px 6px;
            border-radius: 50%;
            vertical-align: top;
        }

        .navbar-toggler {
            border: 1px solid var(--primary-red);
        }
    </style>
</head>

<body>
    <div id="loading">
        <div class="text-center">
            <div class="loader"></div>
            <div class="mt-3" style="font-family: 'Orbitron'; color: white; letter-spacing: 2px;">SYNCING...</div>
        </div>
    </div>

    <nav class="navbar navbar-expand-lg admin-navbar sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="dashboard.php">
                <span>RPM <span style="color:white; font-size: 12px;">Admin</span></span>
            </a>
site
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNav">
                <i class="fas fa-bars" style="color: white;"></i>
            </button>

            <div class="collapse navbar-collapse" id="adminNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php"><i class="fas fa-chart-line me-1"></i> Status</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="products.php"><i class="fas fa-box me-1"></i> Stock</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="orders.php"><i class="fas fa-shopping-bag me-1"></i> Orders</a>
                    </li>
<li class="nav-item">
    <a class="nav-link" href="../index.php" target="_blank">
        <i class="fas fa-external-link-alt me-1"></i> View Store
        <?php if($cart_count > 0): ?>
            <span class="cart-badge"><?php echo $cart_count; ?></span>
        <?php endif; ?>
    </a>
</li>
                    <li class="nav-item">
                        <a class="nav-link logout-btn" href="../logout.php">
                            <i class="fas fa-sign-out-alt"></i> Exit
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        window.addEventListener('load', () => {
            const loader = document.getElementById('loading');
            loader.style.opacity = '0';
            setTimeout(() => loader.style.display = 'none', 500);
        });
    </script>
</body>
</html>