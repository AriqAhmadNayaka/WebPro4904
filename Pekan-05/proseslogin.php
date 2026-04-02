<?php
session_start();

$akun     = $_SESSION["akun"] ?? [];
$email    = trim($_POST["email"]);
$password = trim($_POST["password"]);

// cek cocok dengan akun yang diregistrasi
if ($akun["email"] == $email && $akun["password"] == $password) {
    $_SESSION["user"] = $akun["nama"];
    $_SESSION["role"] = $akun["role"];
    header("Location: beranda.php");
} else {
    header("Location: login.php?error=Email+atau+password+salah!");
}
exit;
?>
