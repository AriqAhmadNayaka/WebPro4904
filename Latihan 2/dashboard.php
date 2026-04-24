<?php
session_start();

// Cek session: jika belum login, tidak bisa akses dashboard
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require 'koneksi.php';

$user_id = $_SESSION['user_id'];

// Ambil statistik milik user yang login menggunakan SELECT COUNT
$total   = $conn->query("SELECT COUNT(*) AS n FROM laporan WHERE user_id = $user_id")->fetch_assoc()['n'];
$ringan  = $conn->query("SELECT COUNT(*) AS n FROM laporan WHERE user_id = $user_id AND status_pelanggaran = 'Ringan'")->fetch_assoc()['n'];
$sedang  = $conn->query("SELECT COUNT(*) AS n FROM laporan WHERE user_id = $user_id AND status_pelanggaran = 'Sedang'")->fetch_assoc()['n'];
$berat   = $conn->query("SELECT COUNT(*) AS n FROM laporan WHERE user_id = $user_id AND status_pelanggaran = 'Berat'")->fetch_assoc()['n'];

// Ambil 5 laporan terbaru milik user
$result = $conn->query("SELECT * FROM laporan WHERE user_id = $user_id ORDER BY waktu_laporan DESC LIMIT 5");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - SmartParking</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<?php require 'navbar.php'; ?>

<div class="container">

    <div class="page-header">
        <h2>Dashboard</h2>
        <span style="color:#666;font-size:14px;">Selamat datang, <strong><?= htmlspecialchars($_SESSION['nama']) ?></strong></span>
    </div>

    <!-- Statistik -->
    <div class="stats-row">
        <div class="stat-card blue">
            <div class="num"><?= $total ?></div>
            <div class="label">Total Laporan</div>
        </div>
        <div class="stat-card green">
            <div class="num"><?= $ringan ?></div>
            <div class="label">Pelanggaran Ringan</div>
        </div>
        <div class="stat-card orange">
            <div class="num"><?= $sedang ?></div>
            <div class="label">Pelanggaran Sedang</div>
        </div>
        <div class="stat-card red">
            <div class="num"><?= $berat ?></div>
            <div class="label">Pelanggaran Berat</div>
        </div>
    </div>

    <!-- Laporan Terbaru -->
    <div class="card">
        <div class="page-header">
            <h2>Laporan Terbaru</h2>
            <a href="laporan.php" class="btn btn-primary btn-sm">Lihat Semua</a>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Lokasi</th>
                        <th>Waktu</th>
                        <th>Jml Kendaraan</th>
                        <th>Status</th>
                        <th>Deskripsi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows === 0): ?>
                        <tr><td colspan="5" style="text-align:center;color:#999;padding:20px">Belum ada laporan. <a href="tambah_laporan.php">Tambah sekarang</a></td></tr>
                    <?php else: ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['lokasi']) ?></td>
                            <td><?= $row['waktu_laporan'] ?></td>
                            <td><?= $row['jumlah_kendaraan'] ?></td>
                            <td>
                                <span class="badge badge-<?= strtolower($row['status_pelanggaran']) ?>">
                                    <?= htmlspecialchars($row['status_pelanggaran']) ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars($row['deskripsi']) ?></td>
                        </tr>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<?php require 'footer.php'; ?>
</body>
</html>