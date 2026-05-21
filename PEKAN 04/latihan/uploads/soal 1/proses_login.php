<?php
session_start();
include 'koneksi.php'; // Pastikan file koneksi.php sudah benar di folder yang sama

if (isset($_POST['email']) && isset($_POST['password'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Mencari user berdasarkan email
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // Verifikasi password (asumsi di database pakai plain text dulu agar mudah tesnya)
        // Jika nanti pakai password_hash, gunakan password_verify($password, $row['password'])
        if ($password == $row['password']) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['nama'] = $row['nama'];
            
            echo "<script>alert('Berhasil Masuk! Selamat datang " . $row['nama'] . "'); window.location.href='home page.php'; </script>";
        } else {
            echo "<script>alert('Password salah!'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Email tidak terdaftar!'); window.history.back();</script>";
    }
}
?>