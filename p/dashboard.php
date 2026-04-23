<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/koneksi.php';

require_login();

$userId = current_user_id();
$summary = ['total' => 0, 'ringan' => 0, 'sedang' => 0, 'berat' => 0];

$summaryQuery = "
    SELECT
        COUNT(*) AS total,
        SUM(CASE WHEN status_pelanggaran = 'Ringan' THEN 1 ELSE 0 END) AS ringan,
        SUM(CASE WHEN status_pelanggaran = 'Sedang' THEN 1 ELSE 0 END) AS sedang,
        SUM(CASE WHEN status_pelanggaran = 'Berat' THEN 1 ELSE 0 END) AS berat
    FROM laporan
    WHERE user_id = ?
";
$summaryStmt = mysqli_prepare($koneksi, $summaryQuery);
mysqli_stmt_bind_param($summaryStmt, 'i', $userId);
mysqli_stmt_execute($summaryStmt);
$summaryResult = mysqli_stmt_get_result($summaryStmt);
if ($summaryResult) {
    $summary = mysqli_fetch_assoc($summaryResult) ?: $summary;
}
mysqli_stmt_close($summaryStmt);

$latestReports = [];
$latestStmt = mysqli_prepare($koneksi, 'SELECT id, lokasi, waktu_laporan, jumlah_kendaraan, status_pelanggaran FROM laporan WHERE user_id = ? ORDER BY waktu_laporan DESC, id DESC LIMIT 5');
mysqli_stmt_bind_param($latestStmt, 'i', $userId);
mysqli_stmt_execute($latestStmt);
$latestResult = mysqli_stmt_get_result($latestStmt);
if ($latestResult) {
    while ($row = mysqli_fetch_assoc($latestResult)) {
        $latestReports[] = $row;
    }
}
mysqli_stmt_close($latestStmt);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | SmartParking Report</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <?php include __DIR__ . '/navbar.php'; ?>
    <main class="container page-layout">
        <section class="page-intro">
            <p class="eyebrow">Dashboard Operator</p>
            <h2>Ringkasan laporan parkir liar milik <?= htmlspecialchars(current_user_name()); ?></h2>
            <p>Semua data di bawah ini hanya menampilkan laporan berdasarkan user yang sedang login.</p>
        </section>

        <?php $flash = get_flash(); ?>
        <?php if ($flash): ?>
            <div class="alert <?= $flash['type'] === 'success' ? 'alert-success' : 'alert-danger'; ?>">
                <?= htmlspecialchars($flash['message']); ?>
            </div>
        <?php endif; ?>

        <section class="stats-grid">
            <article class="stat-card"><span>Total Laporan</span><strong><?= (int) ($summary['total'] ?? 0); ?></strong></article>
            <article class="stat-card"><span>Pelanggaran Ringan</span><strong><?= (int) ($summary['ringan'] ?? 0); ?></strong></article>
            <article class="stat-card"><span>Pelanggaran Sedang</span><strong><?= (int) ($summary['sedang'] ?? 0); ?></strong></article>
            <article class="stat-card"><span>Pelanggaran Berat</span><strong><?= (int) ($summary['berat'] ?? 0); ?></strong></article>
        </section>

        <section class="panel">
            <div class="panel-heading">
                <div>
                    <p class="eyebrow">Laporan Terbaru</p>
                    <h3>5 data terbaru</h3>
                </div>
                <a href="tambah_laporan.php" class="btn btn-primary">Tambah Laporan</a>
            </div>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Lokasi</th>
                            <th>Waktu</th>
                            <th>Jumlah Kendaraan</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!$latestReports): ?>
                            <tr><td colspan="4" class="empty-state">Belum ada laporan.</td></tr>
                        <?php else: ?>
                            <?php foreach ($latestReports as $report): ?>
                                <tr>
                                    <td><?= htmlspecialchars($report['lokasi']); ?></td>
                                    <td><?= htmlspecialchars(date('d-m-Y H:i', strtotime($report['waktu_laporan']))); ?></td>
                                    <td><?= (int) $report['jumlah_kendaraan']; ?></td>
                                    <td><span class="badge badge-<?= strtolower($report['status_pelanggaran']); ?>"><?= htmlspecialchars($report['status_pelanggaran']); ?></span></td>
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
