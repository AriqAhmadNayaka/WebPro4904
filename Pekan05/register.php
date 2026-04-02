<?php
include "koneksi.php"; // Panggil koneksi database

// Jika form daftar dikirim
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $u = $_POST['username']; // Ambil input username
    $p = $_POST['password']; // Ambil input password

    // Simpan data ke tabel users (tanpa role)
    $sql = "INSERT INTO users (username, password) VALUES ('$u','$p')";
    if (mysqli_query($conn, $sql)) {
        // Jika berhasil, tampilkan pesan lalu arahkan ke login
        echo "<script>alert('Registrasi berhasil! Silakan login.'); window.location='login.php';</script>";
    } else {
        // Jika gagal, tampilkan pesan error
        echo "<script>alert('Registrasi gagal!');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Halaman Daftar</title>
  <link rel="stylesheet" href="style.css"> <!-- Panggil CSS -->
</head>
<body>
<div class="container">
  <h2>Daftar Akun Baru</h2>
  
  <!-- Form daftar akun baru -->
  <form method="post">
    <!-- Input username -->
    <input type="text" name="username" placeholder="Username" required>
    
    <!-- Input password -->
    <input type="password" name="password" placeholder="Password" required>
    
    <!-- Tombol daftar -->
    <button type="submit">Daftar</button>
  </form>
</div>
</body>
</html>