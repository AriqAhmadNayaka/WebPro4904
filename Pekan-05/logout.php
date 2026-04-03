<?php
session_start(); // memulai session
session_destroy(); // menghapus semua data session

setcookie("username", "", time() - 3600); //set expired cookie

header("Location: login2.php"); // redirect ke halaman login
exit; // hentikan eksekusi program
?>