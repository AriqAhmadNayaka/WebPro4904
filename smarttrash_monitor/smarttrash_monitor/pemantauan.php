<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/koneksi.php';

require_login();

$userId = current_user_id();
$reports = [];

$stmt = mysqli_prepare($koneksi, 'SELECT id, lokasi_tps, waktu_pemantauan, berat_sampah, status_kapasitas, deskripsi, foto_bukti FROM monitor WHERE user_id = ? ORDER BY waktu_pemantauan DESC, id DESC');
mysqli_stmt_bind_param($stmt, 'i', $userId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $reports[] = $row;
    }
}
mysqli_stmt_close($stmt);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pemantauan | SmartParking Monitoring</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <?php include __DIR__ . '/navbar.php'; ?>
    <main class="container page-layout">
        <section class="page-intro">
            <p class="eyebrow">CRUD Pemantauan</p>
            <h2>Daftar Pemantauan Sampahr</h2>
            <p>Data ditampilkan dalam tabel lengkap beserta file bukti, edit, dan hapus menggunakan parameter GET.</p>
        </section>

        <?php $flash = get_flash(); ?>
        <?php if ($flash): ?>
            <div class="alert <?= $flash['type'] === 'success' ? 'alert-success' : 'alert-danger'; ?>">
                <?= htmlspecialchars($flash['message']); ?>
            </div>
        <?php endif; ?>

        <section class="panel">
            <div class="panel-heading">
                <div>
                    <p class="eyebrow">Tabel monitor</p>
                    <h3>Semua monitor user login</h3>
                </div>
                <a href="tambah_pemantauan.php" class="btn btn-primary">Tambah Data</a>
            </div>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Lokasi</th>
                            <th>Waktu</th>
                            <th>Jumlah Sampah</th>
                            <th>Status</th>
                            <th>Deskripsi</th>
                            <th>File Bukti</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!$reports): ?>
                            <tr><td colspan="7" class="empty-state">Belum ada pematauan yang tersimpan.</td></tr>
                        <?php else: ?>
                            <?php foreach ($reports as $report): ?>
                                <tr>
                                    <td><?= htmlspecialchars($report['lokasi_tps']); ?></td>
                                    <td><?= htmlspecialchars(date('d-m-Y H:i', strtotime($report['waktu_pemantauan']))); ?></td>
                                    <td><?= (int) $report['berat_sampah']; ?></td>
                                    <td><span class="badge badge-<?= strtolower($report['status_kapasitas']); ?>"><?= htmlspecialchars($report['status_kapasitas']); ?></span></td>
                                    <td><?= nl2br(htmlspecialchars($report['deskripsi'])); ?></td>
                                    <td>
                                        <?php if (!empty($report['foto_bukti']) && file_exists(__DIR__ . '/' . $report['foto_bukti'])): ?>
                                            <a href="<?= htmlspecialchars($report['foto_bukti']); ?>" target="_blank">Lihat File</a>
                                        <?php else: ?>
                                            <span class="muted-text">Tidak ada file</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="action-cell">
                                        <a class="btn btn-small" href="edit_pemantauan.php?id=<?= (int) $report['id']; ?>">Edit</a>
                                        <a class="btn btn-small btn-danger" href="hapus_pemantauan.php?id=<?= (int) $report['id']; ?>" onclick="return confirm('Yakin ingin menghapus pemantauan ini?')">Hapus</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
    <?php include __DIR__ . '/footer.php'; ?>
</body>
</html>
