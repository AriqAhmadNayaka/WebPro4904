<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION['users'])) {
        $_SESSION['users'] = [];
    }

    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $_SESSION['users'][] = [
        'nama' => $nama,
        'email' => $email,
        'password' => $password
    ];

    header("Location: login.php");
    exit;
}
?>