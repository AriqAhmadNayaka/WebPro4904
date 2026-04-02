<?php
include 'config.php';

$username = $_POST['username'];
$password = $_POST['password'];

mysqli_query($conn, "INSERT INTO user (username, password) VALUES ('$username', '$password')");

echo "Registrasi berhasil! <a href='login.php'>Login sekarang</a>";
?>