<?php
ob_start();
include("../inc/config.php");
include("../inc/admin_header.php");
$current_page = "edit_bike";

$product = null;
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $product_id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_product']) && $product) {
    $name = htmlspecialchars($_POST['name']);
    $price = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $imageName = $product['image'];

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploads_dir = '../uploads/bikes/'; // المسار الموحد للموتوسيكلات
        $file_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $new_file_name = 'BIKE_UPD_' . time() . '_' . uniqid() . '.' . $file_extension;
        $target = $uploads_dir . $new_file_name;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            // مسح الصورة القديمة لو حابب توفر مساحة (اختياري)
            // if ($product['image'] && file_exists($uploads_dir . $product['image'])) { unlink($uploads_dir . $product['image']); }
            $imageName = $new_file_name;
        }
    }

    $update = $pdo->prepare("UPDATE products SET name = ?, price = ?, image = ? WHERE id = ?");
    if ($update->execute([$name, $price, $imageName, $product_id])) {
        header("Location: products.php?status=updated");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <title>Edit Bike | RPM Motorsports</title>

    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;900&family=Barlow:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --rpm-red: #ff0000;
            --dark-carbon: #0a0a0a;
            --glass-panel: rgba(15, 15, 15, 0.98);
        }

        body {
            font-family: 'Barlow', sans-serif;
            background-color: var(--dark-carbon);
            color: white;
            overflow-x: hidden;
            background: radial-gradient(circle at center, #1a1a1a 0%, #050505 100%);
        }

        .admin-content { padding: 100px 20px 60px; }

        .admin-header h1 {
            font-family: 'Orbitron', sans-serif;
            font-weight: 900;
            font-size: 2.2rem;
            color: #fff;
            text-transform: uppercase;
            text-shadow: 2px 2px var(--rpm-red);
            margin-bottom: 5px;
        }

        .form-container {
            background: var(--glass-panel);
            border-radius: 0 20px 20px 0;
            padding: 40px;
            border-left: 6px solid var(--rpm-red);
            max-width: 700px;
            margin: 0 auto;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.8);
        }

        .form-label {
            font-family: 'Orbitron', sans-serif;
            color: #ccc;
            font-size: 0.8rem;
            letter-spacing: 1px;
            margin-bottom: 12px;
        }

        .form-control {
            background: #151515 !important;
            border: 1px solid #333 !important;
            color: #fff !important;
            padding: 14px;
            border-radius: 5px;
            transition: 0.3s;
        }

        .form-control:focus {
            border-color: var(--rpm-red) !important;
            box-shadow: 0 0 15px rgba(255, 0, 0, 0.15);
        }

        .current-image-wrapper {
            background: #000;
            padding: 10px;
            border: 1px dashed #444;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .current-image {
            border-radius: 5px;
            filter: grayscale(20%);
            transition: 0.3s;
        }

        .current-image:hover { filter: grayscale(0%); }

        .action-buttons-container {
            display: flex;
            gap: 20px;
            margin-top: 35px;
        }

        .btn-submit {
            background: var(--rpm-red);
            color: #fff;
            border: none;
            flex: 2;
            font-family: 'Orbitron', sans-serif;
            font-weight: 800;
            padding: 15px;
            clip-path: polygon(0 0, 100% 0, 95% 100%, 0% 100%);
            transition: 0.3s;
        }

        .btn-submit:hover {
            background: #fff;
            color: var(--rpm-red);
            transform: scale(1.02);
        }

        .btn-back {
            background: #222;
            color: #fff;
            flex: 1;
            text-align: center;
            text-decoration: none;
            padding: 15px;
            font-family: 'Barlow', sans-serif;
            font-weight: 700;
            border-radius: 5px;
            transition: 0.3s;
        }

        .btn-back:hover { background: #333; color: #fff; }

        @media (max-width: 576px) {
            .action-buttons-container { flex-direction: column; }
            .btn-submit { clip-path: none; border-radius: 5px; }
        }
    </style>
</head>

<body>

    <div class="admin-content">
        <div class="container">
            <div class="admin-header text-center mb-5">
                <h1><i class="fas fa-tools me-2"></i>Tune Bike Specs</h1>
                <p class="text-muted">Modify technical details and pricing for this machine</p>
            </div>

            <?php if (!$product): ?>
                <div class="alert alert-danger text-center bg-dark border-danger text-danger py-4">
                    <i class="fas fa-ghost fa-2x mb-3 d-block"></i>
                    This bike has left the garage (Invalid ID).
                </div>
                <div class="text-center mt-3"><a href="products.php" class="btn-back">Back to Garage</a></div>
            <?php else: ?>
                <div class="form-container">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="mb-4">
                            <label class="form-label"><i class="fas fa-motorcycle me-2 text-danger"></i>Bike Model / Name</label>
                            <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($product['name']); ?>" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label"><i class="fas fa-tag me-2 text-danger"></i>Showroom Price ($)</label>
                            <input type="number" step="0.01" name="price" class="form-control" value="<?php echo htmlspecialchars($product['price']); ?>" required>
                        </div>

                        <div class="mb-4 text-center">
                            <label class="form-label text-start d-block"><i class="fas fa-image me-2 text-danger"></i>Current Visual</label>
                            <div class="current-image-wrapper">
                                <img src="../uploads/bikes/<?php echo $product['image']; ?>" width="180" class="current-image" alt="Bike Photo">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label"><i class="fas fa-upload me-2 text-danger"></i>Replace Image</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                            <small class="text-muted mt-2 d-block">Keep empty if you don't want to change the visual asset.</small>
                        </div>

                        <div class="action-buttons-container">
                            <a href="products.php" class="btn-back">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                            <button type="submit" name="update_product" class="btn-submit">
                                <i class="fas fa-check-circle me-2"></i>SAVE SPECIFICATIONS
                            </button>
                        </div>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php include("../inc/footer.php"); ?>
</body>

</html>