<?php
session_start();
require_once 'database.php';

if (!isset($_SESSION['isLoggedIn'])) { header("Location: login.php"); exit; }

$donasi = new Donasi();
$user_id = $_SESSION['user_id'];

if (isset($_POST['tambah'])) {
    $donasi->tambahData($user_id, $_POST, $_FILES);
    header("Location: dashboard.php");
    exit;
}
