<?php
session_start();
require_once '../app/bootstrap.php';

//hanya orang tua yang bisa akses halaman ini
//sara$auth->requireLogin('../auth.php');

//ambil data user yang sedang login
$currentUser = $auth->user();
$namaUser = $currentUser['name'] ?? 'Pengguna';
$userId = $auth->id();

//data lama yg belum ada user_id akan diambil ke user_id yang sesuai dengan session login
$childRepository->adoptLegacyRowsForUser($userId);
$data = $childRepository->firstByUser($userId);

//siapkan data untuk ditampilkan di dashboard
$namaAnak = $data['nama'] ?? '-';
$jadwalTerdekat = $data['jadwal'] ?? 'Belum ada';
$progressAnak = isset($data['progress']) ? (int) $data['progress'] : 0;
$komunikasi = isset($data['komunikasi']) ? (int) $data['komunikasi'] : 0;
$kemandirian = isset($data['kemandirian']) ? (int) $data['kemandirian'] : 0;
$vokasional = isset($data['vokasional']) ? (int) $data['vokasional'] : 0;
$fotoAnak = $data['foto'] ?? '';
$judulRingkasan = 'Ringkasan Data Anak';
$targetRingkasan = $namaAnak !== '-' ? $namaAnak : 'anak Anda';
$keteranganFoto = 'Berikut ringkasan perkembangan dan aktivitas ' . $targetRingkasan;

// ambil jadwal
$queryJadwal = "SELECT * FROM jadwal";
$resultJadwal = $database->getConnection()->query($queryJadwal);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Orang Tua</title>

    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet"/>
    <link rel="stylesheet" href="styles.css">
</head>

<body>

<!--sidebar-->
<div class="sidebar">
    <h2 class="logo">InkluSkill</h2>

    <a href="#" class="active"><i class="ri-dashboard-line"></i>Dashboard</a>
    <a href="../data anak/index.php"><i class="ri-user-3-line"></i>Data Anak</a>
    <a href="../jadwal/index.php"><i class="ri-calendar-check-line"></i>Jadwal Pelatihan</a>
    <a href="../perkembangan/index.php"><i class="ri-bar-chart-line"></i>Status & Perkembangan</a>
    <a href="../profil/index.php"><i class="ri-user-settings-line"></i>Profil</a>

    <a href="../auth.php?logout=1" class="logout"><i class="ri-logout-circle-line"></i>Logout</a>
</div>

<div class="content">

    <!-- HEADER -->
    <div class="header">
        <h1><i class="ri-bar-chart-fill"></i> Statistik Anak</h1>
        <p><?= htmlspecialchars($namaUser, ENT_QUOTES, 'UTF-8') ?>, ringkasan perkembangan & aktivitas anak Anda</p>
    </div>

    <?php if ($fotoAnak !== ''): ?>
        <div class="big-card">
            <h3><?= htmlspecialchars($judulRingkasan, ENT_QUOTES, 'UTF-8') ?></h3>
            <p><?= htmlspecialchars($keteranganFoto, ENT_QUOTES, 'UTF-8') ?></p>
            <img src="../<?= htmlspecialchars($fotoAnak, ENT_QUOTES, 'UTF-8') ?>" alt="Foto Anak" style="width:120px;height:120px;border-radius:20px;object-fit:cover;border:4px solid #eceaff;">
        </div>
    <?php endif; ?>

    <!-- STAT CARDS -->
    <div class="stat-row">
        <div class="stat-card purple">
            <i class="ri-user-3-line"></i>
            <div>
                <p>Nama Anak</p>
                <h2><?= htmlspecialchars($namaAnak, ENT_QUOTES, 'UTF-8') ?></h2>
            </div>
        </div>

        <div class="stat-card green">
            <i class="ri-calendar-check-line"></i>
            <div>
                <p>Jadwal Terdekat</p>
                <h2><?= htmlspecialchars($jadwalTerdekat, ENT_QUOTES, 'UTF-8') ?></h2>
            </div>
        </div>

        <div class="stat-card red">
            <i class="ri-bar-chart-box-line"></i>
            <div>
                <p>Progres</p>
                <h2><?= $progressAnak ?>%</h2>
            </div>
        </div>
    </div>

    <!-- PROGRESS -->
    <div class="big-card">
        <h3>Perkembangan Anak</h3>

        <div class="progress-item">
            <span>Komunikasi</span>
            <div class="progress-bar">
                <div class="fill" id="pb1"></div>
            </div>
        </div>

        <div class="progress-item">
            <span>Kemandirian</span>
            <div class="progress-bar">
                <div class="fill" id="pb2"></div>
            </div>
        </div>

        <div class="progress-item">
            <span>Keterampilan Vokasional</span>
            <div class="progress-bar">
                <div class="fill" id="pb3"></div>
            </div>
        </div>
    </div>

    <!-- JADWAL -->
    <div class="big-card">
        <h3>Jadwal Mingguan</h3>
        <div class="jadwal-list">

            <?php while ($resultJadwal && ($j = $resultJadwal->fetch_assoc())) { ?>
                <div class="jadwal-item">
                    <i class="ri-calendar-event-line"></i>
                    <div class="jadwal-text">
                        <b><?= htmlspecialchars($j['hari'] ?? '-', ENT_QUOTES, 'UTF-8') ?></b><br>
                        <span><?= htmlspecialchars($j['kegiatan'] ?? '-', ENT_QUOTES, 'UTF-8') ?></span>
                    </div>
                </div>
            <?php } ?>

        </div>
    </div>

</div>

<!-- dari js ke php -->
<script>
setTimeout(() => {
    document.getElementById("pb1").style.width = "<?= $komunikasi ?>%";
    document.getElementById("pb2").style.width = "<?= $kemandirian ?>%";
    document.getElementById("pb3").style.width = "<?= $vokasional ?>%";
}, 200);
</script>

</body>
</html>
