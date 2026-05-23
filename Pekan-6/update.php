<?php
include("Koneksi.php");
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: dashboard.php?message=" . urlencode("Metode request tidak valid.") . "&type=error");
    exit;
}

$id = isset($_POST["id"]) ? (int) $_POST["id"] : 0;
$pasien = new Pasien(
    trim($_POST["name"] ?? ""),
    trim($_POST["addres"] ?? ""),
    trim($_POST["number"] ?? ""),
    trim($_POST["gender"] ?? ""),
    trim($_POST["date"] ?? ""),
    trim($_POST["weight"] ?? ""),
    trim($_POST["height"] ?? ""),
    trim($_POST["text"] ?? "")
);

$controller = new PasienController();
$isSuccess = $controller->update($id, $pasien);

header(
    "Location: dashboard.php?message=" .
    urlencode($isSuccess ? "Data pasien berhasil diperbarui." : "Data pasien gagal diperbarui.") .
    "&type=" . urlencode($isSuccess ? "success" : "error")
);
exit;
?>
