<?php
session_start();

// Cek session
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require 'koneksi.php';

$user_id = $_SESSION['user_id'];
$success = $_GET['success'] ?? '';

// READ: Ambil semua laporan milik user yang login menggunakan SELECT
$result = $conn->query("SELECT * FROM laporan WHERE user_id = $user_id ORDER BY waktu_laporan DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Laporan - SmartParking</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<?php require 'navbar.php'; ?>

<div class="container">

    <div class="page-header">
        <h2>Daftar Laporan Parkir Liar</h2>
        <a href="tambah_laporan.php" class="btn btn-primary">+ Tambah Laporan</a>
    </div>

    <?php if ($success === 'tambah'): ?>
        <div class="alert alert-success">Laporan berhasil ditambahkan!</div>
    <?php elseif ($success === 'edit'): ?>
        <div class="alert alert-success">Laporan berhasil diperbarui!</div>
    <?php elseif ($success === 'hapus'): ?>
        <div class="alert alert-success">Laporan berhasil dihapus!</div>
    <?php endif; ?>

    <div class="card">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Lokasi</th>
                        <th>Waktu</th>
                        <th>Jml Kendaraan</th>
                        <th>Status</th>
                        <th>Deskripsi</th>
                        <th>Foto Bukti</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows === 0): ?>
                        <tr><td colspan="8" style="text-align:center;color:#999;padding:20px">Belum ada laporan.</td></tr>
                    <?php else: ?>
                        <?php $no = 1; while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($row['lokasi']) ?></td>
                            <td><?= $row['waktu_laporan'] ?></td>
                            <td><?= $row['jumlah_kendaraan'] ?></td>
                            <td>
                                <span class="badge badge-<?= strtolower($row['status_pelanggaran']) ?>">
                                    <?= htmlspecialchars($row['status_pelanggaran']) ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars($row['deskripsi']) ?></td>
                            <td>
                                <?php if ($row['foto_bukti']): ?>
                                    <a href="uploads/<?= htmlspecialchars($row['foto_bukti']) ?>" target="_blank" class="link-foto">
                                        <img src="uploads/<?= htmlspecialchars($row['foto_bukti']) ?>" class="foto-preview" alt="foto">
                                    </a>
                                <?php else: ?>
                                    <span style="color:#999;font-size:13px">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <!-- GET untuk mengambil id edit dan id hapus -->
                                <a href="edit_laporan.php?id=<?= $row['id'] ?>" class="btn btn-warning">Edit</a>
                                <a href="hapus_laporan.php?id=<?= $row['id'] ?>"
                                   class="btn btn-danger"
                                   onclick="return confirm('Yakin ingin menghapus laporan ini?')">Hapus</a>
                            </td>
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