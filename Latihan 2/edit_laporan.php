<?php
session_start();

// Cek session
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require 'koneksi.php';

$user_id = $_SESSION['user_id'];
$error   = '';

// Ambil id dari GET untuk memilih data yang akan diedit
$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    header("Location: laporan.php");
    exit;
}

// Ambil data laporan berdasarkan id DAN user_id (keamanan: user hanya bisa edit miliknya)
$stmt = $conn->prepare("SELECT * FROM laporan WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $id, $user_id);
$stmt->execute();
$laporan = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$laporan) {
    header("Location: laporan.php");
    exit;
}

// Proses UPDATE menggunakan POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lokasi           = trim($_POST['lokasi'] ?? '');
    $waktu_laporan    = $_POST['waktu_laporan'] ?? '';
    $jumlah_kendaraan = $_POST['jumlah_kendaraan'] ?? '';
    $deskripsi        = trim($_POST['deskripsi'] ?? '');

    // Validasi: field tidak boleh kosong
    if (empty($lokasi) || empty($waktu_laporan) || $jumlah_kendaraan === '') {
        $error = 'Lokasi, waktu, dan jumlah kendaraan wajib diisi.';
    // Validasi: jumlah kendaraan harus berupa angka
    } elseif (!is_numeric($jumlah_kendaraan) || (int)$jumlah_kendaraan < 0) {
        $error = 'Jumlah kendaraan harus berupa angka positif.';
    } else {
        $jml = (int)$jumlah_kendaraan;

        // Logika penentuan status pelanggaran
        if ($jml >= 1 && $jml <= 5) {
            $status = 'Ringan';
        } elseif ($jml >= 6 && $jml <= 15) {
            $status = 'Sedang';
        } else {
            $status = 'Berat';
        }

        // Handle upload foto baru
        $foto_bukti = $laporan['foto_bukti']; // default: gunakan foto lama
        if (isset($_FILES['foto_bukti']) && $_FILES['foto_bukti']['error'] === UPLOAD_ERR_OK) {
            $ext          = strtolower(pathinfo($_FILES['foto_bukti']['name'], PATHINFO_EXTENSION));
            $allowedTypes = ['jpg', 'jpeg', 'png'];

            // Validasi: file harus sesuai format yang diizinkan
            $check = getimagesize($_FILES['foto_bukti']['tmp_name']);
            if ($check === false || !in_array($ext, $allowedTypes)) {
                $error = 'Format file tidak valid. Gunakan JPG, JPEG, atau PNG.';
            } else {
                // Hapus foto lama jika ada
                if ($laporan['foto_bukti'] && file_exists('uploads/' . $laporan['foto_bukti'])) {
                    unlink('uploads/' . $laporan['foto_bukti']);
                }
                // Simpan foto baru
                $namaFile   = time() . '_' . uniqid() . '.' . $ext;
                move_uploaded_file($_FILES['foto_bukti']['tmp_name'], 'uploads/' . $namaFile);
                $foto_bukti = $namaFile;
            }
        }

        if (empty($error)) {
            // UPDATE data di database menggunakan POST
            $sql  = "UPDATE laporan SET lokasi=?, waktu_laporan=?, jumlah_kendaraan=?, status_pelanggaran=?, deskripsi=?, foto_bukti=? WHERE id=? AND user_id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssisssii", $lokasi, $waktu_laporan, $jml, $status, $deskripsi, $foto_bukti, $id, $user_id);
            $stmt->execute();
            $stmt->close();

            header("Location: laporan.php?success=edit");
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Laporan - SmartParking</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<?php require 'navbar.php'; ?>

<div class="container">
    <div class="page-header">
        <h2>Edit Laporan Parkir Liar</h2>
        <a href="laporan.php" class="btn btn-secondary">← Kembali</a>
    </div>

    <div class="card">
        <?php if ($error): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <!-- Form edit menggunakan POST + enctype multipart untuk upload file -->
        <form method="POST" action="edit_laporan.php?id=<?= $id ?>" enctype="multipart/form-data">

            <div class="form-row">
                <div class="form-group">
                    <label>Lokasi Kejadian</label>
                    <input type="text" name="lokasi" value="<?= htmlspecialchars($_POST['lokasi'] ?? $laporan['lokasi']) ?>" required>
                </div>
                <div class="form-group">
                    <label>Waktu Laporan</label>
                    <?php
                    // Format datetime untuk input datetime-local
                    $waktu = $_POST['waktu_laporan'] ?? date('Y-m-d\TH:i', strtotime($laporan['waktu_laporan']));
                    ?>
                    <input type="datetime-local" name="waktu_laporan" value="<?= $waktu ?>" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Jumlah Kendaraan</label>
                    <input type="number" name="jumlah_kendaraan" value="<?= htmlspecialchars($_POST['jumlah_kendaraan'] ?? $laporan['jumlah_kendaraan']) ?>" min="0" required>
                    <small style="color:#888;font-size:12px">1-5: Ringan | 6-15: Sedang | &gt;15: Berat</small>
                </div>
                <div class="form-group">
                    <label>Foto Bukti Baru <small>(biarkan kosong jika tidak diganti)</small></label>
                    <input type="file" name="foto_bukti" accept=".jpg,.jpeg,.png">
                    <?php if ($laporan['foto_bukti']): ?>
                        <small style="color:#666">Foto saat ini:
                            <a href="uploads/<?= htmlspecialchars($laporan['foto_bukti']) ?>" target="_blank" class="link-foto">
                                <?= htmlspecialchars($laporan['foto_bukti']) ?>
                            </a>
                        </small>
                    <?php endif; ?>
                </div>
            </div>

            <div class="form-group">
                <label>Deskripsi Kondisi</label>
                <textarea name="deskripsi"><?= htmlspecialchars($_POST['deskripsi'] ?? $laporan['deskripsi']) ?></textarea>
            </div>

            <div style="display:flex;gap:12px">
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="laporan.php" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

<?php require 'footer.php'; ?>
</body>
</html>