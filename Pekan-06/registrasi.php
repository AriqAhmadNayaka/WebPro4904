<?php
$title = "Daftar - WeBandoo+";
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title><?php echo $title; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="assets/css/registrasi.css">
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
                <h1>Buat Akun</h1>
                <p>Daftarkan diri Anda untuk pengalaman lebih baik.</p>
            </div>

            <?php if (isset($_GET['status']) && $_GET['status'] === 'register-failed') { ?>
                <div class="form-alert form-alert-error">Pendaftaran gagal. Coba lagi atau gunakan email lain.</div>
            <?php } ?>

            <form action="proses_register.php" method="POST">
                <div class="input-box">
                    <input type="text" name="nama" placeholder="Nama Lengkap" required>
                </div>

                <div class="input-box">
                    <input type="email" name="email" placeholder="Email" required>
                </div>

                <div class="input-box pass-container">
                    <input type="password" name="password" id="password" placeholder="Password" required>
                    <i class="fa-regular fa-eye toggle-pass" id="togglePassword"></i>
                </div>

                <button type="submit" class="btn">Daftar Sekarang</button>
            </form>

            <div class="register">
                Sudah punya akun? <a href="login.php">Masuk di sini</a>
            </div>
        </div>
    </div>

    <script src="assets/js/registrasi.js"></script>
</body>

</html>
