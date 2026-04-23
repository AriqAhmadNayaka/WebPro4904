<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/koneksi.php';

require_login();

function hitung_status_pelanggaran_edit(int $jumlahKendaraan): string
{
    if ($jumlahKendaraan >= 1 && $jumlahKendaraan <= 500) {
        return 'Aman';
    }

    if ($jumlahKendaraan >= 500 && $jumlahKendaraan <= 1000) {
        return 'Waspada';
    }

    return 'Overload';
}

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$error = '';

if ($id <= 0) {
    set_flash('error', 'ID pemantauan tidak valid.');
    header('Location: pemantauan.php');
    exit;
}

$userId = current_user_id();
$selectStmt = mysqli_prepare($koneksi, 'SELECT id, lokasi_tps, waktu_pemantauan, berat_sampah, deskripsi, foto_bukti FROM monitor WHERE id = ? AND user_id = ? LIMIT 1');
mysqli_stmt_bind_param($selectStmt, 'ii', $id, $userId);
mysqli_stmt_execute($selectStmt);
$selectResult = mysqli_stmt_get_result($selectStmt);
$laporan = $selectResult ? mysqli_fetch_assoc($selectResult) : null;
mysqli_stmt_close($selectStmt);

if (!$laporan) {
    set_flash('error', 'Data pemantauan tidak ditemukan.');
    header('Location: pemantauan.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lokasi = trim($_POST['lokasi_tps'] ?? '');
    $waktuLaporan = trim($_POST['waktu_pemantauan'] ?? '');
    $jumlahKendaraanInput = trim($_POST['berat_sampah'] ?? '');
    $deskripsi = trim($_POST['deskripsi'] ?? '');
    $relativePath = $laporan['foto_bukti'];
    $oldFilePath = __DIR__ . '/' . $laporan['foto_bukti'];

    if ($lokasi === '' || $waktuLaporan === '' || $jumlahKendaraanInput === '' || $deskripsi === '') {
        $error = 'Semua field wajib diisi.';
    } elseif (!ctype_digit($jumlahKendaraanInput) || (int) $jumlahKendaraanInput < 1) {
        $error = 'Jumlah kendaraan harus berupa angka dan minimal 1.';
    } else {
        $waktuLaporan = str_replace('T', ' ', $waktuLaporan);
        $allowedExtensions = ['jpg', 'jpeg', 'png'];
        $newUploadProvided = isset($_FILES['foto_bukti']) && $_FILES['foto_bukti']['error'] !== UPLOAD_ERR_NO_FILE;
        $newUploadAbsolutePath = '';

        if ($newUploadProvided) {
            $fileName = $_FILES['foto_bukti']['name'];
            $fileTmp = $_FILES['foto_bukti']['tmp_name'];
            $fileError = $_FILES['foto_bukti']['error'];
            $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            if ($fileError !== UPLOAD_ERR_OK) {
                $error = 'Upload file baru gagal diproses.';
            } elseif (!in_array($extension, $allowedExtensions, true)) {
                $error = 'Format file harus jpg, jpeg, atau png.';
            } else {
                $newFileName = uniqid('bukti_', true) . '.' . $extension;
                $relativePath = 'uploads/' . $newFileName;
                $newUploadAbsolutePath = __DIR__ . '/uploads/' . $newFileName;

                if (!move_uploaded_file($fileTmp, $newUploadAbsolutePath)) {
                    $error = 'File baru gagal disimpan.';
                }
            }
        }

        if ($error === '') {
            $jumlahKendaraan = (int) $jumlahKendaraanInput;
            $statusPelanggaran = hitung_status_pelanggaran_edit($jumlahKendaraan);
            $updateStmt = mysqli_prepare($koneksi, 'UPDATE monitor SET lokasi_tps = ?, waktu_pemantauan = ?, berat_sampah = ?, status_kapasitas = ?, deskripsi = ?, foto_bukti = ? WHERE id = ? AND user_id = ?');
            mysqli_stmt_bind_param($updateStmt, 'ssisssii', $lokasi, $waktuLaporan, $jumlahKendaraan, $statusPelanggaran, $deskripsi, $relativePath, $id, $userId);

            if (mysqli_stmt_execute($updateStmt)) {
                mysqli_stmt_close($updateStmt);

                if ($newUploadProvided && $laporan['foto_bukti'] !== '' && file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }

                set_flash('success', 'Pemantauan berhasil diperbarui.');
                header('Location: pemantauan.php');
                exit;
            }

            mysqli_stmt_close($updateStmt);

            if ($newUploadAbsolutePath !== '' && file_exists($newUploadAbsolutePath)) {
                unlink($newUploadAbsolutePath);
            }

            $error = 'Perubahan pemantauan gagal disimpan.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pemantauan | SmartTrash Monitoring</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <?php include __DIR__ . '/navbar.php'; ?>
    <main class="container page-layout">
        <section class="page-intro">
            <p class="eyebrow">Edit Pemantauan</p>
            <h2>Perbarui pemantauan sampah TPS</h2>
            <p>Jika tidak upload file baru, maka file lama tetap digunakan sesuai ketentuan tugas.</p>
        </section>

        <section class="form-card wide-card">
            <h3>Form Edit Pemantauan</h3>

            <?php if ($error !== ''): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data" class="form-grid">
                <label>
                    <span>Lokasi</span>
                    <input type="text" name="lokasi_tps" required value="<?= htmlspecialchars($_POST['lokasi_tps'] ?? $laporan['lokasi_tps']); ?>">
                </label>

                <label>
                    <span>Waktu Pemantauan</span>
                    <input type="datetime-local" name="waktu_pemantauan" required value="<?= htmlspecialchars($_POST['waktu_pemantauan'] ?? date('Y-m-d\TH:i', strtotime($laporan['waktu_pemantauan']))); ?>">
                </label>

                <label>
                    <span>Berat Sampah</span>
                    <input type="number" name="berat_sampah" min="1" required value="<?= htmlspecialchars($_POST['berat_sampah'] ?? $laporan['berat_sampah']); ?>">
                </label>

                <label>
                    <span>Foto Bukti Baru</span>
                    <input type="file" name="foto_bukti" accept=".jpg,.jpeg,.png">
                </label>

                <label class="full-width">
                    <span>Deskripsi</span>
                    <textarea name="deskripsi" rows="5" required><?= htmlspecialchars($_POST['deskripsi'] ?? $laporan['deskripsi']); ?></textarea>
                </label>

                <div class="file-preview full-width">
                    <span>File Saat Ini:</span>
                    <?php if ($laporan['foto_bukti'] !== '' && file_exists(__DIR__ . '/' . $laporan['foto_bukti'])): ?>
                        <a href="<?= htmlspecialchars($laporan['foto_bukti']); ?>" target="_blank">Lihat File Lama</a>
                    <?php else: ?>
                        <span class="muted-text">Tidak ada file lama</span>
                    <?php endif; ?>
                </div>

                <button type="submit" class="btn btn-primary">Update Pemantauan</button>
            </form>
        </section>
    </main>
    <?php include __DIR__ . '/footer.php'; ?>
</body>
</html>
