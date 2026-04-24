<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
require 'koneksi.php';

$uid = $_SESSION['user_id'];

$total   = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM monitoring WHERE user_id=$uid"))['c'];
$lancar  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM monitoring WHERE user_id=$uid AND status_kemacetan='Lancar'"))['c'];
$padat   = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM monitoring WHERE user_id=$uid AND status_kemacetan='Padat'"))['c'];
$macet   = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM monitoring WHERE user_id=$uid AND status_kemacetan='Macet'"))['c'];

$recent  = mysqli_query($conn, "SELECT * FROM monitoring WHERE user_id=$uid ORDER BY waktu_monitoring DESC LIMIT 5");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - SmartTraffic Cam</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="container">
    <div class="top-bar">
        <h1>Dashboard</h1>
        <a href="tambah_monitoring.php" class="btn btn-success">+ Tambah Monitoring</a>
    </div>

    <div class="stats">
        <div class="stat-box">
            <div class="num"><?= $total ?></div>
            <div class="label">Total Data</div>
        </div>
        <div class="stat-box lancar">
            <div class="num"><?= $lancar ?></div>
            <div class="label">Lancar</div>
        </div>
        <div class="stat-box padat">
            <div class="num"><?= $padat ?></div>
            <div class="label">Padat</div>
        </div>
        <div class="stat-box macet">
            <div class="num"><?= $macet ?></div>
            <div class="label">Macet</div>
        </div>
    </div>

    <div class="card">
        <h2>Data Monitoring Terbaru</h2>
        <table>
            <thead>
                <tr>
                    <th>Lokasi CCTV</th>
                    <th>Waktu</th>
                    <th>Kendaraan</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($row = mysqli_fetch_assoc($recent)): ?>
                <tr>
                    <td><?= htmlspecialchars($row['lokasi_cctv']) ?></td>
                    <td><?= $row['waktu_monitoring'] ?></td>
                    <td><?= $row['jumlah_kendaraan'] ?></td>
                    <td>
                        <?php $s = strtolower($row['status_kemacetan']); ?>
                        <span class="badge badge-<?= $s ?>"><?= $row['status_kemacetan'] ?></span>
                    </td>
                </tr>
            <?php endwhile; ?>
            <?php if ($total == 0): ?>
                <tr><td colspan="4" style="text-align:center;color:#999">Belum ada data monitoring.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
        <br>
        <a href="monitoring.php" class="btn btn-primary">Lihat Semua Data</a>
    </div>
</div>
<?php include 'footer.php'; ?>
</body>
</html>