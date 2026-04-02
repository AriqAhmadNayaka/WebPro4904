<?php
include 'koneksi.php';
include 'profil_helper.php';

ensureProfileSchema($conn);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']); 
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (username, nama_lengkap, email, password) VALUES ('$nama', '$nama', '$email', '$password')";
    
    if (mysqli_query($conn, $sql)) {
        header("Location: login.php?status=register-success");
        exit;
    } else {
        header("Location: registrasi.php?status=register-failed");
        exit;
    }
}
?>
