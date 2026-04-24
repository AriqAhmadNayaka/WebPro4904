<?php
session_start();
require 'koneksi.php';

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $pass  = $_POST['password'];

    if (empty($email) || empty($pass)) {
        $error = "Email dan password wajib diisi.";
    } else {
        $sql  = "SELECT * FROM users WHERE email='".mysqli_real_escape_string($conn,$email)."'";
        $res  = mysqli_query($conn, $sql);
        $user = mysqli_fetch_assoc($res);

        if ($user && password_verify($pass, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['nama']    = $user['nama'];
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Email atau password salah.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - SmartTraffic Cam</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="auth-wrap">
    <div class="auth-box">
        <h2>Login</h2>
        <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%">Masuk</button>
        </form>
        <div class="link-text">Belum punya akun? <a href="register.php">Daftar</a></div>
    </div>
</div>
</body>
</html>