<?php
// Memuat file inisialisasi (autoloader & session)
require_once 'config/init.php';

// Proteksi halaman dashboard
Auth::checkLogin();

/**
 * Flow Penghapusan Data (Delete)
 */
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    /**
     * 1. Validasi Data
     * Mengambil data untuk mendapatkan nama file sertifikat agar bisa dibersihkan.
     */
    $doctorModel = new Doctor();
    $dokter = $doctorModel->find($id);
    
    if ($dokter) {
        $fileSertifikat = $dokter['sertifikat'] ?? '';
        
        /**
         * 2. Bersihkan File Fisik
         * Menghapus file sertifikat dari folder uploads untuk menghemat storage.
         */
        if (!empty($fileSertifikat)) {
            FileHelper::delete($fileSertifikat);
        }
        
        /**
         * 3. Hapus Record Database
         * Setelah file sukses dihapus, hapus data dari tabel.
         */
        if ($doctorModel->delete($id)) {
            header("Location: admin.php?deleted=success");
            exit;
        } else {
            header("Location: admin.php?deleted=error");
            exit;
        }
    } else {
        header("Location: admin.php"); // ID tidak ditemukan
        exit;
    }
} else {
    header("Location: admin.php"); // ID tidak disediakan
    exit;
}
?>
