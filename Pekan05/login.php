<?php
// Mulai session agar bisa menyimpan status login user
session_start();                 

// Panggil file koneksi ke database (koneksi.php)
include "koneksi.php";           

// Mengecek apakah form login dikirim dengan method POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil input username dari form
    $u = $_POST['username'];     
    // Ambil input password dari form
    $p = $_POST['password'];     

    // Query ke tabel users untuk cek apakah username & password cocok
    $q = mysqli_query($conn, "SELECT * FROM users WHERE username='$u' AND password='$p'");
    
    // Jika data ditemukan (login berhasil)
    if (mysqli_num_rows($q) > 0) {
        // Simpan status login ke session
        $_SESSION['login'] = true;       
        // Simpan username ke session (opsional, bisa dipakai di halaman lain)
        $_SESSION['username'] = $u;      
        // Arahkan user ke halaman utama (home.php)
        header("Location: home.php");    
    } else {
        // Jika tidak cocok, tampilkan pesan alert
        echo "<script>alert('Login gagal! Username atau password salah');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"> <!-- Set karakter encoding -->
  <title>Halaman Masuk</title> <!-- Judul tab browser -->
  <link rel="stylesheet" href="style.css"> <!-- Panggil file CSS untuk styling -->
</head>
<body>
<div class="container"> <!-- Container utama untuk tampilan -->
  <h2>Login Aplikasi Desa</h2>
  
  <!-- Form login -->
  <form method="post">
    <!-- Input username -->
    <input type="text" name="username" placeholder="Username" required>
    
    <!-- Input password -->
    <input type="password" name="password" placeholder="Password" required>
    
    <!-- Tombol login -->
    <button type="submit">Login</button>
  </form>
  
  <!-- Link menuju halaman daftar (register.php) -->
  <div class="register-link">
    <p>Belum punya akun? <a href="register.php">Daftar</a></p>
  </div>
</div>
</body>
</html>