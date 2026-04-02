<?php
session_start();
session_destroy();
header("Location: login.php");
?>
//untuk keluar dari akun dan mengalihkan halaman ke login.php 