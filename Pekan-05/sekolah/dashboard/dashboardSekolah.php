<?php
// Cek apakah user sudah login, session_start() wajib dipanggil paling atas
session_start();

// Jika belum login, redirect ke halaman auth menggunakan header() di sisi server
if (!isset($_SESSION['current_user'])) {
    header('Location: ../../auth.php');
    exit;
}

// require koneksi.php agar $conn tersedia, koneksi database adalah komponen wajib
require '../../koneksi.php';

// READ: Ambil statistik dari database menggunakan SELECT COUNT
// SELECT COUNT(*) menghitung jumlah baris yang memenuhi kondisi WHERE tertentu
// Ini adalah operasi READ sesuai konsep CRUD di modul 4.4.4

// Hitung total semua siswa di tabel siswa
$totalSiswa = $conn->query("SELECT COUNT(*) AS total FROM siswa")->fetch_assoc()['total'];

// Hitung siswa yang statusnya 'Aktif'
$siswaAktif = $conn->query("SELECT COUNT(*) AS total FROM siswa WHERE status = 'Aktif'")->fetch_assoc()['total'];

// Hitung pelatihan yang statusnya 'Aktif' dari tabel pelatihan
$pelatihanAktif = $conn->query("SELECT COUNT(*) AS total FROM pelatihan WHERE status = 'Aktif'")->fetch_assoc()['total'];

// Hitung laporan yang statusnya 'Baru' (belum ditangani) dari tabel laporan
$laporanMasuk = $conn->query("SELECT COUNT(*) AS total FROM laporan WHERE status = 'Baru'")->fetch_assoc()['total'];

// Ambil nama sekolah dari session — data ini tersimpan saat login
$namaSekolah = htmlspecialchars($_SESSION['current_user']['name'] ?? 'Sekolah');

// READ: Ambil aktivitas terbaru dari 3 tabel sekaligus
// Masing-masing query mengambil data terbaru dari siswa, pelatihan, dan laporan
// fetch_assoc() mengambil hasil query baris per baris sebagai array asosiatif
// Sesuai modul 4.4.5: akses data menggunakan ->fetch_assoc()
$aktivitas = [];

// Ambil 2 siswa terbaru ditambahkan
$resSiswa = $conn->query("SELECT nama, kelas, status FROM siswa ORDER BY id DESC LIMIT 2");
while ($row = $resSiswa->fetch_assoc()) {
    $aktivitas[] = [
        'icon' => 'ri-user-line',
        'text' => 'Siswa baru: ' . $row['nama'] . ' — Kelas ' . $row['kelas'],
        'time' => 'Status: ' . $row['status'],
    ];
}

// Ambil 2 pelatihan terbaru
$resPelatihan = $conn->query("SELECT nama_program, instruktur, status FROM pelatihan ORDER BY id DESC LIMIT 2");
while ($row = $resPelatihan->fetch_assoc()) {
    $aktivitas[] = [
        'icon' => 'ri-book-open-line',
        'text' => 'Pelatihan: ' . $row['nama_program'],
        'time' => 'Instruktur: ' . $row['instruktur'] . ' — ' . $row['status'],
    ];
}

// Ambil 1 laporan terbaru yang berstatus 'Baru'
$resLaporan = $conn->query("SELECT nama_siswa, judul, tanggal FROM laporan WHERE status = 'Baru' ORDER BY id DESC LIMIT 1");
while ($row = $resLaporan->fetch_assoc()) {
    $aktivitas[] = [
        'icon' => 'ri-file-warning-line',
        'text' => 'Laporan baru: ' . $row['judul'],
        'time' => 'Siswa: ' . $row['nama_siswa'] . ' — ' . $row['tanggal'],
    ];
}

// READ: Hitung data untuk chart status siswa
$aktifCount    = (int) $conn->query("SELECT COUNT(*) AS total FROM siswa WHERE status = 'Aktif'")->fetch_assoc()['total'];
$tidakAktifCount = (int) $conn->query("SELECT COUNT(*) AS total FROM siswa WHERE status = 'Tidak Aktif'")->fetch_assoc()['total'];

// READ: Hitung data untuk chart jumlah siswa per kelas
$kelasX   = (int) $conn->query("SELECT COUNT(*) AS total FROM siswa WHERE kelas = 'X'")->fetch_assoc()['total'];
$kelasXI  = (int) $conn->query("SELECT COUNT(*) AS total FROM siswa WHERE kelas = 'XI'")->fetch_assoc()['total'];
$kelasXII = (int) $conn->query("SELECT COUNT(*) AS total FROM siswa WHERE kelas = 'XII'")->fetch_assoc()['total'];

// Tutup koneksi setelah semua data berhasil diambil
$conn->close();

// json_encode() mengubah array PHP menjadi format JSON agar bisa dibaca oleh Chart.js di browser
$aktivitasJson = json_encode($aktivitas);
$dataChartJson = json_encode([
    'status'   => ['labels' => ['Aktif', 'Tidak Aktif'], 'data' => [$aktifCount, $tidakAktifCount]],
    'progress' => ['labels' => ['Kelas X', 'Kelas XI', 'Kelas XII'], 'data' => [$kelasX, $kelasXI, $kelasXII]],
]);
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
    <a href="#"><i class="ri-file-edit-line"></i>Pendaftaran Pelatihan</a>
    <a href="#"><i class="ri-bar-chart-line"></i>Laporan</a>
    <a href="#"><i class="ri-archive-line"></i>Rekap Siswa</a>
    <a href="#"><i class="ri-user-settings-line"></i>Profil</a>
  </nav>

  <!-- Tombol logout pakai <button> bukan <a> agar styling CSS class .logout terapply dengan benar -->
  <button class="logout" onclick="window.location.href='../../auth.php?logout=1'">
    <i class="ri-logout-box-line"></i> Logout
  </button>
</div>

<!-- CONTENT -->
<div class="content">

  <!-- TOPBAR -->
  <div class="topbar">
    <h1>Dashboard Sekolah</h1>
    <div class="user-info">
      <!-- Nama sekolah diambil dari session yang tersimpan saat login -->
      <span><?= $namaSekolah ?></span>
      <img src="../../assets/fotonovi.webp">
    </div>
  </div>

  <div class="dashboard-body">

    <div class="header">
      <h1>Ringkasan Sekolah</h1>
      <p>Pantau data siswa dan perkembangan pelatihan</p>
    </div>

    <!-- Stat cards, nilai di-echo dari hasil SELECT COUNT di database, murni READ -->
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
        <h3>Siswa per Kelas</h3>
        <canvas id="progressChart"></canvas>
      </div>
    </div>

    <!-- Activity list, diisi oleh JavaScript dari data JSON yang berasal dari database -->
    <div class="card">
      <h3>Siswa dan Laporan Terbaru Ditambahkan</h3>
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

  // Render daftar aktivitas terbaru dari data yang diambil lewat SELECT database
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

  // Render pie chart status siswa dan bar chart jumlah siswa per kelas
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