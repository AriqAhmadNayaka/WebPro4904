<?php
session_start();

if (!isset($_SESSION['current_user'])) {
    header('Location: ../../auth.php');
    exit;
}

// DashboardModel child class dari Database untuk mengambil data dashboard
// Sesuai modul 6.4.8: DashboardModel extends Database, mewarisi koneksi otomatis
require_once 'classes/../DashboardModel.php';

// Buat objek DashboardModel konstruktor otomatis membuka koneksi ke database
// Sesuai modul 6.4.5: object dibuat dengan kata kunci "new"
$dashboard = new DashboardModel();

// Panggil method getStatistik() untuk mengambil data stat cards dari database
// Sesuai modul 6.4.4: method adalah fungsi yang ada di dalam class
$statistik = $dashboard->getStatistik();
$totalSiswa = $statistik['total_siswa'];
$siswaAktif = $statistik['siswa_aktif'];
$pelatihanAktif = $statistik['pelatihan_aktif'];
$laporanMasuk = $statistik['laporan_masuk'];

// Panggil method getAktivitas() untuk mengambil aktivitas terbaru
$aktivitas = $dashboard->getAktivitas();

// Panggil method getChartData() untuk mengambil data chart
$chartData = $dashboard->getChartData();

// Nama sekolah diambil dari session yang tersimpan saat login
$namaSekolah = htmlspecialchars($_SESSION['current_user']['name'] ?? 'Sekolah');

// json_encode() mengubah array PHP menjadi format JSON agar bisa dibaca Chart.js di browser
$aktivitasJson = json_encode($aktivitas);
$dataChartJson = json_encode($chartData);

// unset() memanggil destruktor DashboardModel yang menutup koneksi secara otomatis
// Sesuai modul 6.4.6: __destruct dijalankan saat objek tidak lagi digunakan
unset($dashboard);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Sekolah - InkluSkill</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet"/>
  <link rel="stylesheet" href="style.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<div class="sidebar">
  <h2 class="logo">InkluSkill</h2>
  <nav class="menu">
    <a class="active"><i class="ri-dashboard-line"></i>Dashboard</a>
    <a href="../dataSiswa/index.php"><i class="ri-user-3-line"></i>Data Siswa</a>
    <a href="#"><i class="ri-file-edit-line"></i>Pendaftaran Pelatihan</a>
    <a href="#"><i class="ri-bar-chart-line"></i>Laporan</a>
    <a href="#"><i class="ri-archive-line"></i>Rekap Siswa</a>
    <a href="#"><i class="ri-user-settings-line"></i>Profil</a>
  </nav>
  <button class="logout" onclick="window.location.href='../../auth.php?logout=1'">
    <i class="ri-logout-box-line"></i> Logout
  </button>
</div>

<div class="content">
  <div class="topbar">
    <h1>Dashboard Sekolah</h1>
    <div class="user-info">
      <span><?= $namaSekolah ?></span>
      <img src="../../assets/fotonovi.webp">
    </div>
  </div>

  <div class="dashboard-body">
    <div class="header">
      <h1>Ringkasan Sekolah</h1>
      <p>Pantau data siswa dan perkembangan pelatihan</p>
    </div>

    <!-- Stat cards: data dari method getStatistik() DashboardModel -->
    <div class="stat-row">
      <div class="stat-card purple">
        <i class="ri-team-line"></i>
        <div><p>Total Siswa</p><h2><?= $totalSiswa ?></h2></div>
      </div>
      <div class="stat-card green">
        <i class="ri-user-follow-line"></i>
        <div><p>Siswa Aktif</p><h2><?= $siswaAktif ?></h2></div>
      </div>
      <div class="stat-card orange">
        <i class="ri-calendar-event-line"></i>
        <div><p>Pelatihan Aktif</p><h2><?= $pelatihanAktif ?></h2></div>
      </div>
      <div class="stat-card red">
        <i class="ri-file-warning-line"></i>
        <div><p>Laporan Masuk</p><h2><?= $laporanMasuk ?></h2></div>
      </div>
    </div>

    <div class="chart-row">
      <div class="card">
        <h3>Status Siswa</h3>
        <canvas id="statusChart"></canvas>
      </div>
      <div class="card">
        <h3>Siswa per Kelas</h3>
        <canvas id="progressChart"></canvas>
      </div>
    </div>

    <!-- Activity list, data dari method getAktivitas() DashboardModel -->
    <div class="card">
      <h3>Siswa dan Laporan Terbaru Ditambahkan</h3>
      <div id="activityList" class="activity-list"></div>
    </div>
  </div>
</div>

<a href="../chatbot/index.php" class="chatbot-float">
  <i class="ri-chat-3-line"></i>
</a>

<script>
  const aktivitas = <?= $aktivitasJson ?>;
  const dataChart = <?= $dataChartJson ?>;

  const activityList = document.getElementById("activityList");
  aktivitas.forEach(a => {
    activityList.innerHTML += `
      <div class="activity-item">
        <div class="activity-icon"><i class="${a.icon}"></i></div>
        <div><p>${a.text}</p><span class="activity-meta">${a.time}</span></div>
      </div>`;
  });

  new Chart(document.getElementById('statusChart'), {
    type: "pie",
    data: {
      labels: dataChart.status.labels,
      datasets: [{ data: dataChart.status.data, backgroundColor: ["#1BC47D", "#FF5B5B"] }]
    }
  });

  new Chart(document.getElementById('progressChart'), {
    type: "bar",
    data: {
      labels: dataChart.progress.labels,
      datasets: [{ data: dataChart.progress.data, backgroundColor: "#6C63FF" }]
    }
  });
</script>
</body>
</html>