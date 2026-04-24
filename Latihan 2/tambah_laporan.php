<?php
session_start();

// Cek session
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require 'koneksi.php';

$error = '';
$user_id = $_SESSION['user_id'];

// Proses simpan data menggunakan POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lokasi          = trim($_POST['lokasi'] ?? '');
    $waktu_laporan   = $_POST['waktu_laporan'] ?? '';
    $jumlah_kendaraan= $_POST['jumlah_kendaraan'] ?? '';
    $deskripsi       = trim($_POST['deskripsi'] ?? '');

    // Validasi: field tidak boleh kosong
    if (empty($lokasi) || empty($waktu_laporan) || $jumlah_kendaraan === '') {
        $error = 'Lokasi, waktu, dan jumlah kendaraan wajib diisi.';
    // Validasi: jumlah kendaraan harus berupa angka
    } elseif (!is_numeric($jumlah_kendaraan) || (int)$jumlah_kendaraan < 0) {
        $error = 'Jumlah kendaraan harus berupa angka positif.';
    } else {
        $jml = (int)$jumlah_kendaraan;

        // Logika penentuan status pelanggaran berdasarkan jumlah kendaraan
        if ($jml >= 1 && $jml <= 5) {
            $status = 'Ringan';
        } elseif ($jml >= 6 && $jml <= 15) {
            $status = 'Sedang';
        } else {
            $status = 'Berat';
        }

        // Handle upload file foto bukti
        $foto_bukti = null;
        if (isset($_FILES['foto_bukti']) && $_FILES['foto_bukti']['error'] === UPLOAD_ERR_OK) {
            $ext          = strtolower(pathinfo($_FILES['foto_bukti']['name'], PATHINFO_EXTENSION));
            $allowedTypes = ['jpg', 'jpeg', 'png'];

            // Validasi: file harus sesuai format yang diizinkan
            $check = getimagesize($_FILES['foto_bukti']['tmp_name']);
            if ($check === false || !in_array($ext, $allowedTypes)) {
                $error = 'Format file tidak valid. Gunakan JPG, JPEG, atau PNG.';
            } else {
                // Simpan file dengan nama unik agar tidak tumpang tindih
                $namaFile = time() . '_' . uniqid() . '.' . $ext;
                move_uploaded_file($_FILES['foto_bukti']['tmp_name'], 'uploads/' . $namaFile);
                $foto_bukti = $namaFile;
            }
        }

        if (empty($error)) {
            // INSERT data ke database menggunakan POST
            $sql  = "INSERT INTO laporan (user_id, lokasi, waktu_laporan, jumlah_kendaraan, status_pelanggaran, deskripsi, foto_bukti)
                     VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("issiiss", $user_id, $lokasi, $waktu_laporan, $jml, $status, $deskripsi, $foto_bukti);
            $stmt->execute();
            $stmt->close();

            // PRG Pattern: redirect setelah POST berhasil
            header("Location: laporan.php?success=tambah");
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Laporan - SmartParking</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<?php require 'navbar.php'; ?>

<div class="container">
    <div class="page-header">
        <h2>Tambah Laporan Parkir Liar</h2>
        <a href="#" onclick="history.back(); return false;" class="btn btn-secondary">Kembali</a>
    </div>

    <div class="card">
        <?php if ($error): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <!-- Form menggunakan method POST dan enctype multipart untuk upload file -->
        <form method="POST" action="tambah_laporan.php" enctype="multipart/form-data">

            <div class="form-row">
                <div class="form-group">
                    <label>Lokasi Kejadian</label>
                    <input type="text" name="lokasi" value="<?= htmlspecialchars($_POST['lokasi'] ?? '') ?>" placeholder="Contoh: Pasar Baleendah" required>
                </div>
                <div class="form-group">
                    <label>Waktu Laporan</label>
                    <input type="datetime-local" name="waktu_laporan" value="<?= $_POST['waktu_laporan'] ?? '' ?>" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Jumlah Kendaraan</label>
                    <input type="number" name="jumlah_kendaraan" value="<?= htmlspecialchars($_POST['jumlah_kendaraan'] ?? '') ?>" min="0" placeholder="Masukkan angka" required>
                    <small style="color:#888;font-size:12px">1-5: Ringan | 6-15: Sedang | &gt;15: Berat</small>
                </div>
                <div class="form-group">
                    <label>Foto Bukti <small>(JPG/JPEG/PNG, opsional)</small></label>
                    <input type="file" name="foto_bukti" accept=".jpg,.jpeg,.png">
                </div>
            </div>

            <div class="form-group">
                <label>Deskripsi Kondisi</label>
                <textarea name="deskripsi" placeholder="Jelaskan kondisi lapangan..."><?= htmlspecialchars($_POST['deskripsi'] ?? '') ?></textarea>
            </div>

            <div style="display:flex;gap:12px">
                <button type="submit" class="btn btn-primary">Simpan Laporan</button>
                <a href="laporan.php" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

<?php require 'footer.php'; ?>
</body>
</html>