<?php
session_start();
require 'koneksi.php';

$error = $success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama  = trim($_POST['nama']);
    $email = trim($_POST['email']);
    $pass  = $_POST['password'];

    // Validasi
    if (empty($nama) || empty($email) || empty($pass)) {
        $error = "Semua field wajib diisi.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Format email tidak valid.";
    } elseif (strlen($pass) < 6) {
        $error = "Password minimal 6 karakter.";
    } else {
        // Cek email duplikat
        $cek = mysqli_query($conn, "SELECT id FROM users WHERE email='".mysqli_real_escape_string($conn,$email)."'");
        if (mysqli_num_rows($cek) > 0) {
            $error = "Email sudah terdaftar.";
        } else {
            $hash = password_hash($pass, PASSWORD_DEFAULT);
            $sql  = "INSERT INTO users (nama, email, password) VALUES ('".mysqli_real_escape_string($conn,$nama)."','".mysqli_real_escape_string($conn,$email)."','$hash')";
            if (mysqli_query($conn, $sql)) {
                $success = "Registrasi berhasil! <a href='login.php'>Login sekarang</a>";
            } else {
                $error = "Gagal registrasi. Coba lagi.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Register - SmartTraffic Cam</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="auth-wrap">
    <div class="auth-box">
        <h2>🚦 Register</h2>
        <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
        <?php if ($success): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <label>Nama</label>
                <input type="text" name="nama" value="<?= htmlspecialchars($_POST['nama'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%">Daftar</button>
        </form>
        <div class="link-text">Sudah punya akun? <a href="login.php">Login</a></div>
    </div>
</div>
</body>
</html>