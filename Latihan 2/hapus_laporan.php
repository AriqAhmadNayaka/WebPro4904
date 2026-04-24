<?php
session_start();

// Cek session
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require 'koneksi.php';

$user_id = $_SESSION['user_id'];

// Ambil id dari GET untuk menentukan data yang dihapus
$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    header("Location: laporan.php");
    exit;
}

// Ambil data laporan untuk mendapatkan nama foto sebelum dihapus
$stmt = $conn->prepare("SELECT foto_bukti FROM laporan WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $id, $user_id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
$stmt->close();

if ($row) {
    // Hapus file foto dari folder uploads jika ada
    if ($row['foto_bukti'] && file_exists('uploads/' . $row['foto_bukti'])) {
        unlink('uploads/' . $row['foto_bukti']);
    }

    // DELETE data dari database
    $stmt = $conn->prepare("DELETE FROM laporan WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $id, $user_id);
    $stmt->execute();
    $stmt->close();
}

header("Location: laporan.php?success=hapus");
exit;