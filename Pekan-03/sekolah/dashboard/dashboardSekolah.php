<?php
// Cek apakah user sudah login — session_start() wajib dipanggil paling atas
// sebelum ada output HTML apapun agar $_SESSION bisa diakses
session_start();

// Jika belum login, redirect ke halaman auth menggunakan header() di sisi server
if (!isset($_SESSION['current_user'])) {
    header('Location: ../../auth.php');
    exit;
}

// require dipakai karena data dashboard adalah komponen wajib —
// jika file tidak ditemukan, program langsung berhenti (fatal error)
// Berbeda dengan include yang hanya warning dan program tetap berjalan
require 'data_dashboard.php';

// Ambil nilai dari array data untuk ditampilkan, htmlspecialchars() mencegah XSS
$namaSekolah    = htmlspecialchars($infoSekolah['nama']);
$totalSiswa     = (int) $statistik['total_siswa'];
$siswaAktif     = (int) $statistik['siswa_aktif'];
$pelatihanAktif = (int) $statistik['pelatihan_aktif'];
$laporanMasuk   = (int) $statistik['laporan_masuk'];

// json_encode() mengubah array PHP menjadi format JSON agar bisa dibaca oleh Chart.js di browser
$aktivitasJson = json_encode($aktivitas);
$dataChartJson = json_encode($dataChart);
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

<!-- SIDEBAR -->
<div class="sidebar">
  <h2 class="logo">InkluSkill</h2>

  <nav class="menu">
    <a class="active"><i class="ri-dashboard-line"></i>Dashboard</a>
    <a href="../dataSiswa/index.php"><i class="ri-user-3-line"></i>Data Siswa</a>
    <a href="../daftarSiswa/index.php"><i class="ri-file-edit-line"></i>Pendaftaran Pelatihan</a>
    <a href="../laporanPerkembangan/index.php"><i class="ri-bar-chart-line"></i>Laporan</a>
    <a href="../rekapSiswa/index.php"><i class="ri-archive-line"></i>Rekap Siswa</a>
    <a href="../profil/index.php"><i class="ri-user-settings-line"></i>Profil</a>
  </nav>

  <!-- Tombol logout pakai <button> bukan <a> agar styling CSS class .logout terapply dengan benar
       ?logout=1 dikirim via GET — hanya sinyal ke server untuk menghapus session -->
  <button class="logout" onclick="window.location.href='../../auth/auth.php?logout=1'">
    <i class="ri-logout-box-line"></i> Logout
  </button>
</div>

<!-- CONTENT -->
<div class="content">

  <!-- TOPBAR -->
  <div class="topbar">
    <h1>Dashboard Sekolah</h1>
    <div class="user-info">
      <!-- Nama sekolah di-echo dari $infoSekolah yang di-require dari data_dashboard.php -->
      <span><?= $namaSekolah ?></span>
      <img src="../../assets/fotonovi.webp">
    </div>
  </div>

  <div class="dashboard-body">

    <div class="header">
      <h1>Ringkasan Sekolah</h1>
      <p>Pantau data siswa dan perkembangan pelatihan</p>
    </div>

    <!-- Stat cards — setiap nilai di-echo dari $statistik via require, murni READ -->
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

    <!-- Chart -->
    <div class="chart-row">
      <div class="card">
        <h3>Status Siswa</h3>
        <canvas id="statusChart"></canvas>
      </div>
      <div class="card">
        <h3>Progress Pelatihan</h3>
        <canvas id="progressChart"></canvas>
      </div>
    </div>

    <!-- Activity list, diisi oleh JavaScript dari data JSON di bawah -->
    <div class="card">
      <h3>Aktivitas Terbaru</h3>
      <div id="activityList" class="activity-list"></div>
    </div>

  </div>
</div>

<!-- Link ke halaman chatbot menggunakan <a> karena ini navigasi biasa (GET) -->
<a href="../chatbot/index.php" class="chatbot-float">
  <i class="ri-chat-3-line"></i>
</a>

<script>
  // Data dari PHP diteruskan ke JavaScript via json_encode()
  // Chart.js berjalan di sisi browser sehingga tidak bisa membaca variabel PHP secara langsung
  const aktivitas = <?= $aktivitasJson ?>;
  const dataChart = <?= $dataChartJson ?>;

  // Render daftar aktivitas terbaru dari data JSON ke dalam elemen HTML
  const activityList = document.getElementById("activityList");
  aktivitas.forEach(a => {
    activityList.innerHTML += `
      <div class="activity-item">
        <div class="activity-icon"><i class="${a.icon}"></i></div>
        <div>
          <p>${a.text}</p>
          <span class="activity-meta">${a.time}</span>
        </div>
      </div>
    `;
  });

  // Render pie chart status siswa dan bar chart progress pelatihan menggunakan Chart.js
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