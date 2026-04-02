<?php
// 1. Inisialisasi Error Reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 2. Memuat Session dan Koneksi
require_once "session_init.php";
require_once "koneksi.php";

// Memastikan variabel $conn dari koneksi.php dapat diakses
global $conn;

// 3. Proteksi Halaman: Hanya Admin yang diizinkan menghapus data
if (!isset($_SESSION["login"]) || $_SESSION["role"] !== "admin") {
    // Jika bukan admin atau belum login, tendang ke login.php
    header("Location: login.php");
    exit;
}

// 4. Validasi Parameter ID
// Mengecek apakah ada ID yang dikirim melalui URL (GET)
if (isset($_GET["id"]) && !empty($_GET["id"])) {
    $id = (int)$_GET["id"]; // Casting ke integer untuk keamanan (SQL Injection Protection)

    // 5. Eksekusi Query Hapus
    // Kita hapus data berdasarkan ID yang dipilih
    $queryHapus = "DELETE FROM datauser WHERE id = $id";

    if (mysqli_query($conn, $queryHapus)) {
        // Jika berhasil, kembali ke dashboard dengan pesan sukses (opsional bisa ditambah session)
        header("Location: dashboard.php");
        exit;
    } else {
        // Jika gagal karena masalah database
        echo "
        <script>
            alert('Gagal menghapus data: " . mysqli_error($conn) . "');
            window.location.href = 'dashboard.php';
        </script>";
    }
} else {
    // Jika tidak ada ID yang dipilih, langsung balik ke dashboard
    header("Location: dashboard.php");
    exit;
}
?>