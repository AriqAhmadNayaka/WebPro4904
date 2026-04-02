<?php
include "koneksi.php";

//untuk mengecek apakah tombol register sudah ditekan atau belum, kalo berhasil maka akan menjalankan query insert ke database dengan data yang diambil dari form, lalu menampilkan alert dan mengalihkan halaman ke login.php
if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = md5($_POST['password']);

    //untuk menjalankan query insert ke database dengan data yang diambil dari form tadi
    mysqli_query($conn, "INSERT INTO user (username, email, password) 
    VALUES ('$username', '$email', '$password')");

    //untuk menampilkan alert dan mengalihkan halaman ke login.php setelah data berhasil disimpan ke database
    echo "<script>
    alert('Register berhasil!');
    window.location='login.php';
    </script>";
}
?>
<!--untuk menampilkan form register dengan desain yang sudah dibuat di auth.css-->
<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
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
            <h2 class="auth-title">Register</h2>
            <p class="auth-subtitle">Buat akun baru InkluSkill</p>
        </div>

        <form class="auth-form" method="POST">
            <div class="form-group">
                <label>Username</label>
                <div class="input-wrapper">
                    <input type="text" name="username" placeholder="Masukkan username" required>
                </div>
            </div>

            <div class="form-group">
                <label>Email</label>
                <div class="input-wrapper">
                    <input type="email" name="email" placeholder="Masukkan email" required>
                </div>
            </div>

            <div class="form-group">
                <label>Password</label>
                <div class="input-wrapper">
                    <input type="password" name="password" placeholder="Masukkan password" required>
                </div>
            </div>

            <button type="submit" name="register" class="auth-btn">Daftar</button>
        </form>

        <div class="auth-switch">
            <p>Sudah punya akun? <a href="login.php">Login di sini</a></p>
        </div>
    </div>
</div>

</body>
</html>