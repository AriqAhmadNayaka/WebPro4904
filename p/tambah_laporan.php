<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/koneksi.php';

require_login();

function hitung_status_pelanggaran(int $jumlahKendaraan): string
{
    if ($jumlahKendaraan >= 1 && $jumlahKendaraan <= 5) {
        return 'Ringan';
    }

    if ($jumlahKendaraan >= 6 && $jumlahKendaraan <= 14) {
        return 'Sedang';
    }

    return 'Berat';
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lokasi = trim($_POST['lokasi'] ?? '');
    $waktuLaporan = trim($_POST['waktu_laporan'] ?? '');
    $jumlahKendaraanInput = trim($_POST['jumlah_kendaraan'] ?? '');
    $deskripsi = trim($_POST['deskripsi'] ?? '');

    if ($lokasi === '' || $waktuLaporan === '' || $jumlahKendaraanInput === '' || $deskripsi === '') {
        $error = 'Semua field wajib diisi.';
    } elseif (!ctype_digit($jumlahKendaraanInput) || (int) $jumlahKendaraanInput < 1) {
        $error = 'Jumlah kendaraan harus berupa angka dan minimal 1.';
    } elseif (!isset($_FILES['foto_bukti']) || $_FILES['foto_bukti']['error'] === UPLOAD_ERR_NO_FILE) {
        $error = 'File bukti wajib diupload.';
    } else {
        $waktuLaporan = str_replace('T', ' ', $waktuLaporan);
        $allowedExtensions = ['jpg', 'jpeg', 'png'];
        $fileName = $_FILES['foto_bukti']['name'];
        $fileTmp = $_FILES['foto_bukti']['tmp_name'];
        $fileError = $_FILES['foto_bukti']['error'];
        $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if ($fileError !== UPLOAD_ERR_OK) {
            $error = 'Upload file gagal diproses.';
        } elseif (!in_array($extension, $allowedExtensions, true)) {
            $error = 'Format file harus jpg, jpeg, atau png.';
        } else {
            $jumlahKendaraan = (int) $jumlahKendaraanInput;
            $statusPelanggaran = hitung_status_pelanggaran($jumlahKendaraan);
            $safeFileName = uniqid('bukti_', true) . '.' . $extension;
            $relativePath = 'uploads/' . $safeFileName;
            $uploadPath = __DIR__ . '/uploads/' . $safeFileName;

            if (!move_uploaded_file($fileTmp, $uploadPath)) {
                $error = 'File gagal disimpan ke folder uploads.';
            } else {
                $stmt = mysqli_prepare($koneksi, 'INSERT INTO laporan (user_id, lokasi, waktu_laporan, jumlah_kendaraan, status_pelanggaran, deskripsi, foto_bukti) VALUES (?, ?, ?, ?, ?, ?, ?)');
                $userId = current_user_id();
                mysqli_stmt_bind_param($stmt, 'ississs', $userId, $lokasi, $waktuLaporan, $jumlahKendaraan, $statusPelanggaran, $deskripsi, $relativePath);

                if (mysqli_stmt_execute($stmt)) {
                    mysqli_stmt_close($stmt);
                    set_flash('success', 'Laporan berhasil ditambahkan.');
                    header('Location: laporan.php');
                    exit;
                }

                mysqli_stmt_close($stmt);
                if (file_exists($uploadPath)) {
                    unlink($uploadPath);
                }
                $error = 'Data laporan gagal disimpan.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Laporan | SmartParking Report</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <?php include __DIR__ . '/navbar.php'; ?>
    <main class="container page-layout">
        <section class="page-intro">
            <p class="eyebrow">Input Laporan</p>
            <h2>Tambah data parkir liar baru</h2>
            <p>Status pelanggaran dihitung otomatis dengan logika if/else berdasarkan jumlah kendaraan.</p>
        </section>

        <section class="form-card wide-card">
            <h3>Form Tambah Laporan</h3>

            <?php if ($error !== ''): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data" class="form-grid">
                <label>
                    <span>Lokasi</span>
                    <input type="text" name="lokasi" required value="<?= htmlspecialchars($_POST['lokasi'] ?? ''); ?>" placeholder="Contoh: Pasar Baleendah">
                </label>

                <label>
                    <span>Waktu Laporan</span>
                    <input type="datetime-local" name="waktu_laporan" required value="<?= htmlspecialchars($_POST['waktu_laporan'] ?? ''); ?>">
                </label>

                <label>
                    <span>Jumlah Kendaraan</span>
                    <input type="number" name="jumlah_kendaraan" min="1" required value="<?= htmlspecialchars($_POST['jumlah_kendaraan'] ?? ''); ?>">
                </label>

                <label>
                    <span>Foto Bukti</span>
                    <input type="file" name="foto_bukti" accept=".jpg,.jpeg,.png" required>
                </label>

                <label class="full-width">
                    <span>Deskripsi</span>
                    <textarea name="deskripsi" rows="5" required placeholder="Jelaskan kondisi lapangan secara singkat."><?= htmlspecialchars($_POST['deskripsi'] ?? ''); ?></textarea>
                </label>

                <button type="submit" class="btn btn-primary">Simpan Laporan</button>
            </form>
        </section>
    </main>
    <?php include __DIR__ . '/footer.php'; ?>
</body>
</html>
