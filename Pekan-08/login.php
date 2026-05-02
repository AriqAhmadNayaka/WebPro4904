<?php
$title = "Login - Bandung Heritage";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?php echo $title; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="assets/css/login.css">
</head>
<body>

<div class="outer-container">
    <div class="image-panel">
        <div id="slideshow-bg"></div>
        <div class="image-content">
            <h3 id="slide-title">Jelajahi Warisan Bandung</h3>
            <p id="slide-description">Temukan sejarah, seni, dan budaya yang kaya di Kota Kembang.</p>
        </div>
    </div>

    <div class="login-container">
        <div class="logo-header">
            <span>WeBandoo+</span>
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

        <form action="proses_login.php" method="POST">
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
            Belum punya akun? <a href="registrasi.php">Daftar sekarang</a>
        </div>
    </div>
</div>

<script src="assets/js/login.js"></script>
</body>
</html>
