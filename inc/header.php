<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$cartItemCount = array_sum($_SESSION['cart']);
$base_path = './';
$page_title = isset($page_title) ? $page_title : 'Home';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" type="image/png" href="img/rpm-logo.png">
    <link rel="shortcut icon" type="image/png" href="img/rpm-logo.png">

    <meta property="og:title" content="RPM Motorsports">
    <meta property="og:description" content="Premium Motorcycles & Performance Parts">
    <meta property="og:image" content="img/og-rpm.png">
    <meta property="og:url" content="index.php">
    <meta property="og:type" content="website">

    <title><?php echo ucfirst($page_title); ?> | RPM Motorsports</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Barlow:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/custom.css">

    <style>
        :root {
            --primary-red: #ff0000;
            --deep-black: #0a0a0a;
            --carbon-gray: #1a1a1a;
            --pure-white: #ffffff;
            --nitro-glow: 0 0 20px rgba(255, 0, 0, 0.5);
        }

        body {
            font-family: 'Barlow', sans-serif;
            background-color: var(--deep-black);
            color: var(--pure-white);
            overflow-x: hidden;
            scroll-behavior: smooth;
            min-height: 100vh;
            padding-top: 90px;
        }

        .floating-dots {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 1;
        }



        @keyframes floatDot {
            0% { transform: translateY(0); opacity: 0.2; }
            50% { opacity: 0.8; }
            100% { transform: translateY(-100px); opacity: 0.2; }
        }



        .loader {
            width: 100px;
            height: 100px;
            border: 4px solid rgba(255, 0, 0, 0.1);
            border-top-color: var(--primary-red);
            border-radius: 50%;
            animation: spin 1s cubic-bezier(0.68, -0.55, 0.27, 1.55) infinite;
        }

        .loader-text {
            font-family: 'Orbitron', sans-serif;
            color: var(--primary-red);
            margin-top: 1.5rem;
            letter-spacing: 4px;
            text-transform: uppercase;
            font-weight: bold;
        }

        @keyframes spin {
            100% { transform: rotate(360deg); }
        }

        .admin-navbar {
            background: rgba(10, 10, 10, 0.9);
            backdrop-filter: blur(15px);
            border-bottom: 2px solid var(--primary-red);
            padding: 15px 0;
            box-shadow: 0 5px 30px rgba(0, 0, 0, 0.8);
        }



        .navbar-brand:hover .rpm-logo-img {
            transform: skewX(-10deg) scale(1.1);
            box-shadow: 0 0 30px var(--primary-red);
        }

        .nav-link {
            font-family: 'Orbitron', sans-serif;
            color: white !important;
            font-weight: 700;
            font-size: 0.85rem;
            letter-spacing: 1px;
            text-transform: uppercase;
            padding: 10px 20px !important;
            transition: 0.3s;
        }

        .nav-link:hover {
            color: var(--primary-red) !important;
            background: rgba(255, 0, 0, 0.05);
        }

        .cart-badge {
            background-color: var(--primary-red);
            color: white;
            font-size: 10px;
            padding: 3px 7px;
            border-radius: 4px;
            position: absolute;
            top: 0;
            right: 5px;
            font-weight: 800;
            transform: skewX(-15deg);
        }

        .navbar-toggler {
            border: 1px solid var(--primary-red);
        }

        .navbar-toggler-icon {
            filter: invert(1) brightness(100) sepia(1) saturate(10000%) hue-rotate(0deg);
        }
    </style>
</head>

<body>
    <div class="floating-dots"></div>

    <div id="loading">
        <div class="loader-container text-center">
            <div class="loader"></div>
            <div class="loader-text">Igniting Engine...</div>
        </div>
    </div>

    <nav class="navbar navbar-expand-lg admin-navbar fixed-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <div class="d-flex flex-column">
                    <span style="font-family: 'Orbitron', sans-serif; color: #fff; font-weight: 900; line-height: 1;">RPM</span>
                    <small style="font-family: 'Orbitron', sans-serif; color: var(--primary-red); font-size: 10px; font-weight: 700; letter-spacing: 1px;">MOTORSPORTS</small>
                </div>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#rpmNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="rpmNavbar">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="aboutUs.php">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="products.php">Garage</a>
                    </li>
                    <li class="nav-item position-relative">
                        <a class="nav-link" href="cart.php">
                            <i class="fas fa-shopping-basket"></i>
                            <?php if ($cartItemCount > 0): ?>
                                <span class="cart-badge"><?php echo $cartItemCount; ?></span>
                            <?php endif; ?>
                        </a>
                    </li>
                    <li class="nav-item ms-lg-3">
                        <a class="nav-link" href="login.php" style="border: 1px solid var(--primary-red); border-radius: 5px;">
                            <i class="fas fa-user-shield"></i> Admin
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        window.addEventListener('load', function() {
            const loader = document.getElementById('loading');
            loader.style.opacity = '0';
            setTimeout(() => { loader.style.display = 'none'; }, 500);
        });
    </script>
</body>
</html>