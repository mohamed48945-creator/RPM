<?php
include("../inc/config.php");
include("../inc/admin_header.php"); 
$current_page = "products";

$stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
$products = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Garage Inventory | RPM Motorsports</title>

    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Barlow:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <style>
        :root {
            --rpm-red: #ff0000;
            --rpm-black: #050505;
            --rpm-card-bg: #121212; /* لون خلفية المنتجات الجديد - غامق جداً */
            --rpm-border: rgba(255, 0, 0, 0.2);
        }

        body {
            font-family: 'Barlow', sans-serif;
            background-color: var(--rpm-black);
            color: white;
        }

        .admin-header h1 {
            font-family: 'Orbitron', sans-serif;
            color: var(--rpm-red);
            text-transform: uppercase;
            border-left: 4px solid var(--rpm-red);
            padding-left: 15px;
        }

        /* حاوية المنتجات - حل مشكلة اللون الأبيض */
        .products-wrapper {
            background-color: var(--rpm-card-bg);
            border: 1px solid var(--rpm-border);
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.7);
        }

/* تعديل الجدول ليكون غامق بالكامل */
.table {
    color: #ffffff !important; /* لون النص أبيض */
    --bs-table-bg: transparent !important; /* إلغاء خلفية بوتستراب البيضاء */
    --bs-table-color: white !important;
}

.table tbody tr {
    background-color: #1a1a1a !important; /* لون الصفوف رمادي غامق جداً */
    border-bottom: 1px solid #333 !important;
}

.table tbody tr:hover {
    background-color: #252525 !important; /* تفتيح بسيط عند الوقوف بالماوس */
}

/* تعديل الـ Header الخاص بالجدول */
.table thead th {
    background-color: #111 !important;
    color: var(--rpm-red) !important;
    border-bottom: 2px solid var(--rpm-red) !important;
    padding: 15px !important;
}

/* تأكيد أن الحاوية غامقة */
.products-wrapper {
    background-color: #121212 !important;
    border: 1px solid #333;
    padding: 20px;
    border-radius: 15px;
}

        /* صفوف المنتجات */
        .product-row {
            background-color: transparent;
            border-bottom: 1px solid #222;
            transition: all 0.3s ease;
        }

        .product-row:hover {
            background-color: #1d1d1d !important; /* لون خفيف عند الوقوف بالماوس */
        }

        .product-row td {
            padding: 20px 15px;
            vertical-align: middle;
            border: none;
        }

        .bike-thumb {
            width: 70px;
            height: 50px;
            object-fit: cover;
            border-radius: 6px;
            border: 1px solid #333;
        }

        .price-highlight {
            color: var(--rpm-red);
            font-weight: 700;
            font-family: 'Orbitron', sans-serif;
        }

        /* الأزرار */
        .btn-action {
            width: 38px;
            height: 38px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            margin: 0 4px;
            transition: 0.3s;
            text-decoration: none;
        }

        .edit-tool {
            background: rgba(52, 152, 219, 0.2);
            color: #3498db;
            border: 1px solid #3498db;
        }

        .delete-tool {
            background: rgba(255, 0, 0, 0.1);
            color: var(--rpm-red);
            border: 1px solid var(--rpm-red);
        }

        .btn-action:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }

        .edit-tool:hover { background: #3498db; color: white; }
        .delete-tool:hover { background: var(--rpm-red); color: white; }

        .btn-add-main {
            background: var(--rpm-red);
            color: white;
            font-family: 'Orbitron', sans-serif;
            padding: 10px 20px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: bold;
            display: inline-block;
        }
    </style>
</head>

<body>

    <div class="container my-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="admin-header">
                <h1>Garage Fleet</h1>
            </div>
            <a href="add_product.php" class="btn-add-main">
                <i class="fas fa-plus"></i> NEW MACHINE
            </a>
        </div>

        <div class="products-wrapper">
            <?php if (count($products) > 0): ?>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#ID</th>
                                <th>Preview</th>
                                <th>Model</th>
                                <th>Pricing</th>
                                <th class="text-center">Modify</th>
                            </tr>
                        </thead>
<tbody>
    <?php foreach ($products as $product): ?>
        <tr class="product-row">
            <td><span class="text-muted">#<?php echo $product['id']; ?></span></td>
            
            <td>
                <?php 
                $img_name = $product['image'];
                $img_path = "../uploads/bikes/" . $img_name;
                if (!empty($img_name) && file_exists($img_path)): ?>
                    <img src="<?php echo $img_path; ?>" class="bike-thumb" alt="Bike">
                <?php else: ?>
                    <div class="bike-thumb d-flex align-items-center justify-content-center bg-dark" style="border: 1px dashed #444;">
                        <i class="fas fa-motorcycle text-muted"></i>
                    </div>
                <?php endif; ?>
            </td>

            <td><span class="fw-bold"><?php echo htmlspecialchars($product['name']); ?></span></td>
            <td><span class="text-danger fw-bold" style="font-family: 'Orbitron';">
                <?php echo number_format($product['price']); ?> EGP</span>
            </td>
            <td class="text-center">
                <a href="edit_product.php?id=<?php echo $product['id']; ?>" class="btn btn-outline-info btn-sm me-2"><i class="fas fa-edit"></i></a>
                <a href="delete_product.php?id=<?php echo $product['id']; ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Delete this bike?')"><i class="fas fa-trash"></i></a>
            </td>
        </tr>
    <?php endforeach; ?>
</tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-exclamation-triangle fa-3x mb-3 text-muted"></i>
                    <p class="text-muted">No machines found in the garage.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php include("../inc/footer.php"); ?>
</body>
</html>