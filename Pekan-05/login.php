<?php
// 1. Inisialisasi Environment
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "session_init.php";
require "koneksi.php"; 

// Pastikan variabel koneksi tersedia secara global
global $conn;

// Jika sudah login, langsung lempar ke dashboard agar tidak login dua kali
if (isset($_SESSION["login"]) && $_SESSION["login"] === true) {
    header("Location: dashboard.php");
    exit;
}

$error = "";
$success = isset($_SESSION["success"]) ? $_SESSION["success"] : "";
unset($_SESSION["success"]); // Hapus pesan setelah ditampilkan sekali

// Cek kesehatan koneksi
if (!isset($conn)) {
    die("Koneksi database tidak ditemukan. Periksa file koneksi.php!");
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["login"])) {
    $email = mysqli_real_escape_string($conn, trim($_POST["email"]));
    $password = trim($_POST["password"]);

    // --- OPSIONAL: LOGIN ADMIN MANUAL (Hardcoded) ---
    // Akun bypass ini berguna jika database sedang bermasalah
    $adminEmail = "admin@cybervault.com";
    $adminPassword = "admin123";

    if ($email === $adminEmail && $password === $adminPassword) {
        $_SESSION["login"] = true;
        $_SESSION["role"] = "admin";
        $_SESSION["nama"] = "Administrator Utama";
        $_SESSION["email"] = $adminEmail;
        header("Location: dashboard.php");
        exit;
    }

    $query = "SELECT * FROM datauser WHERE email = '$email'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);

        if (password_verify($password, $row["password"])) {
            $_SESSION["login"] = true;
            $_SESSION["id_user"] = $row["id"];
            $_SESSION["nama"] = $row["name"];
            $_SESSION["email"] = $row["email"];
            
            $_SESSION["role"] = (!empty($row["role"])) ? $row["role"] : "user";

            header("Location: dashboard.php");
            exit;
        }
    }

    $error = "Email atau password salah!";
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
        /* Tambahan style jika belum ada di HomePage.css untuk pesan alert */
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
    <header class="main-header">
        <nav class="nav-bar">
            <div class="logo">CyberVault</div>
            <ul class="nav-links">
                <li><a href="daftar.php">Daftar Akun</a></li>
            </ul>
        </nav>
    </header>

    <div class="login-wrapper">
        <div class="image-box">
            <img src="../PertemuanMinggu_3/cybersecurity_NicoElNino-AlamyStockPhoto.jpg" alt="Cyber Security Login">
        </div>
        
        <div class="form-box">
            <div class="top">
                <img src="../PertemuanMinggu_3/0x0-1024x576 (1).webp" alt="Logo CyberVault" style="width: 80px; margin-bottom: 10px;">
                <h2>CyberVault</h2>
                <p>Masuk ke akun Anda untuk mengakses dashboard keamanan modern.</p>
            </div>

            <?php if ($success !== ""): ?>
                <div class="success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>

            <?php if ($error !== ""): ?>
                <div class="error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form action="" method="POST">
                <div class="input-group">
                    <input type="email" name="email" class="input" placeholder="Alamat email" required autocomplete="email">
                </div>
                <div class="input-group">
                    <input type="password" name="password" class="input" placeholder="Password" required autocomplete="current-password">
                </div>
                <button type="submit" name="login" class="btn-login">Masuk Sekarang</button>
            </form>

            <p class="helper-text">Belum punya akses? <a href="daftar.php">Daftar sekarang</a></p>
        </div>
    </div>

    <footer class="cyber-footer">
        <div class="footer-container">
            <div class="footer-section">
                <h3 class="footer-logo">CyberVault</h3>
                <p>Edukasi keamanan siber terpercaya untuk melindungi privasi Anda di era digital.</p>
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
            <p>&copy; 2026 CyberVault Project - S1 Sistem Informasi Kota Cerdas.</p>
        </div>
    </footer>
</body>
</html>