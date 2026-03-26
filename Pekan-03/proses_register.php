<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (!isset($_SESSION['users'])) {
        $_SESSION['users'] = [];
    }

    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $user_baru = [
        "nama" => $nama,
        "email" => $email,
        "password" => $password
    ];

    $_SESSION['users'][] = $user_baru;

    header("Location: login.php");
    exit;
}
?>