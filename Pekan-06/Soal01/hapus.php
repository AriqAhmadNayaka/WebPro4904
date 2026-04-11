<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: Login.php");
    exit;
}

require_once __DIR__ . '/classes/DataUser.php';

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $dataObj = new DataUser($_SESSION['user_id']);
    
    if ($dataObj->delete($id)) {
        header("Location: dashboard.php");
        exit;
    } else {
        echo "<script>alert('Gagal menghapus data!');</script>";
        echo "<script>window.location='dashboard.php';</script>";
    }
} else {
    header("Location: dashboard.php");
}
?>