<?php
// Include file koneksi ke database
include 'koneksi.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil input dari form dan escape supaya aman dari SQL injection
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
     // Hash password supaya aman sebelum disimpan di database
    $sql = "INSERT INTO users (nama, email, password)
     VALUES ('$nama', '$email', '$password')";
    // Query untuk memasukkan data user baru ke tabel 'users'
    if (mysqli_query($conn, $sql)) {// jika sukses lanjut ke halaman login
        header("Location: login.php");
        exit; 
    } else {
        // Kalau gagal maka menampilkan pesan error 
        echo "Error: " . mysqli_error($conn);
    }
}
?>