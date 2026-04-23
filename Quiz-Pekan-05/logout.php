<?php
// Memulai session agar data login yang tersimpan bisa dihapus.
session_start();

// Mengecek apakah logout juga diminta untuk menghapus cookie.
$clearCookie = isset($_GET['clear_cookie']) && $_GET['clear_cookie'] === '1';

// Menghapus seluruh data session lalu menutup session pengguna.
session_unset();
session_destroy();

// Jika parameter clear_cookie aktif, cookie username ikut dihapus.
if ($clearCookie) {
    setcookie('remember_username', '', time() - 3600, '/');
}

// Setelah logout selesai, arahkan kembali ke halaman login.
header('Location: login.php');
exit;
