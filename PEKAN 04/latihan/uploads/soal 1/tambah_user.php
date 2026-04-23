<?php
include 'koneksi.php';

// Data yang mau dimasukkan (contoh manual)
$nama     = "Budi Doremi";
$email    = "budi@example.com";
$password = password_hash("password123", PASSWORD_DEFAULT); // Biar aman di-hash

// Query Insert
$sql = "INSERT INTO users (nama, email, password) VALUES ('$nama', '$email', '$password')";

if ($conn->query($sql) === TRUE) {
    echo "Data berhasil masuk! Cek lagi di phpMyAdmin kamu.";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>