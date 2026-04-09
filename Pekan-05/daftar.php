<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "session_init.php";
require "koneksi.php"; 

global $conn;

if (isset($_SESSION["login"]) && $_SESSION["login"] === true) {
    header("Location: dashboard.php");
    exit;
}

$error = "";

if (!isset($conn)) {
    die("Gagal memuat koneksi database. Periksa file koneksi.php!");
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["daftar"])) {
    $nama = mysqli_real_escape_string($conn, trim($_POST["nama"]));
    $email = mysqli_real_escape_string($conn, trim($_POST["email"]));
    $passwordInput = trim($_POST["password"]);
    $confirmPassword = trim($_POST["confirm_password"]);

    if ($nama === "" || $email === "" || $passwordInput === "" || $confirmPassword === "") {
        $error = "Semua field wajib diisi!";
    } elseif ($passwordInput !== $confirmPassword) {
        $error = "Konfirmasi password tidak cocok!";
    } elseif (strlen($passwordInput) < 6) {
        $error = "Password minimal 6 karakter!";
    } else {
        $cek = mysqli_query($conn, "SELECT id FROM datauser WHERE email = '$email'");

        if ($cek && mysqli_num_rows($cek) > 0) {
            $error = "Email sudah terdaftar! Gunakan email lain.";
        } else {
            $passwordHash = password_hash($passwordInput, PASSWORD_DEFAULT);
            
            $sql = "INSERT INTO datauser (name, email, password, role) 
                    VALUES ('$nama', '$email', '$passwordHash', 'user')";

            if (mysqli_query($conn, $sql)) {
                $_SESSION["success"] = "Pendaftaran akun $nama berhasil! Silakan login.";
                header("Location: login.php");
                exit;
            } else {
                $error = "Sistem sibuk: " . mysqli_error($conn);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - CyberVault</title>
    <link rel="stylesheet" href="HomePage.css">
    <style>
        .error {
            background: rgba(255, 71, 87, 0.1);
            color: #ff4757;
            padding: 12px;
            border-radius: 6px;
            border: 1px solid #ff4757;
            margin-bottom: 20px;
            font-size: 13px;
            text-align: center;
        }
        .input-group { margin-bottom: 15px; }
    </style>
</head>
<body>
    <header class="main-header">
        <nav class="nav-bar">
            <div class="logo">CyberVault</div>
            <ul class="nav-links">
                <li><a href="login.php">Masuk</a></li>
            </ul>
        </nav>
    </header>

    <div class="login-wrapper">
        <div class="image-box">
            <img src="../PertemuanMinggu_3/cybersecurity_NicoElNino-AlamyStockPhoto.jpg" alt="Cyber Security Registration">
        </div>
        
        <div class="form-box">
            <div class="top">
                <img src="../PertemuanMinggu_3/0x0-1024x576 (1).webp" alt="Logo CyberVault" style="width: 80px; margin-bottom: 10px;">
                <h2>Registrasi Akun</h2>
                <p>Bergabunglah dengan CyberVault untuk pengalaman keamanan digital yang lebih baik.</p>
            </div>

            <?php if ($error !== ""): ?>
                <div class="error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form action="" method="POST">
                <div class="input-group">
                    <input type="text" name="nama" class="input" placeholder="Nama lengkap" required value="<?= isset($_POST['nama']) ? htmlspecialchars($_POST['nama']) : '' ?>">
                </div>
                <div class="input-group">
                    <input type="email" name="email" class="input" placeholder="Alamat email" required value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
                </div>
                <div class="input-group">
                    <input type="password" name="password" class="input" placeholder="Password (Min. 6 karakter)" required>
                </div>
                <div class="input-group">
                    <input type="password" name="confirm_password" class="input" placeholder="Konfirmasi password" required>
                </div>
                <button type="submit" name="daftar" class="btn-login">Daftar Sekarang</button>
            </form>

            <p class="helper-text">Sudah memiliki akun? <a href="login.php">Masuk di sini</a></p>
        </div>
    </div>

    <footer class="cyber-footer">
        <div class="footer-container">
            <div class="footer-section">
                <h3 class="footer-logo">CyberVault</h3>
                <p>Melindungi data dan privasi Anda melalui edukasi keamanan siber yang modern.</p>
            </div>
            <div class="footer-section">
                <h4>Support</h4>
                <ul>
                    <li>Bandung, Indonesia</li>
                    <li>support@cybervault.id</li>
                </ul>
            </div>
            <div class="footer-section">
                <h4>Connect</h4>
                <div class="social-links">
                    <a href="#" class="social-item">Instagram</a>
                    <a href="#" class="social-item">GitHub</a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2026 CyberVault Project - Kelompok 10 SIKC. All Rights Reserved.</p>
        </div>
    </footer>
</body>
</html>