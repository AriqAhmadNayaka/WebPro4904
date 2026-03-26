<?php 
session_start(); // memulai session
session_destroy(); // menghancurkan semua data session yang tersimpan
header("Location: PR_02login.php");// mengarahkan kembali ke halaman login setelah logout
exit; // pastikan script berhenti setelah logout
?>