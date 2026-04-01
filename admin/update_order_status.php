<?php
include("../inc/config.php");
include("../admin_auth.php");

if (isset($_POST['order_id'], $_POST['status'])) {
    $stmt = $pdo->prepare("UPDATE orders SET status=? WHERE id=?");
    $stmt->execute([$_POST['status'], $_POST['order_id']]);
    echo "success";
} else {
    echo "error";
}
