<?php
session_start();
include 'config.php';

// Cek apakah tombol login udah ditekan
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Ambil data dari database berdasarkan username yang diketik
    $result = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");
    $user = mysqli_fetch_assoc($result);

    // 'password_verify' bakal bandingin password yang dimasukin
    if ($user && password_verify($password, $user['password'])) {
        // Kalau password sama,maka tersimpan dengan SESSION biar server tau dia dah login.
        $_SESSION['login'] = true;
        header("Location: index.php");
    } else {
        $error = true;
    }
}
?>

<head>
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>

<h2>Masuk</h2>

<form method="post">
    <?php if(isset($error)) echo "<p>Login Gagal!</p>"; ?>
    <input type="text" name="username" placeholder="Username" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <button type="submit" name="login">Login</button>
    <a1>Belum punya akun??<a href="register.php">Daftar</a></a1>
</form>

