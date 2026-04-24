<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
require 'koneksi.php';

$error = '';

// Helper: tentukan status dari jumlah kendaraan
function tentukanStatus($jumlah) {
    if ($jumlah <= 20)  return 'Lancar';
    if ($jumlah <= 50)  return 'Padat';
    return 'Macet';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uid      = $_SESSION['user_id'];
    $lokasi   = trim($_POST['lokasi_cctv']);
    $waktu    = $_POST['waktu_monitoring'];
    $jumlah   = (int)$_POST['jumlah_kendaraan'];
    $status   = tentukanStatus($jumlah);
    $deskripsi= trim($_POST['deskripsi']);
    $foto     = '';

    // Validasi
    if (empty($lokasi) || empty($waktu) || empty($jumlah)) {
        $error = "Lokasi, waktu, dan jumlah kendaraan wajib diisi.";
    } elseif (!is_numeric($_POST['jumlah_kendaraan']) || $jumlah < 0) {
        $error = "Jumlah kendaraan harus berupa angka positif.";
    } else {
        // Upload foto
        if (!empty($_FILES['foto_bukti']['name'])) {
            $ext = strtolower(pathinfo($_FILES['foto_bukti']['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, ['jpg','jpeg','png'])) {
                $error = "Format foto harus jpg, jpeg, atau png.";
            } else {
                $foto = time() . '_' . basename($_FILES['foto_bukti']['name']);
                move_uploaded_file($_FILES['foto_bukti']['tmp_name'], "uploads/" . $foto);
            }
        }

        if (!$error) {
            $sql = "INSERT INTO monitoring (user_id, lokasi_cctv, waktu_monitoring, jumlah_kendaraan, status_kemacetan, deskripsi, foto_bukti)
                    VALUES ($uid, '".mysqli_real_escape_string($conn,$lokasi)."', '$waktu', $jumlah, '$status', '".mysqli_real_escape_string($conn,$deskripsi)."', '$foto')";
            if (mysqli_query($conn, $sql)) {
                header("Location: monitoring.php");
                exit;
            } else {
                $error = "Gagal menyimpan data.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Monitoring - SmartTraffic Cam</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="container">
    <div class="card" style="max-width:600px;margin:auto">
        <h2>Tambah Data Monitoring</h2>
        <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Lokasi CCTV</label>
                <input type="text" name="lokasi_cctv" placeholder="cth: Simpang Lima, Pasar Kota" required>
            </div>
            <div class="form-group">
                <label>Waktu Monitoring</label>
                <input type="datetime-local" name="waktu_monitoring" required>
            </div>
            <div class="form-group">
                <label>Jumlah Kendaraan</label>
                <input type="number" name="jumlah_kendaraan" min="0" placeholder="0-20: Lancar | 21-50: Padat | >50: Macet" required>
            </div>
            <div class="form-group">
                <label>Deskripsi Kondisi</label>
                <textarea name="deskripsi" placeholder="Penjelasan singkat kondisi lalu lintas..."></textarea>
            </div>
            <div class="form-group">
                <label>Foto Bukti (jpg/jpeg/png)</label>
                <input type="file" name="foto_bukti" accept=".jpg,.jpeg,.png">
            </div>
            <button type="submit" class="btn btn-success">Simpan</button>
            <a href="monitoring.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
<?php include 'footer.php'; ?>
</body>
</html>