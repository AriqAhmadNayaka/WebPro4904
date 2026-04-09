<?php
require 'koneksi.php';  //menghubungkan ke class Database untuk koneksi ke database
require 'auth.php'; //menghubungkan ke class Auth untuk autentikasi pengguna

// Inisialisasi Objek
$database = new Database();
$auth = new Auth($database->conn);

if (isset($_POST['username']) && isset($_POST['password'])) { // Memeriksa apakah data login telah dikirim melalui metode POST
    $user = $_POST['username']; // Menyimpan username yang dikirim dari form login
    $pass = $_POST['password'];// Menyimpan password yang dikirim dari form login

    if (!empty($user) && !empty($pass)) { // Memeriksa apakah username dan password tidak kosong
        if ($auth->login($user, $pass)) {// Memanggil metode login dari objek Auth untuk memeriksa kecocokan username dan password
            header("Location: warga.php");
            exit();
        } else {
            echo "<script>alert('Username atau password salah!');</script>"; // Menampilkan pesan kesalahan jika login gagal
        }
    } else {
        echo "<script>alert('Lengkapi data login!');</script>"; // Menampilkan pesan kesalahan jika username atau password kosong
    }
}
?>

<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Halaman Masuk</title>
    <link rel="stylesheet" href="stylelogin.css" />
  </head>
  <body>
    <div class="container">
      <h2>Masuk</h2>

      <form action="login.php" method="POST">
        <input type="text" id="username" name="username" placeholder="Username" />
        <input type="password" id="password" name="password" placeholder="Password" />
        <button type="submit">Masuk</button>
        <div class="register-link">
          <span>Belum punya akun? </span>
          <a href="register.html">Register</a>
        </div>
      </form>
    </div>
  </body>
</html>
