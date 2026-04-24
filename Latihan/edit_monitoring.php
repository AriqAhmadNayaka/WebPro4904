<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
require 'koneksi.php';

$uid = $_SESSION['user_id'];
$id  = (int)$_GET['id'];
$error = '';

function tentukanStatus($jumlah) {
    if ($jumlah <= 20)  return 'Lancar';
    if ($jumlah <= 50)  return 'Padat';
    return 'Macet';
}

// Ambil data
$res = mysqli_query($conn, "SELECT * FROM monitoring WHERE id=$id AND user_id=$uid");
$row = mysqli_fetch_assoc($res);
if (!$row) {
    echo "Data tidak ditemukan."; exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lokasi   = trim($_POST['lokasi_cctv']);
    $waktu    = $_POST['waktu_monitoring'];
    $jumlah   = (int)$_POST['jumlah_kendaraan'];
    $status   = tentukanStatus($jumlah);
    $deskripsi= trim($_POST['deskripsi']);
    $foto     = $row['foto_bukti']; // default foto lama

    if (empty($lokasi) || empty($waktu) || $jumlah < 0) {
        $error = "Lokasi, waktu, dan jumlah kendaraan wajib diisi.";
    } else {
        // Upload foto baru jika ada
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
            $sql = "UPDATE monitoring SET 
                        lokasi_cctv='".mysqli_real_escape_string($conn,$lokasi)."',
                        waktu_monitoring='$waktu',
                        jumlah_kendaraan=$jumlah,
                        status_kemacetan='$status',
                        deskripsi='".mysqli_real_escape_string($conn,$deskripsi)."',
                        foto_bukti='$foto'
                    WHERE id=$id AND user_id=$uid";
            if (mysqli_query($conn, $sql)) {
                header("Location: monitoring.php");
                exit;
            } else {
                $error = "Gagal mengupdate data.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Monitoring - SmartTraffic Cam</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="container">
    <div class="card" style="max-width:600px;margin:auto">
        <h2>Edit Data Monitoring</h2>
        <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Lokasi CCTV</label>
                <input type="text" name="lokasi_cctv" value="<?= htmlspecialchars($row['lokasi_cctv']) ?>" required>
            </div>
            <div class="form-group">
                <label>Waktu Monitoring</label>
                <input type="datetime-local" name="waktu_monitoring" value="<?= str_replace(' ','T',$row['waktu_monitoring']) ?>" required>
            </div>
            <div class="form-group">
                <label>Jumlah Kendaraan</label>
                <input type="number" name="jumlah_kendaraan" value="<?= $row['jumlah_kendaraan'] ?>" min="0" required>
            </div>
            <div class="form-group">
                <label>Deskripsi Kondisi</label>
                <textarea name="deskripsi"><?= htmlspecialchars($row['deskripsi']) ?></textarea>
            </div>
            <div class="form-group">
                <label>Foto Bukti (kosongkan jika tidak diganti)</label>
                <?php if ($row['foto_bukti']): ?>
                    <p style="font-size:13px;margin-bottom:6px">Foto saat ini: <a href="uploads/<?= $row['foto_bukti'] ?>" target="_blank"><?= $row['foto_bukti'] ?></a></p>
                <?php endif; ?>
                <input type="file" name="foto_bukti" accept=".jpg,.jpeg,.png">
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="monitoring.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
<?php include 'footer.php'; ?>
</body>
</html>