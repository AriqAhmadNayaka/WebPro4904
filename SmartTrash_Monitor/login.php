<?php
$title = "Login Assesment";
session_start();
require_once 'koneksi.php';

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?php echo $title; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="assets/css/login.css">
</head>

    <div class="login-container">
        <div class="logo-header">
            <span>Smart TransMonitor</span>
        </div>
        
        <div class="greeting">
            <h1>Selamat Datang</h1>
            <p>Masukkan data diri anda untuk mengakses layanan.</p>
        </div>

        <?php if (isset($_GET['status']) && $_GET['status'] === 'login-failed') { ?>
            <div class="form-alert form-alert-error">Login gagal. Email atau password tidak sesuai.</div>
        <?php } ?>

        <?php if (isset($_GET['status']) && $_GET['status'] === 'register-success') { ?>
            <div class="form-alert form-alert-success">Akun berhasil dibuat. Silakan masuk menggunakan akun Anda.</div>
        <?php } ?>

        <form action="proses.login.php" method="POST">
            <div class="input-box">
                <input type="email" name="email" placeholder="Email" required>
            </div>

            <div class="input-box pass-container">
                <input type="password" id="password" name="password" placeholder="Password" required>
                <i class="fa-regular fa-eye toggle-pass" id="togglePassword"></i>
            </div>
            
            <div class="forgot-password">
                <a href="#">Lupa Kata Sandi?</a>
            </div>

            <button type="submit" class="btn">Masuk</button> 
        </form>

        <div class="register">
            Belum punya akun? <a href="register.php">Daftar sekarang</a>
        </div>
    </div>
</div>
</body>
