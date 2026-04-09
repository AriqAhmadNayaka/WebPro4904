<?php
require "Database.php"; // memanggil class database
require "Auth.php"; // memanggil class auth login logout

$db = new Database(); // membuat objek database
$auth = new Auth($db->conn); // kirim koneksi ke class Auth

$auth->logout(); // jalankan fungsi logout user
?>