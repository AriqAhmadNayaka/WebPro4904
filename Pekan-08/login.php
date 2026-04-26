<?php
// Session dipakai buat nandain user sudah login atau belum.
session_start();                 

// Ambil koneksi database dulu.
include "koneksi.php";           

// Cek apakah form login barusan disubmit.
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil isi form dari user.
    $u = $_POST['username'];     
    $p = $_POST['password'];     

    // Cocokin username dan password ke tabel users.
    $q = mysqli_query($conn, "SELECT * FROM users WHERE username='$u' AND password='$p'");
    
    // Kalau ketemu, anggap login berhasil.
    if (mysqli_num_rows($q) > 0) {
        $_SESSION['login'] = true;       
        $_SESSION['username'] = $u;      
        header("Location: home.php");    
    } else {
        // Kalau nggak cocok, kasih alert sederhana.
        echo "<script>alert('Login gagal! Username atau password salah');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Halaman Masuk</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
  <h2>Login Aplikasi Desa</h2>
  
  <!-- Form login sederhana -->
  <form method="post">
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Login</button>
  </form>
  
  <!-- Link daftar ini buat pindah ke halaman registrasi kalau ada -->
  <div class="register-link">
    <p>Belum punya akun? <a href="register.php">Daftar</a></p>
  </div>
</div>
</body>
</html>
