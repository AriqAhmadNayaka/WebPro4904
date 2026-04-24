<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
require 'koneksi.php';

$uid = $_SESSION['user_id'];
$id  = (int)$_GET['id'];

// Ambil foto dulu sebelum hapus
$res = mysqli_query($conn, "SELECT foto_bukti FROM monitoring WHERE id=$id AND user_id=$uid");
$row = mysqli_fetch_assoc($res);

if ($row) {
    // Hapus file foto dari server jika ada
    if ($row['foto_bukti'] && file_exists("uploads/" . $row['foto_bukti'])) {
        unlink("uploads/" . $row['foto_bukti']);
    }
    mysqli_query($conn, "DELETE FROM monitoring WHERE id=$id AND user_id=$uid");
}

header("Location: monitoring.php");
exit;
?>