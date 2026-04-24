<?php 
session_start();// Proteksi halaman agar tidak bisa diakses tanpa login
session_destroy();// Hapus semua session
setcookie('user_login', '', time() - 3600, "/");// Hapus cookie login
header("Location: login.php");
exit;
?>
