<?php
$title = "Daftar Assesment";
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?php echo $title; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="assets/css/login.css">
</head>

<body>
        <div class="login-container">
            <div class="logo-header">
                <span>Smart TransMonitor</span>
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

</body>