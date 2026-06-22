<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/koneksi.php';

require_login();

$userId = current_user_id();
$summary = ['total' => 0, 'aman' => 0, 'waspada' => 0, 'overload' => 0];

$summaryQuery = "
    SELECT
        COUNT(*) AS total,
        SUM(CASE WHEN status_kapasitas = 'Aman' THEN 1 ELSE 0 END) AS aman,
        SUM(CASE WHEN status_kapasitas = 'Waspada' THEN 1 ELSE 0 END) AS waspada,
        SUM(CASE WHEN status_kapasitas = 'Overload' THEN 1 ELSE 0 END) AS overload
    FROM monitor
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
$latestStmt = mysqli_prepare($koneksi, 'SELECT id, lokasi_tps, waktu_pemantauan, berat_sampah, status_kapasitas FROM monitor WHERE user_id = ? ORDER BY waktu_pemantauan DESC, id DESC LIMIT 5');
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
    <title>Dashboard | SmartTrash Monitoring</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <?php include __DIR__ . '/navbar.php'; ?>
    <main class="container page-layout">
        <section class="page-intro">
            <p class="eyebrow">Dashboard Operator</p>
            <h2>Ringkasan pemantauan sampah TPS milik <?= htmlspecialchars(current_user_name()); ?></h2>
            <p>Semua data di bawah ini hanya menampilkan pemantauan berdasarkan user yang sedang login.</p>
        </section>

        <?php $flash = get_flash(); ?>
        <?php if ($flash): ?>
            <div class="alert <?= $flash['type'] === 'success' ? 'alert-success' : 'alert-danger'; ?>">
                <?= htmlspecialchars($flash['message']); ?>
            </div>
        <?php endif; ?>

        <section class="stats-grid">
            <article class="stat-card"><span>Total Pemantauan</span><strong><?= (int) ($summary['total'] ?? 0); ?></strong></article>
            <article class="stat-card"><span>Pemantauan Aman</span><strong><?= (int) ($summary['aman'] ?? 0); ?></strong></article>
            <article class="stat-card"><span>Pelanggaran Waspada</span><strong><?= (int) ($summary['waspada'] ?? 0); ?></strong></article>
            <article class="stat-card"><span>Pelanggaran Overload</span><strong><?= (int) ($summary['overload'] ?? 0); ?></strong></article>
        </section>

        <section class="panel">
            <div class="panel-heading">
                <div>
                    <p class="eyebrow">Pemantauan Terbaru</p>
                    <h3>5 data terbaru</h3>
                </div>
                <a href="tambah_pemantauan.php" class="btn btn-primary">Tambah Pemantauan</a>
            </div>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Lokasi</th>
                            <th>Waktu</th>
                            <th>Berat Sampah</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!$latestReports): ?>
                            <tr><td colspan="4" class="empty-state">Pemantauan tidak ditemukan.</td></tr>
                        <?php else: ?>
                            <?php foreach ($latestReports as $report): ?>
                                <tr>
                                    <td><?= htmlspecialchars($report['lokasi_tps']); ?></td>
                                    <td><?= htmlspecialchars(date('d-m-Y H:i', strtotime($report['waktu_pemantauan']))); ?></td>
                                    <td><?= (int) $report['berat_sampah']; ?></td>
                                    <td><span class="badge badge-<?= strtolower($report['status_kapasitas']); ?>"><?= htmlspecialchars($report['status_kapasitas']); ?></span></td>
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
