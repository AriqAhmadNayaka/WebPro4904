<?php
session_start();

$clearCookie = isset($_GET['clear_cookie']) && $_GET['clear_cookie'] === '1';

session_unset();
session_destroy();

if ($clearCookie) {
    setcookie('remember_username', '', time() - 3600, '/');
}

header('Location: login.php');
exit;
