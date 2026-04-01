<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once("./inc/config.php");

$page_title = "Admin Gateway | Secure Access";
$error = "";

if (isset($_POST['login'])) {
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = trim($_POST['password']);

    if (!empty($email) && !empty($password)) {
        $stmt = $pdo->prepare("SELECT * FROM admins WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($admin && md5($password) == $admin['password']) {
            session_regenerate_id(true);
            $_SESSION['admin'] = $admin['email'];
            $_SESSION['admin_id'] = $admin['id'];
            header("Location: admin/dashboard.php");
            exit();
        } else {
            $error = "AUTHENTICATION_FAILED: UNRECOGNIZED_CREDENTIALS";
        }
    } else {
        $error = "REQUIRED_FIELDS_MISSING";
    }
}

include("inc/header.php");
?>

<link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;900&family=JetBrains+Mono:wght@300;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    :root {
        --neon-red: #ff0000;
        --dark-void: #050505;
        --panel-bg: #0a0a0a;
    }

    .login-wrapper {
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 40px 20px;
        background: var(--dark-void);
        font-family: 'JetBrains Mono', monospace;
    }

    .neon-card {
        position: relative;
        width: 100%;
        max-width: 480px;
        background: #111;
        border-radius: 2px;
        padding: 2px;
        overflow: hidden;
        box-shadow: 0 0 40px rgba(0,0,0,0.8);
    }

    .neon-card::before {
        content: "";
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: conic-gradient(transparent, var(--neon-red), transparent 40%);
        animation: rotate-neon 3s linear infinite;
    }

    @keyframes rotate-neon {
        100% { transform: rotate(360deg); }
    }

    .login-box {
        position: relative;
        z-index: 1;
        background: var(--panel-bg);
        border-radius: 1px;
        padding: 50px 40px;
    }

    .login-box h2 {
        text-align: left;
        margin-bottom: 40px;
        color: #fff;
        font-family: 'Orbitron', sans-serif;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 3px;
        border-left: 4px solid var(--neon-red);
        padding-left: 15px;
    }

    .input-group-custom {
        position: relative;
        margin-bottom: 30px;
    }

    .form-control {
        width: 100%;
        background: #000 !important;
        border: 1px solid #222;
        border-radius: 0;
        padding: 18px 50px 18px 55px;
        color: #fff !important;
        font-size: 0.9rem;
        transition: 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .form-control:focus {
        border-color: var(--neon-red);
        box-shadow: none;
        outline: none;
        background: #050505 !important;
    }

    .input-icon {
        position: absolute;
        left: 20px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--neon-red);
        font-size: 1.1rem;
        pointer-events: none;
        z-index: 2;
    }

    .toggle-password {
        position: absolute;
        right: 20px;
        top: 50%;
        transform: translateY(-50%);
        color: #444;
        cursor: pointer;
        transition: 0.3s;
        z-index: 2;
    }

    .toggle-password:hover {
        color: var(--neon-red);
    }

    .action-container {
        display: flex;
        flex-direction: column;
        gap: 15px;
        margin-top: 40px;
    }

    .btn-neon-submit {
        background: var(--neon-red);
        border: none;
        color: #fff !important;
        padding: 20px;
        font-family: 'Orbitron', sans-serif;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 2px;
        clip-path: polygon(0 0, 100% 0, 95% 100%, 0 100%);
        transition: 0.3s;
        cursor: pointer;
    }

    .btn-neon-submit:hover {
        background: #fff;
        color: #000 !important;
        transform: translateY(-2px);
    }

    .btn-back-home {
        text-align: center;
        color: #444 !important;
        text-decoration: none;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: 0.3s;
    }

    .btn-back-home:hover {
        color: var(--neon-red) !important;
    }

    .error-alert {
        background: rgba(255, 0, 0, 0.05);
        border-left: 3px solid var(--neon-red);
        color: var(--neon-red);
        padding: 15px;
        margin-bottom: 30px;
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        animation: glitch 0.3s ease;
    }

    @keyframes glitch {
        0% { transform: translateX(-5px); opacity: 0.5; }
        50% { transform: translateX(5px); opacity: 1; }
        100% { transform: translateX(0); }
    }
</style>

<div class="login-wrapper">
    <div class="neon-card">
        <div class="login-box">
            <h2>SYSTEM_ADMIN</h2>

            <?php if ($error): ?>
                <div class="error-alert">
                    <i class="fas fa-exclamation-triangle me-2"></i> <?= htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form method="POST" autocomplete="off">
                <div class="input-group-custom">
                    <i class="fas fa-user-shield input-icon"></i>
                    <input type="email" name="email" class="form-control" placeholder="IDENTIFIER" autocomplete="username" required>
                </div>

                <div class="input-group-custom">
                    <i class="fas fa-fingerprint input-icon"></i>
                    <input type="password" name="password" id="passwordInput" class="form-control" placeholder="ACCESS_KEY" autocomplete="new-password" required>
                    <i class="fas fa-eye toggle-password" id="toggleIcon"></i>
                </div>

                <div class="action-container">
                    <button type="submit" name="login" class="btn-neon-submit">
                        INITIATE_SESSION <i class="fas fa-arrow-right ms-2"></i>
                    </button>
                    
                    <a href="index.php" class="btn-back-home">
                        <i class="fas fa-terminal me-2"></i> ABORT_LOGON
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    const passwordInput = document.getElementById('passwordInput');
    const toggleIcon = document.getElementById('toggleIcon');

    toggleIcon.addEventListener('click', function () {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        this.classList.toggle('fa-eye');
        this.classList.toggle('fa-eye-slash');
    });

    window.onload = () => {
        const inputs = document.querySelectorAll('.form-control');
        inputs.forEach(input => {
            input.value = '';
        });
    };
</script>

<?php include("./inc/footer.php"); ?>