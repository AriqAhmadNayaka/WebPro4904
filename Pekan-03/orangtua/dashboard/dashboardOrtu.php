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

<div class="sidebar">
    <h2 class="logo">InkluSkill</h2>

    <a href="#" class="active"><i class="ri-dashboard-line"></i>Dashboard</a>
    <a href="../data-anak/index.html"><i class="ri-user-3-line"></i>Data Anak</a>
    <a href="../jadwal/index.html"><i class="ri-calendar-check-line"></i>Jadwal Pelatihan</a>
    <a href="../perkembangan/index.html"><i class="ri-bar-chart-line"></i>Status & Perkembangan</a>
    <a href="../profil/index.html"><i class="ri-user-settings-line"></i>Profil</a>

    <button class="logout"><i class="ri-logout-circle-line"></i>Logout</button>
</div>

<div class="content">

    <div class="header">
        <h1><i class="ri-bar-chart-fill"></i> Statistik Anak</h1>
        <p>Ringkasan perkembangan & aktivitas anak Anda</p>
    </div>

    <!-- STAT CARDS -->
    <div class="stat-row">
        <div class="stat-card purple">
            <i class="ri-user-3-line"></i>
            <div>
                <p>Nama Anak</p>
                <h2 id="namaAnak">-</h2>
            </div>
        </div>

        <div class="stat-card green">
            <i class="ri-calendar-check-line"></i>
            <div>
                <p>Jadwal Terdekat</p>
                <h2 id="jadwalTerdekat">-</h2>
            </div>
        </div>

        <div class="stat-card red">
            <i class="ri-bar-chart-box-line"></i>
            <div>
                <p>Progres</p>
                <h2 id="progressAnak">-</h2>
            </div>
        </div>
    </div>

    <!-- Progress Section -->
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

    <!-- JADWAL MINGGUAN -->
    <div class="big-card">
        <h3>Jadwal Mingguan</h3>
        <div id="jadwalList" class="jadwal-list"></div>
    </div>

</div>

<script src="ortu-dashboard.js"></script>
</body>
</html>
