<?php
session_start();

$_SESSION["akun"] = [ // menerima data yang dikirim dari form registrasi menggunakan method post
    "role"     => trim($_POST["role"]),
    "nama"     => trim($_POST["nama"]),
    "email"    => trim($_POST["email"]),
    "password" => trim($_POST["password"]),
];

header("Location: login.php?from=register"); // redirect ke halaman login menggunakan method get
exit;
?>
