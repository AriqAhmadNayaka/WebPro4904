<?php
// Memanggil session untuk mengakses data session yang sedang aktif
session_start();
// Memanggil file User.php yang berisi class User untuk mengelola proses login dan logout
require_once 'User.php';

// Memanggil method logout() dari class User. 
// Method ini akan menghapus semua data session yang terkait dengan pengguna yang sedang login, sehingga pengguna dianggap sudah logout.
$userObj = new User();
$userObj->logout();

// Setelah logout, pengguna akan diarahkan kembali ke halaman login
header("Location: 1login.php");
exit();
?>