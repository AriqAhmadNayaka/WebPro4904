<?php
session_start();

$email_input = $_POST['email'] ?? '';
$password_input = $_POST['password'] ?? '';

$login_sukses = false;

if (isset($_SESSION['users'])) {
    foreach ($_SESSION['users'] as $user) {
        if ($user['email'] == $email_input && $user['password'] == $password_input) {
            $login_sukses = true;
            $_SESSION['user_login'] = $user['nama']; 
            break;
        }
    }
}

if ($login_sukses) {
    header("Location: dasboard.php");
    exit;
} else {
    header("Location: login.php");
    exit;
}
?>