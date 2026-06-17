<?php
session_start();
include '../config/Database.php';
include '../models/User.php';

$db = (new Database())->connect();
$user = new User($db);

$user->username = $_POST['username'];
$user->password = $_POST['password'];

if (isset($_POST['login'])) {
    if ($user->login()) {
        $_SESSION['login'] = true;
        header("Location: ../views/dashboard.php");
    } else {
        echo "Login gagal!";
    }
}

if (isset($_POST['register'])) {
    $user->register();
    header("Location: ../views/login.php");
}
?>