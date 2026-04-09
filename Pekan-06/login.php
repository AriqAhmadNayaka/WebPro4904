<?php
// ===============================
// 1. Inisialisasi Environment
// ===============================
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "session_init.php";
require_once "Class/Auth.php";

// ===============================
// 2. Inisialisasi Class Auth
// ===============================
$auth = new Auth();

$error = "";
$success = $_SESSION["success"] ?? "";
unset($_SESSION["success"]);

// ===============================
// 3. Jika sudah login → dashboard
// ===============================
if ($auth->isLoggedIn()) {
    header("Location: dashboard.php");
    exit;
}

// ===============================
// 4. Proses Login
// ===============================
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["login"])) {

    $email = $_POST["email"];
    $password = $_POST["password"];

    if ($auth->login($email, $password)) {
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Email atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CyberVault</title>

    <link rel="stylesheet" href="HomePage.css">

    <style>
        .error {
            background: rgba(255, 71, 87, 0.1);
            color: #ff4757;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ff4757;
            margin-bottom: 15px;
            font-size: 13px;
            text-align: center;
        }

        .success {
            background: rgba(0, 212, 255, 0.1);
            color: #00d4ff;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #00d4ff;
            margin-bottom: 15px;
            font-size: 13px;
            text-align: center;
        }
    </style>
</head>

<body>

    <!-- ================= HEADER ================= -->
    <header class="main-header">
        <nav class="nav-bar">
            <div class="logo">CyberVault</div>

            <ul class="nav-links">
                <li><a href="daftar.php">Daftar Akun</a></li>
            </ul>
        </nav>
    </header>


    <!-- ================= LOGIN ================= -->
    <div class="login-wrapper">

        <div class="image-box">
            <img src="cybersecurity_NicoElNino-AlamyStockPhoto.jpg"
                 alt="Cyber Security Login">
        </div>

        <div class="form-box">

            <div class="top">

                <img src="0x0-1024x576 (1).webp"
                     alt="Logo CyberVault"
                     style="width:80px;margin-bottom:10px;">

                <h2>CyberVault</h2>

                <p>
                    Masuk ke akun Anda untuk mengakses
                    dashboard keamanan modern.
                </p>

            </div>


            <!-- SUCCESS -->
            <?php if ($success !== ""): ?>
                <div class="success">
                    <?php echo htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>


            <!-- ERROR -->
            <?php if ($error !== ""): ?>
                <div class="error">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>


            <!-- FORM LOGIN -->
            <form action="" method="POST">

                <div class="input-group">
                    <input type="email"
                           name="email"
                           class="input"
                           placeholder="Alamat email"
                           required
                           autocomplete="email">
                </div>

                <div class="input-group">
                    <input type="password"
                           name="password"
                           class="input"
                           placeholder="Password"
                           required
                           autocomplete="current-password">
                </div>

                <button type="submit"
                        name="login"
                        class="btn-login">
                    Masuk Sekarang
                </button>

            </form>

            <p class="helper-text">
                Belum punya akses?
                <a href="daftar.php">Daftar sekarang</a>
            </p>

        </div>
    </div>


    <!-- ================= FOOTER ================= -->
    <footer class="cyber-footer">

        <div class="footer-container">

            <div class="footer-section">
                <h3 class="footer-logo">CyberVault</h3>
                <p>
                    Edukasi keamanan siber terpercaya
                    untuk melindungi privasi Anda di era digital.
                </p>
            </div>

            <div class="footer-section">
                <h4>Support</h4>
                <ul>
                    <li>Bandung, Indonesia</li>
                    <li>support@cybervault.id</li>
                </ul>
            </div>

            <div class="footer-section">
                <h4>Social</h4>

                <div class="social-links">
                    <a href="#" class="social-item">Instagram</a>
                    <a href="#" class="social-item">GitHub</a>
                </div>
            </div>

        </div>

        <div class="footer-bottom">
            <p>
                &copy; 2026 CyberVault Project -
                S1 Sistem Informasi Kota Cerdas.
            </p>
        </div>

    </footer>

</body>
</html>