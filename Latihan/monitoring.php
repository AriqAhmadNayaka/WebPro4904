<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
require 'koneksi.php';

$uid  = $_SESSION['user_id'];
$data = mysqli_query($conn, "SELECT * FROM monitoring WHERE user_id=$uid ORDER BY waktu_monitoring DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Monitoring - SmartTraffic Cam</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="container">
    <div class="top-bar">
        <h1>Data Monitoring</h1>
        <a href="tambah_monitoring.php" class="btn btn-success">+ Tambah</a>
    </div>

    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Lokasi CCTV</th>
                    <th>Waktu</th>
                    <th>Kendaraan</th>
                    <th>Status</th>
                    <th>Deskripsi</th>
                    <th>Foto</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php $no = 1; while ($row = mysqli_fetch_assoc($data)): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($row['lokasi_cctv']) ?></td>
                    <td><?= $row['waktu_monitoring'] ?></td>
                    <td><?= $row['jumlah_kendaraan'] ?></td>
                    <td>
                        <?php $s = strtolower($row['status_kemacetan']); ?>
                        <span class="badge badge-<?= $s ?>"><?= $row['status_kemacetan'] ?></span>
                    </td>
                    <td><?= htmlspecialchars($row['deskripsi']) ?></td>
                    <td>
                        <?php if ($row['foto_bukti']): ?>
                            <a href="uploads/<?= $row['foto_bukti'] ?>" target="_blank">Lihat</a>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="edit_monitoring.php?id=<?= $row['id'] ?>" class="btn btn-secondary" style="font-size:12px;padding:4px 10px">Edit</a>
                        <a href="hapus_monitoring.php?id=<?= $row['id'] ?>" class="btn btn-danger" style="font-size:12px;padding:4px 10px" onclick="return confirm('Hapus data ini?')">Hapus</a>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include 'footer.php'; ?>
</body>
</html>