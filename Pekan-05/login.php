<?php
session_start();
include "koneksi.php"; 

//jadi jika session username sudah ada maka akan diarahkan ke halaman index
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = md5($_POST['password']);

    //untuk menjalankan query select ke database dengan username dan password yang diambil dari form
    $cek = mysqli_query($conn, "SELECT * FROM user WHERE username='$username' AND password='$password'");

    if (mysqli_num_rows($cek) > 0) {
        $_SESSION['username'] = $username;
        header("Location: index.php");
        exit;
    } else {
        echo "<script>alert('Login gagal!');</script>";
    }
}
?>

<!--untuk menampilkan form login dengan desain yang sudah dibuat di auth.css-->
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="auth.css">
</head>
<body>

<div class="auth-bg">
    <div class="gradient-blob blob-1"></div>
    <div class="gradient-blob blob-2"></div>
    <div class="gradient-blob blob-3"></div>
</div>

<div class="auth-wrapper">
    <div class="auth-container active">
        <div class="auth-header">
            <div class="logo-container">
                <div class="logo-icon">IK</div>
            </div>
            <h2 class="auth-title">Login</h2>
            <p class="auth-subtitle">Masuk ke akun InkluSkill</p>
        </div>

        <form class="auth-form" method="POST">
            <div class="form-group">
                <label>Username</label>
                <div class="input-wrapper">
                    <input type="text" name="username" placeholder="Masukkan username" required>
                </div>
            </div>

            <div class="form-group">
                <label>Password</label>
                <div class="input-wrapper">
                    <input type="password" name="password" placeholder="Masukkan password" required>
                </div>
            </div>

            <button type="submit" name="login" class="auth-btn">Login</button>
        </form>

        <div class="auth-switch">
            <p>Belum punya akun? <a href="register.php">Daftar disini</a></p>
        </div>
    </div>
</div>

</body>
</html>