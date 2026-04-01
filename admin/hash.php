<?php
$password = "7amo";

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$is_correct = password_verify("7amo", $hashed_password);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Source+Code+Pro&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #0a0a0a; color: #eee; font-family: 'Barlow', sans-serif; padding: 50px; }
        .hash-container {
            background: #111;
            border-left: 4px solid #ff0000;
            padding: 25px;
            border-radius: 0 10px 10px 0;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            max-width: 800px;
            margin: 0 auto;
        }
        h3 { font-family: 'Orbitron', sans-serif; color: #ff0000; font-size: 1.2rem; margin-bottom: 20px; text-transform: uppercase; }
        .hash-box {
            background: #000;
            color: #05d9e8; /* Neon Blue */
            font-family: 'Source Code Pro', monospace;
            padding: 15px;
            border: 1px solid #222;
            border-radius: 5px;
            word-break: break-all;
            font-size: 1.1rem;
            margin-bottom: 15px;
        }
        .status { font-size: 0.9rem; color: #888; }
        .status i { color: #2ecc71; margin-right: 5px; }
    </style>
</head>
<body>

    <div class="hash-container">
        <h3><i class="fas fa-user-shield"></i> RPM Encryption Engine</h3>
        
        <p>Raw Password: <strong><?php echo $password; ?></strong></p>
        
        <div class="hash-box">
            <?php echo $hashed_password; ?>
        </div>

        <div class="status">
            <i class="fas fa-check-double"></i> 
            Algorithm: BCRYPT (Secure) | 
            Verification Test: <?php echo $is_correct ? '<span style="color:#2ecc71">Success</span>' : 'Failed'; ?>
        </div>
    </div>

</body>
</html>