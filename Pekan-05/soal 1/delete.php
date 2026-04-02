<?php
include 'db.php';
include 'functions.php';

/**
 * Logika Penghapusan Data (Delete)
 */
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    /**
     * 1. Cari Data Terlebih Dahulu
     * Dibutuhkan untuk mendapatkan nama file sertifikat agar bisa dihapus dari server.
     */
    $dokter = getData($conn, "dokter", ["id" => $id]);
    
    if (count($dokter) > 0) {
        $fileSertifikat = $dokter[0]['sertifikat'];
        
        /**
         * 2. Hapus File Fisik
         * Menggunakan helper deletePhysicalFile untuk membersihkan folder uploads.
         */
        if (!empty($fileSertifikat)) {
            deletePhysicalFile($fileSertifikat, 'uploads/');
        }
        
        /**
         * 3. Hapus Data dari Database
         * Setelah file sukses dihapus (atau jika memang tidak ada file), hapus record di DB.
         */
        if (deleteData($conn, "dokter", $id)) {
            header("Location: admin.php?deleted=success");
            exit;
        } else {
            header("Location: admin.php?deleted=error");
            exit;
        }
    } else {
        header("Location: admin.php"); // Jika ID tidak ditemukan
        exit;
    }
} else {
    header("Location: admin.php"); // Jika tidak ada ID di URL
    exit;
}
?>
