<?php
include("../inc/config.php");
include("../inc/admin_header.php");

$update_success = false;
if (isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];
    $stmt = $pdo->prepare("UPDATE orders SET status=? WHERE id=?");
    if ($stmt->execute([$status, $order_id])) {
        $update_success = true;
    }
}

$order_id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->execute([$order_id]);
$order = $stmt->fetch();

if (!$order) {
    echo "<div style='color:white; background:#ff3e3e; padding:40px; text-align:center; font-family:Montserrat;'>
            <i class='fas fa-skull-crossbones fa-3x mb-3'></i><br>CRITICAL ERROR: BIKE ORDER NOT FOUND!
          </div>";
    exit;
}

// جلب تفاصيل الموتسيكلات المطلوبة في الأوردر
$stmt_items = $pdo->prepare("SELECT oi.*, p.name as bike_name, p.image, p.price as current_bike_price FROM order_items oi LEFT JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?");
$stmt_items->execute([$order_id]);
$items = $stmt_items->fetchAll();

function getBikeImage($image)
{
    if (empty($image)) return '../img/default_bike.jpg';
    if (file_exists($image)) return $image;
    if (file_exists('../uploads/' . $image)) return '../uploads/' . $image;
    return '../img/default_bike.jpg';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BIKE ORDER #<?php echo $order_id; ?> | 7AMO BIKERS ADMIN</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700;900&family=Orbitron:wght@400;900&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --neon-red: #ff003c;
            --neon-blue: #00f2ff;
            --glass-bg: rgba(20, 20, 20, 0.7);
            --glass-border: rgba(255, 255, 255, 0.1);
            --deep-black: #080808;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background-color: var(--deep-black);
            color: #fff;
            min-height: 100vh;
            background-image: linear-gradient(rgba(0,0,0,0.8), rgba(0,0,0,0.8)), url('https://images.unsplash.com/photo-1558981403-c5f91bb9a08b?auto=format&fit=crop&q=80&w=2070');
            background-size: cover;
            background-attachment: fixed;
        }

        .content {
            padding: 100px 15px 60px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .glass-card {
            background: var(--glass-bg);
            backdrop-filter: blur(15px);
            border-radius: 25px;
            padding: 35px;
            margin-bottom: 30px;
            border: 1px solid var(--glass-border);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.8);
        }

        h1, h2 {
            font-family: 'Orbitron', sans-serif;
            text-transform: uppercase;
            color: var(--neon-blue);
            font-weight: 900;
            letter-spacing: 2px;
            border-left: 5px solid var(--neon-red);
            padding-left: 15px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin: 30px 0;
        }

        .info-item {
            background: rgba(255, 255, 255, 0.05);
            padding: 20px;
            border-radius: 15px;
            border-bottom: 3px solid transparent;
            transition: 0.4s ease;
        }

        .info-item:hover {
            background: rgba(255, 255, 255, 0.08);
            border-bottom-color: var(--neon-red);
            transform: translateY(-5px);
        }

        .info-item label {
            color: var(--neon-red);
            font-size: 0.7rem;
            font-weight: 900;
            text-transform: uppercase;
            display: block;
            margin-bottom: 5px;
        }

        .status-box {
            background: rgba(0, 242, 255, 0.05);
            padding: 25px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            border: 1px solid rgba(0, 242, 255, 0.2);
            margin-top: 20px;
        }

        .status-box select {
            background: #000;
            color: #fff;
            border: 1px solid var(--neon-blue);
            padding: 12px;
            border-radius: 10px;
            flex: 1;
            margin: 0 15px;
        }

        .btn-update {
            background: var(--neon-red);
            color: white;
            font-weight: 900;
            border: none;
            padding: 12px 35px;
            border-radius: 10px;
            transition: 0.3s;
            text-transform: uppercase;
        }

        .btn-update:hover {
            box-shadow: 0 0 20px var(--neon-red);
            filter: brightness(1.2);
        }

        .bike-thumb {
            width: 90px;
            height: 60px;
            object-fit: cover;
            border-radius: 10px;
            border: 2px solid var(--neon-blue);
        }

        .price-tag {
            color: #00ff88;
            font-weight: 800;
            font-family: 'Orbitron', sans-serif;
        }

        .total-section {
            text-align: right;
            padding: 30px;
            border-top: 2px dashed var(--glass-border);
        }

        .total-value {
            font-size: 3.5rem;
            font-family: 'Orbitron', sans-serif;
            color: var(--neon-blue);
            text-shadow: 0 0 20px rgba(0, 242, 255, 0.5);
        }

        .back-btn {
            display: inline-block;
            margin-top: 20px;
            color: #aaa;
            text-decoration: none;
            transition: 0.3s;
        }

        .back-btn:hover { color: var(--neon-red); }

        @media (max-width: 768px) {
            .status-box { flex-direction: column; gap: 15px; }
            .status-box select { width: 100%; margin: 0; }
            .total-value { font-size: 2.2rem; }
        }
    </style>
</head>

<body>
    <div class="content">
        <div class="glass-card">
            <h1><i class="fas fa-motorcycle me-3"></i>RIDER DOSSIER</h1>
            
            <div class="info-grid">
                <div class="info-item">
                    <label>RIDER NAME</label>
                    <div class="value"><?php echo htmlspecialchars($order['customer_name']); ?></div>
                </div>
                <div class="info-item">
                    <label>CONTACT LINE</label>
                    <div class="value"><?php echo htmlspecialchars($order['customer_phone']); ?></div>
                </div>
                <div class="info-item">
                    <label>GARAGE / ADDRESS</label>
                    <div class="value"><?php echo htmlspecialchars($order['customer_address']); ?></div>
                </div>
                <div class="info-item">
                    <label>BOOKING DATE</label>
                    <div class="value"><?php echo date('d M Y | H:i', strtotime($order['created_at'])); ?></div>
                </div>
            </div>

            <form method="POST" class="status-box">
                <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
                <span class="text-uppercase fw-bold"><i class="fas fa-tools me-2"></i>Garage Status:</span>
                <select name="status">
                    <?php
                    $status_list = ['Pending', 'In Workshop', 'Ready for Pickup', 'Delivered', 'Cancelled'];
                    foreach ($status_list as $st) {
                        $selected = ($order['status'] == $st) ? 'selected' : '';
                        echo "<option value='$st' $selected>$st</option>";
                    }
                    ?>
                </select>
                <button type="submit" name="update_status" class="btn-update">Update Log</button>
            </form>
        </div>

        <div class="glass-card">
            <h2><i class="fas fa-list-ul me-3"></i>BIKE MANIFEST</h2>
            <div class="table-responsive mt-4">
                <table class="table table-dark table-hover align-middle" style="background: transparent;">
                    <thead>
                        <tr class="text-muted border-bottom border-secondary">
                            <th>MACHINE</th>
                            <th class="text-center">UNIT PRICE</th>
                            <th class="text-center">QTY</th>
                            <th class="text-end">SUBTOTAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item): 
                            $img = getBikeImage($item['image']);
                            $price = ($item['price'] > 0) ? $item['price'] : ($item['current_bike_price'] ?? 0);
                            $sub = $price * $item['quantity'];
                        ?>
                        <tr class="border-bottom border-secondary">
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="<?php echo $img; ?>" class="bike-thumb me-3">
                                    <div>
                                        <div class="fw-bold"><?php echo htmlspecialchars($item['bike_name']); ?></div>
                                        <small class="text-muted">VIN-<?php echo str_pad($item['product_id'], 6, '0', STR_PAD_LEFT); ?></small>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center price-tag">$<?php echo number_format($price, 2); ?></td>
                            <td class="text-center"><?php echo $item['quantity']; ?></td>
                            <td class="text-end fw-bold text-white">$<?php echo number_format($sub, 2); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="total-section">
                <div class="text-muted text-uppercase small mb-2">Total Machine Value</div>
                <div class="total-value">$<?php echo number_format($order['total_price'], 2); ?></div>
            </div>
        </div>

        <div class="text-center">
            <a href="orders.php" class="back-btn">
                <i class="fas fa-arrow-left me-2"></i> Return to Garage Records
            </a>
        </div>
    </div>

    <?php include("../inc/footer.php"); ?>

    <script>
        <?php if ($update_success): ?>
            Swal.fire({
                title: 'LOG UPDATED!',
                text: 'The machine status has been successfully modified.',
                icon: 'success',
                background: '#111',
                color: '#fff',
                confirmButtonColor: '#ff003c',
                toast: true,
                position: 'top-end',
                timer: 3000,
                showConfirmButton: false
            });
        <?php endif; ?>
    </script>
</body>
</html>