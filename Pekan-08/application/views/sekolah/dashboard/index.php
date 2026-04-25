<?php
// VIEW: sekolah/dashboard/index.php
// Halaman ringkasan dashboard sekolah.
//
// Variabel yang diterima dari Dashboard Controller (via array $data):
//   $namaSekolah    : Nama pengguna yang sedang login (dari session)
//   $totalSiswa     : Jumlah total semua siswa di tabel siswa
//   $siswaAktif     : Jumlah siswa dengan status 'Aktif'
//   $pelatihanAktif : Jumlah pelatihan dengan status 'Aktif'
//   $laporanMasuk   : Jumlah laporan dengan status 'Baru'
//   $aktivitasJson  : Data aktivitas terbaru dalam format JSON string (untuk JavaScript)
//   $dataChartJson  : Data chart status siswa & distribusi kelas dalam format JSON string
//
// Library eksternal yang digunakan:
//   - Remix Icon (CDN): untuk ikon sidebar dan UI
//   - Chart.js (CDN): untuk grafik pie (status siswa) dan bar (distribusi kelas)
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Sekolah - InkluSkill</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Remix Icon: library ikon berbasis CSS dari CDN -->
  <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet"/>
  <!-- CSS utama dashboard: base_url() menghasilkan path absolut dari root project CI3 -->
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <!-- Chart.js: library grafik JavaScript dari CDN, dipakai untuk pie chart dan bar chart -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<!-- SIDEBAR NAVIGASI                                       -->
<!-- Menu navigasi vertikal di sisi kiri halaman           -->
<div class="sidebar">
  <h2 class="logo">InkluSkill</h2>
  <nav class="menu">
    <!-- Link aktif (class "active") sesuai halaman yang sedang dibuka -->
    <a class="active"><i class="ri-dashboard-line"></i>Dashboard</a>
    <!-- base_url('siswa') mengarahkan ke controller Siswa::index() -->
    <a href="<?= base_url('siswa') ?>"><i class="ri-user-3-line"></i>Data Siswa</a>
    <a href="#"><i class="ri-file-edit-line"></i>Pendaftaran Pelatihan</a>
    <a href="#"><i class="ri-bar-chart-line"></i>Laporan</a>
    <a href="#"><i class="ri-archive-line"></i>Rekap Siswa</a>
    <a href="#"><i class="ri-user-settings-line"></i>Profil</a>
  </nav>
  <!-- Tombol logout: redirect ke Auth::logout() yang akan menghancurkan session -->
  <button class="logout" onclick="window.location.href='<?= base_url('auth/logout') ?>'">
    <i class="ri-logout-box-line"></i> Logout
  </button>
</div>

<div class="content">
  <!-- Topbar: judul halaman + info user yang sedang login -->
  <div class="topbar">
    <h1>Dashboard Sekolah</h1>
    <div class="user-info">
      <!-- $namaSekolah dikirim dari controller (sudah di-htmlspecialchars di controller) -->
      <span><?= $namaSekolah ?></span>
      <img src="<?= base_url('assets/img/fotonovi.webp') ?>">
    </div>
  </div>

  <div class="dashboard-body">
    <div class="header">
      <h1>Ringkasan Sekolah</h1>
      <p>Pantau data siswa dan perkembangan pelatihan</p>
    </div>

    <!-- Setiap kartu menampilkan satu angka dari Dashboard_model::getStatistik() -->
    <!-- Nilai sudah diproses di controller sebelum dikirim ke view -->
    <div class="stat-row">
      <div class="stat-card purple">
        <i class="ri-team-line"></i>
        <!-- $totalSiswa: jumlah semua baris di tabel siswa -->
        <div><p>Total Siswa</p><h2><?= $totalSiswa ?></h2></div>
      </div>
      <div class="stat-card green">
        <i class="ri-user-follow-line"></i>
        <!-- $siswaAktif: jumlah siswa WHERE status = 'Aktif' -->
        <div><p>Siswa Aktif</p><h2><?= $siswaAktif ?></h2></div>
      </div>
      <div class="stat-card orange">
        <i class="ri-calendar-event-line"></i>
        <!-- $pelatihanAktif: jumlah pelatihan WHERE status = 'Aktif' -->
        <div><p>Pelatihan Aktif</p><h2><?= $pelatihanAktif ?></h2></div>
      </div>
      <div class="stat-card red">
        <i class="ri-file-warning-line"></i>
        <!-- $laporanMasuk: jumlah laporan WHERE status = 'Baru' -->
        <div><p>Laporan Masuk</p><h2><?= $laporanMasuk ?></h2></div>
      </div>
    </div>

    <!-- GRAFIK CHART.JS                                        -->
    <!-- Canvas elemen diisi oleh Chart.js di blok <script> bawah -->
    <div class="chart-row">
      <div class="card">
        <h3>Status Siswa</h3>
        <!-- Canvas untuk grafik Pie: distribusi siswa Aktif vs Tidak Aktif -->
        <canvas id="statusChart"></canvas>
      </div>
      <div class="card">
        <h3>Siswa per Kelas</h3>
        <!-- Canvas untuk grafik Bar: jumlah siswa per kelas (X, XI, XII) -->
        <canvas id="progressChart"></canvas>
      </div>
    </div>

    <!-- Feed aktivitas terbaru, diisi oleh JavaScript dari data $aktivitasJson -->
    <div class="card">
      <h3>Siswa dan Laporan Terbaru Ditambahkan</h3>
      <div id="activityList" class="activity-list"></div>
    </div>
  </div>
</div>

<!-- Tombol floating chatbot: mengarahkan ke Chatbot::index() -->
<a href="<?= base_url('chatbot') ?>" class="chatbot-float">
  <i class="ri-chat-3-line"></i>
</a>

<script>
  // Decode JSON string dari controller menjadi objek/array JavaScript.
  // $aktivitasJson dan $dataChartJson sudah di-json_encode() di Dashboard Controller.
  // Ini adalah cara standar CI3 untuk mengirim data PHP ke JavaScript.
  const aktivitas = <?= $aktivitasJson ?>;
  const dataChart = <?= $dataChartJson ?>;

  // Render feed aktivitas: loop setiap item dari $aktivitasJson
  // dan buat elemen HTML secara dinamis dengan template literal
  const activityList = document.getElementById("activityList");
  aktivitas.forEach(a => {
    activityList.innerHTML += `
      <div class="activity-item">
        <div class="activity-icon"><i class="${a.icon}"></i></div>
        <div><p>${a.text}</p><span class="activity-meta">${a.time}</span></div>
      </div>`;
  });

  // Grafik Pie: distribusi status siswa (Aktif vs Tidak Aktif)
  // Data diambil dari dataChart.status yang dikirim Dashboard_model::getChartData()
  new Chart(document.getElementById('statusChart'), {
    type: "pie",
    data: {
      labels: dataChart.status.labels,   // ['Aktif', 'Tidak Aktif']
      datasets: [{ data: dataChart.status.data, backgroundColor: ["#1BC47D", "#FF5B5B"] }]
    }
  });

  // Grafik Bar: distribusi jumlah siswa per kelas (X, XI, XII)
  // Data diambil dari dataChart.progress yang dikirim Dashboard_model::getChartData()
  new Chart(document.getElementById('progressChart'), {
    type: "bar",
    data: {
      labels: dataChart.progress.labels,  // ['Kelas X', 'Kelas XI', 'Kelas XII']
      datasets: [{ data: dataChart.progress.data, backgroundColor: "#6C63FF" }]
    }
  });
</script>
</body>
</html>
