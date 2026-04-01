<?php
include("../inc/config.php");
include("../inc/admin_header.php");

$current_page = "orders";
$page_title = "Garage Order Control";

try {
    $stmt = $pdo->query("SELECT * FROM orders ORDER BY id DESC");
    $orders = $stmt->fetchAll();
} catch (PDOException $e) {
    $orders = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <title><?php echo $page_title; ?> | RPM Admin</title>

    <link href="https://fonts.googleapis.com/css2?family=Nosifer&family=Barlow+Condensed:ital,wght@0,400;0,700;1,900&family=Orbitron:wght@400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --rpm-red: #ff0000;
            --neon-orange: #eee;
            --bg-black: #050505;
            --glass: rgba(255, 255, 255, 0.03);
            --border-glow: rgba(243, 156, 18, 0.2);
        }

        body {
            background-color: var(--bg-black);
            color: #eee;
            font-family: 'Barlow Condensed', sans-serif;
            letter-spacing: 0.5px;
        }

        .admin-wrapper { padding: 60px 0; }

        .garage-header h1 {
            font-family: 'Nosifer', sans-serif;
            font-size: 2.8rem;
            color: var(--neon-orange);
            text-shadow: 0 0 20px rgba(243, 156, 18, 0.4);
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .garage-header p {
            font-family: 'Orbitron', sans-serif;
            font-size: 0.8rem;
            color: #555;
            letter-spacing: 4px;
        }

        /* حاوية الجدول الزجاجية */
        .order-vault {
            background: var(--glass);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255,255,255,0.05);
            border-radius: 20px;
            padding: 30px;
            margin-top: 40px;
            position: relative;
            box-shadow: 0 30px 60px rgba(0,0,0,0.8);
        }

        .order-vault::before {
            content: 'SYSTEM ACTIVE';
            position: absolute;
            top: -10px; right: 30px;
            background: var(--neon-orange);
            color: #000;
            font-family: 'Orbitron';
            font-size: 0.6rem;
            font-weight: 900;
            padding: 2px 10px;
            clip-path: polygon(10% 0, 100% 0, 90% 100%, 0 100%);
        }

        .table {
            border-collapse: separate;
            border-spacing: 0 10px;
            color: #fff;
        }

        .table thead th {
            font-family: 'Orbitron';
            color: var(--neon-orange);
            border: none;
            text-transform: uppercase;
            font-size: 0.75rem;
            padding: 15px 20px;
            background: rgba(0,0,0,0.4);
        }

        .table tbody tr {
            background: rgba(255,255,255,0.02) !important;
            transition: 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid transparent;
        }

        .table tbody tr:hover {
            background: rgba(243, 156, 18, 0.08) !important;
            transform: translateX(10px);
            border-left: 4px solid var(--neon-orange);
        }

        .table td {
            vertical-align: middle;
            padding: 20px;
            border: none;
            border-top: 1px solid rgba(255,255,255,0.05);
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }

        /* تفاصيل الأوردر */
        .order-ref {
            font-family: 'Orbitron';
            font-weight: 900;
            color: #fff;
        }

        .customer-info b {
            font-size: 1.2rem;
            display: block;
            color: #fff;
        }

        .customer-info span { color: #777; font-size: 0.9rem; }

        .price-tag {
            font-family: 'Orbitron';
            color: #2ecc71;
            font-weight: 700;
            font-size: 1.1rem;
        }

        /* بادات الحالة التكتيكية */
        .status-pill {
            padding: 6px 15px;
            border-radius: 4px;
            font-size: 0.7rem;
            font-weight: 900;
            text-transform: uppercase;
            font-family: 'Orbitron';
            display: inline-block;
            border: 1px solid;
        }

        .pending { background: rgba(243, 156, 18, 0.1); color: var(--neon-orange); border-color: rgba(243, 156, 18, 0.3); }
        .completed { background: rgba(46, 204, 113, 0.1); color: #2ecc71; border-color: rgba(46, 204, 113, 0.3); }
        .cancelled { background: rgba(231, 76, 60, 0.1); color: #e74c3c; border-color: rgba(231, 76, 60, 0.3); }

        /* زرار الأكشن المائل */
        .btn-manage {
            background: var(--neon-orange);
            color: #000;
            font-family: 'Orbitron';
            font-weight: 900;
            padding: 10px 20px;
            text-decoration: none;
            text-transform: uppercase;
            font-size: 0.75rem;
            clip-path: polygon(15% 0, 100% 0, 85% 100%, 0 100%);
            transition: 0.3s;
            display: inline-block;
        }

        .btn-manage:hover {
            background: #fff;
            transform: scale(1.05);
            color: #000;
        }
    </style>
</head>
<body>

<div class="admin-wrapper">
    <div class="container">
        <div class="garage-header text-center">
            <h1><i class="fas fa-motorcycle me-3"></i>Orders Manifest</h1>
            <p>GARAGE DEPOT CONTROL PANEL V2.0</p>
        </div>

        <div class="order-vault">
            <?php if (!empty($orders)): ?>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Ref ID</th>
                                <th>Customer / Machine</th>
                                <th>Revenue</th>
                                <th>Deployment Date</th>
                                <th>Current Status</th>
                                <th class="text-center">Command</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td><span class="order-ref">#<?php echo $order['id']; ?></span></td>
                                    <td class="customer-info">
                                        <b><?php echo htmlspecialchars($order['customer_name'] ?? 'Guest Rider'); ?></b>
                                        <span><?php echo htmlspecialchars($order['email'] ?? 'No Email'); ?></span>
                                    </td>
                                    <td><span class="price-tag">EGP <?php echo number_format($order['total_price']); ?></span></td>
                                    <td><i class="far fa-calendar-alt me-2 text-muted"></i><?php echo date('d M, Y', strtotime($order['created_at'])); ?></td>
                                    <td>
                                        <?php $st = strtolower($order['status'] ?? 'pending'); ?>
                                        <div class="status-pill <?php echo $st; ?>">
                                            <i class="fas fa-microchip me-1"></i> <?php echo $st; ?>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <a href="view_order.php?id=<?php echo $order['id']; ?>" class="btn-manage">
                                            Open Order <i class="fas fa-angle-right ms-1"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-skull fa-4x mb-4 text-muted"></i>
                    <h3 class="text-muted">GARAGE IS CLEAR. NO PENDING MISSIONS.</h3>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include("../inc/footer.php"); ?>

</body>
</html>