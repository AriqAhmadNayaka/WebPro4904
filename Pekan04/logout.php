<?php
session_start();      // mulai session
session_destroy();    // hapus semua session
header("Location: PR_01login.php"); // arahkan ke halaman login
exit;
?>