<?php
// 1. PHP logic must be first in file
include("../inc/config.php");

if (isset($_POST['add'])) {
    $bike_name = htmlspecialchars($_POST['bike_name']); 
    $price     = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $category  = htmlspecialchars($_POST['category']); 

    // Process uploaded image
    $imageName = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $uploads_dir = '../uploads/bikes/';
        $allowed_types = ['image/jpeg', 'image/png', 'image/webp'];
        
        if (in_array($_FILES['image']['type'], $allowed_types)) {
            if (!is_dir($uploads_dir)) {
                mkdir($uploads_dir, 0777, true);
            }
            $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $imageName = 'BIKE_' . time() . '_' . uniqid() . '.' . $extension;
            
            move_uploaded_file($_FILES['image']['tmp_name'], $uploads_dir . $imageName);
        }
    }

    // Insert data into database
    // تأكد أن جدول الـ products يحتوي على أعمدة (name, price, image, category) أو عدلها حسب جدولك
    $stmt = $pdo->prepare("INSERT INTO products(name, price, image, category) VALUES(?,?,?,?)");
    if ($stmt->execute([$bike_name, $price, $imageName, $category])) {
        header("Location: products.php?msg=bike_added");
        exit;
    }
}

include("../inc/admin_header.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/custom.css"> 
    <title>RPM Showroom | Add New Bike</title>

    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;900&family=Barlow:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <style>
        :root {
            --rpm-red: #ff0000;
            --carbon: #111;
        }

        .admin-content {
            padding: 60px 15px;
            background: radial-gradient(circle at top right, #1a1a1a, #0a0a0a);
        }

        .showroom-card {
            background: rgba(0, 0, 0, 0.7);
            border-left: 5px solid var(--rpm-red);
            border-radius: 0 15px 15px 0;
            padding: 40px;
            box-shadow: 0 10px 50px rgba(0,0,0,0.5);
            max-width: 800px;
            margin: 0 auto;
        }

        .form-label {
            font-family: 'Orbitron', sans-serif;
            color: #eee;
            font-size: 0.85rem;
            letter-spacing: 1px;
            margin-bottom: 10px;
        }

        .form-control, .form-select {
            background: #1a1a1a !important;
            border: 1px solid #333 !important;
            color: #fff !important;
            padding: 12px;
            transition: 0.3s;
        }

        .form-control:focus {
            border-color: var(--rpm-red) !important;
            box-shadow: 0 0 15px rgba(255, 0, 0, 0.2);
        }

        .btn-add-bike {
            background: var(--rpm-red);
            color: #fff;
            font-family: 'Orbitron', sans-serif;
            font-weight: 900;
            text-transform: uppercase;
            padding: 15px;
            width: 100%;
            border: none;
            clip-path: polygon(5% 0, 100% 0, 95% 100%, 0 100%);
            transition: 0.3s;
        }

        .btn-add-bike:hover {
            background: #fff;
            color: var(--rpm-red);
            transform: scale(1.02);
        }

        .section-title {
            font-family: 'Orbitron', sans-serif;
            font-weight: 900;
            color: #fff;
            text-shadow: 2px 2px var(--rpm-red);
            margin-bottom: 30px;
        }
    </style>
</head>

<body>
    <div class="admin-content">
        <div class="container">
            <div class="text-center">
                <h1 class="section-title">NEW BIKE ARRIVAL</h1>
                <p class="text-muted mb-5">Add a new beast to the showroom collection</p>
            </div>

            <div class="showroom-card">
                <form method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-7 mb-4">
                            <label class="form-label"><i class="fas fa-motorcycle text-red"></i> Bike Model Name</label>
                            <input type="text" name="bike_name" class="form-control" placeholder="e.g. Kawasaki Ninja H2" required>
                        </div>

                        <div class="col-md-5 mb-4">
                            <label class="form-label"><i class="fas fa-filter text-red"></i> Category</label>
                            <select name="category" class="form-select">
                                <option value="Sport">Sport Bike</option>
                                <option value="Cruiser">Cruiser</option>
                                <option value="Adventure">Adventure</option>
                                <option value="Scooter">Scooter</option>
                            </select>
                        </div>

                        <div class="col-md-12 mb-4">
                            <label class="form-label"><i class="fas fa-tag text-red"></i> List Price ($)</label>
                            <input type="number" step="0.01" name="price" class="form-control" placeholder="Enter Amount" required>
                        </div>

                        <div class="col-md-12 mb-4">
                            <label class="form-label"><i class="fas fa-image text-red"></i> Showcase Image</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" name="add" class="btn-add-bike">
                            <i class="fas fa-plus"></i> ADD TO SHOWROOM
                        </button>
                    </div>
                    
                    <div class="text-center mt-3">
                        <a href="products.php" class="text-white text-decoration-none" style="font-size: 0.8rem; opacity: 0.6;">
                            <i class="fas fa-chevron-left"></i> Back to Inventory
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php include("../inc/footer.php"); ?>
</body>
</html>