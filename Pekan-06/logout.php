<?php 
session_start();// Proteksi halaman agar tidak bisa diakses tanpa login
session_destroy();// Hapus semua session
setcookie('id', '', time() - 3600, "/");// Hapus cookie id
setcookie('key', '', time() - 3600, "/");// Hapus cookie key
header("Location: login.php");
exit;
?>