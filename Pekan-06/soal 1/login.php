<?php
// Memuat file inisialisasi (autoloader & session)
require_once 'config/init.php';

// Proteksi halaman: jika sudah login, lempar ke dashboard
Auth::redirectIfLoggedIn();

$error = '';

// Flow Login: Menangani pengiriman form POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Mencoba login menggunakan class Auth
    if (Auth::login($email, $password)) {
        header("Location: admin.php");
        exit();
    } else {
        // Jika gagal, cek alasan kegagalan untuk feedback user
        $userModel = new User();
        if (!$userModel->findByEmail($email)) {
            $error = "Email tidak ditemukan!";
        } else {
            $error = "Password salah!";
        }
    }
}
?>



<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Nuids</title>
    <link rel="stylesheet" href="global.css">
    <link rel="stylesheet" href="login.css">
</head>

<body>
    <div class="container-login">
        <h2 class="greeting">Hi! Selamat Datang Kembali di Nuids.</h2>

        <div class="acrylic-card">
            <div class="logo-container">
                <img src="logo.png" alt="" width="50px">
            </div>

            <?php if (!empty($error)): ?>
                <div style=" <?php if (password_verify($password, $user['password'])) {
                                    echo 'color: green;';
                                } else {
                                    echo 'color: red;';
                                } ?>  margin-bottom: 15px; text-align: center; font-weight: bold;"><?= $error ?></div>
            <?php endif; ?>

            <form action="login.php" method="POST">
                <div class="form-group">
                    <input type="email" name="email" class="custom-input input-teal" placeholder="Email" required>
                </div>
                <div class="form-group">
                    <input type="password" name="password" class="custom-input input-pink" placeholder="Password" required>
                </div>

                <button type="submit" class="btn-login">
                    <span class="btn-text">Login</span>
                </button>
            </form>
            <a href="register.php">Belum punya akun? Daftar sekarang</a>
        </div>
    </div>
    <script src="main.js"></script>
</body>

</html>