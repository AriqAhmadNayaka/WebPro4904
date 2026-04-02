<?php
session_start();
// Include ke koneksi database
include 'koneksi.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email_input = mysqli_real_escape_string($conn, $_POST['email']);// Ambil input email dan password dari form
    $password_input = $_POST['password'];
    $query = "SELECT * FROM users WHERE email = '$email_input'";
    $result = mysqli_query($conn, $query);// Query database untuk cari user berdasarkan email
    if (mysqli_num_rows($result) === 1) {// Cek apakah email ada di database 
        $user = mysqli_fetch_assoc($result);// Ambil data user dari hasil query
        if (password_verify($password_input, $user['password'])) { // Verifikasi password dan menyimpan nama user di session 
            $_SESSION['user_login'] = $user['nama']; // memastikan data apakah sama dengan yang ada di database

            // Lanjut ke dashboard kalau login sukses
            header("Location: dasboard.php"); 
            exit; 
        }
    }
    echo "<script>alert('Email atau Password salah!'); window.location='login.php';</script>";
    // Kalau email atau password salah maka tampilan kembali ke halaman login
}
?>