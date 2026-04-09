<?php
include("Koneksi.php");
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$id = isset($_GET["id"]) ? (int) $_GET["id"] : 0;
$controller = new PasienController();
$isSuccess = $controller->delete($id);

header(
    "Location: dashboard.php?message=" .
    urlencode($isSuccess ? "Data pasien berhasil dihapus." : "Data pasien gagal dihapus.") .
    "&type=" . urlencode($isSuccess ? "success" : "error")
);
exit;
?>