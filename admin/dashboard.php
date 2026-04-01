<?php
include("../inc/config.php");
include("../inc/admin_header.php");
$page_title = "RPM Showroom Control";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <title>
        <?php echo (isset($page_title)) ? $page_title : "RPM Dashboard"; ?> | Speed & Power
    </title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;900&family=Barlow:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --primary-red: #ff0000;
            --dark-bg: #0a0a0a;
            --carbon-gray: #111;
            --nitro-blue: #05d9e8;
            --gold-accent: #f39c12;
        }

        body {
            font-family: 'Barlow', sans-serif;
            background-color: var(--dark-bg);
            color: white;
            overflow-x: hidden;
        }

        .admin-content {
            padding: 80px 20px 40px;
        }

        .admin-header {
            margin-bottom: 50px;
            text-align: center;
        }

        .admin-header h1 {
            font-family: 'Orbitron', sans-serif;
            font-weight: 900;
            font-size: 2.8rem;
            color: #fff;
            text-transform: uppercase;
            letter-spacing: 2px;
            text-shadow: 3px 3px var(--primary-red);
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 25px;
        }

        /* Neon Card RPM Style */
        .stat-card {
            position: relative;
            padding: 40px 20px;
            border-radius: 15px;
            background: rgba(255, 255, 255, 0.02);
            display: flex;
            flex-direction: column;
            align-items: center;
            overflow: hidden;
            border: none;
            transition: 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .stat-card:hover { transform: translateY(-10px); }

        .stat-card::before {
            content: '';
            position: absolute;
            width: 150%;
            height: 150%;
            background-image: conic-gradient(transparent, var(--card-color), transparent 30%);
            animation: rotateNeon 3s linear infinite;
            z-index: -2;
        }

        .stat-card::after {
            content: '';
            position: absolute;
            inset: 2px;
            background: var(--carbon-gray);
            border-radius: 13px;
            z-index: -1;
        }

        @keyframes rotateNeon {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        /* Colors for RPM Theme */
        .stat-card.bikes { --card-color: var(--primary-red); }
        .stat-card.orders { --card-color: var(--nitro-blue); }
        .stat-card.revenue { --card-color: var(--gold-accent); }
        .stat-card.users { --card-color: #9d50bb; }

        .stat-icon {
            font-size: 3rem;
            margin-bottom: 15px;
            color: var(--card-color);
            filter: drop-shadow(0 0 10px var(--card-color));
        }

        .stat-card h5 {
            font-family: 'Orbitron', sans-serif;
            font-size: 0.8rem;
            color: #888;
            margin-bottom: 10px;
        }

        .stat-card h3 {
            font-size: 2.8rem;
            font-weight: 800;
            margin-bottom: 20px;
        }

        .btn-custom {
            padding: 8px 25px;
            border-radius: 5px;
            border: 1px solid var(--card-color);
            color: #fff;
            text-decoration: none;
            font-family: 'Orbitron', sans-serif;
            font-size: 0.7rem;
            transition: 0.3s;
        }

        .btn-custom:hover {
            background: var(--card-color);
            color: #000;
            box-shadow: 0 0 20px var(--card-color);
        }

        /* Custom Swal for RPM */
        .rpm-swal-popup {
            border: 2px solid var(--primary-red) !important;
            background: #000 !important;
            border-radius: 0 !important;
        }
        .rpm-swal-btn {
            background: var(--primary-red) !important;
            clip-path: polygon(10% 0, 100% 0, 90% 100%, 0 100%) !important;
            font-family: 'Orbitron', sans-serif !important;
            padding: 12px 30px !important;
        }
    </style>
</head>

<body>
    <div class="admin-content">
        <div class="container">
            <div class="admin-header">
                <h1><i class="fas fa-tachometer-alt text-red"></i> RPM DASHBOARD</h1>
                <p class="text-muted">Motorcycle Showroom Management System</p>
            </div>

            <div class="stats-grid">
                <div class="stat-card bikes">
                    <div class="stat-icon"><i class="fas fa-motorcycle"></i></div>
                    <h5>Available Inventory</h5>
                    <?php
                    $stmt = $pdo->query("SELECT COUNT(*) as count FROM products");
                    $result = $stmt->fetch();
                    echo "<h3>" . ($result['count'] ?? 0) . "</h3>";
                    ?>
                    <a href="products.php" class="btn-custom">MANAGE GARAGE</a>
                </div>

                <div class="stat-card orders">
                    <div class="stat-icon"><i class="fas fa-file-invoice-dollar"></i></div>
                    <h5>Showroom Sales</h5>
                    <?php
                    $stmt = $pdo->query("SELECT COUNT(*) as count FROM orders");
                    $result = $stmt->fetch();
                    echo "<h3>" . ($result['count'] ?? 0) . "</h3>";
                    ?>
                    <a href="orders.php" class="btn-custom">VIEW SALES</a>
                </div>

                <div class="stat-card revenue">
                    <div class="stat-icon"><i class="fas fa-coins"></i></div>
                    <h5>Total Revenue</h5>
                    <?php
                    $stmt = $pdo->query("SELECT SUM(total_price) as total FROM orders");
                    $result = $stmt->fetch();
                    $total = $result['total'] ?? 0;
                    echo "<h3>$" . number_format($total, 0) . "</h3>";
                    ?>
                </div>

                <div class="stat-card users">
                    <div class="stat-icon"><i class="fas fa-user-tie"></i></div>
                    <h5>VIP Clients</h5>
                    <?php
                    try {
                        $stmt = $pdo->query("SELECT COUNT(*) as count FROM users");
                        $result = $stmt->fetch();
                        echo "<h3>" . ($result['count'] ?? 0) . "</h3>";
                    } catch (Exception $e) { echo "<h3>0</h3>"; }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            Swal.fire({
                title: 'ENGINE STARTED!',
                html: `
                <div style="text-align: center; font-family: 'Barlow';">
                    <p style="color: #ff0000; font-weight: bold; font-family: 'Orbitron';">
                        Welcome back, Mohamed Ahmed.
                    </p>
                    <p style="color: #ccc;">
                        Showroom systems are primed and ready for high performance.<br>
                        <span style="color: #fff; font-style: italic;">"Shift into higher gears today."</span>
                    </p>
                </div>
            `,
                icon: 'success',
                background: '#0a0a0a',
                color: '#fff',
                confirmButtonText: 'OPEN THROTTLE',
                customClass: {
                    popup: 'rpm-swal-popup',
                    confirmButton: 'rpm-swal-btn'
                }
            });
        });
    </script>

    <?php include("../inc/footer.php"); ?>
</body>
</html>