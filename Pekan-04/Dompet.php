<?php
include "koneksi.php"; 
//menghubungkan file PHP dengan database MySQL 
// agar bisa menjalankan perintah seperti simpan, ambil, ubah, dan hapus data

// ================= DELETE =================
if(isset($_GET['hapus'])){
    $id = $_GET['hapus'];

    mysqli_query($conn, "DELETE FROM laporan WHERE id=$id");

    echo "<script>
    alert('Data berhasil dihapus'); 
    window.location='dompet.php';
    </script>";
} 
//mengecek apakah ada perintah hapus dari URL, 
//lalu menghapus data di database berdasarkan id yang dipilih, 
//kemudian menampilkan notifikasi berhasil

// ================= EDIT (AMBIL DATA) =================
$editData = null;

if(isset($_GET['edit'])){
    $id = $_GET['edit'];

    $result = mysqli_query($conn, "SELECT * FROM laporan WHERE id=$id");
    $editData = mysqli_fetch_assoc($result);
}
//mengambil data dari database berdasarkan id yang dipilih 
//untuk ditampilkan kembali ke dalam form agar bisa diedit oleh pengguna

// ================= INSERT & UPDATE =================
if(isset($_GET['tanggal'])){

    $tanggal = $_GET['tanggal'];
    $keterangan = $_GET['keterangan'];
    $jenis = $_GET['jenis'];
    $jumlah = $_GET['jumlah'];

    // CEK UPDATE ATAU INSERT
    if(isset($_GET['id']) && $_GET['id'] != ""){

        // UPDATE
        $id = $_GET['id'];

        mysqli_query($conn, "UPDATE laporan SET 
            tanggal='$tanggal',
            keterangan='$keterangan',
            jenis='$jenis',
            jumlah='$jumlah'
            WHERE id=$id");

        echo "<script>
        alert('Data berhasil diupdate'); 
        window.location='dompet.php';
        </script>";
        //memperbarui data yang sudah ada di database berdasarkan id, 
        //menggunakan data terbaru yang diinput dari form

    } else {

        // INSERT
        mysqli_query($conn, "INSERT INTO laporan (tanggal, keterangan, jenis, jumlah)
            VALUES ('$tanggal','$keterangan','$jenis','$jumlah')");

        echo "<script>
        alert('Data berhasil ditambahkan'); 
        window.location='dompet.php';
        </script>";
    }   //menyimpan data baru yang diinput dari form ke dalam database sebagai data laporan baru
}

// ================= TAMPIL DATA =================
$dataLaporan = mysqli_query($conn, "SELECT * FROM laporan ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard Penjualan & Keuangan</title>

  <style>
    :root {
      --bg-body: #f5f7fa;
      --bg-card: #ffffff;
      --text-main: #111827;
      --text-muted: #6b7280;
      --primary: #3b82f6;
      --radius-lg: 16px;
      --radius-md: 10px;
      --shadow-soft: 0 10px 30px rgba(15, 23, 42, 0.12);
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Poppins', sans-serif;
      background: #f6f7fb;
      color: #333;
      min-height: 100vh;
      background-color: #ebfbfa;

      background-image:
        radial-gradient(circle at 10% 100%,
          rgba(5, 12, 87, 0.607),
          rgba(70, 79, 184, 0.281),
          transparent 40%),

        radial-gradient(circle at 100% 10%,
          rgba(60, 252, 242, 0.42),
          rgba(207, 244, 248, 0.756),
          transparent 60%);

      background-repeat: no-repeat;
      background-attachment: fixed;
    }

    .header {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(20px);
      padding: 20px 40px;
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
      border-radius: 20px;
      display: flex;
      align-items: center;
      justify-content: center;
      max-width: 1200px;
      margin: 20px auto 40px;
      position: relative;
      border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .back-button {
      position: absolute;
      left: 40px;
      top: 50%;
      transform: translateY(-50%);
      display: flex;
      align-items: center;
      gap: 10px;
      background: linear-gradient(90deg, #8D6DFD, #3a71ff);
      color: white;
      border: none;
      padding: 12px 28px;
      border-radius: 999px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      text-decoration: none;
      box-shadow: 0 4px 15px rgba(141, 109, 253, 0.3);
      font-family: 'Poppins', sans-serif;
      white-space: nowrap;
    }

    .back-button:hover {
      background: linear-gradient(90deg, #7b54f2, #2d5de8);
      transform: translateY(-50%) translateX(-2px);
      box-shadow: 0 8px 25px rgba(141, 109, 253, 0.4);
    }

    .back-button i {
      font-size: 18px;
    }

    .page-title {
      font-size: 28px;
      font-weight: 700;
      background: linear-gradient(90deg, #8D6DFD, #3a71ff);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      margin: 0;
      font-family: 'Poppins', sans-serif;
    }

    .side-floating-nav {
      position: fixed;
      top: 100px;
      left: 25px;
      z-index: 99;
    }

    .side-rail {
      width: 50px;
      height: 460px;
      padding: 9px;
      border-radius: 40px;
      background: var(--glass-bg);
      backdrop-filter: blur(20px);
      box-shadow: var(--shadow);
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 5px;
    }

    .side-top {
      margin-bottom: 20px;
      gap: 5px;
    }

    .side-bottom {
      margin-top: 20px;
      gap: 5px;
    }

    .icon-pill {
      width: 44px;
      height: 44px;
      border-radius: 50%;
      display: grid;
      place-items: center;
      background: transparent;
      border: none;
      cursor: pointer;
      transition: var(--transition);
      font-size: 1.1rem;
    }

    .icon-pill:hover,
    .icon-pill.active {
      background: transparent;
      transform: translateY(-2px);
      box-shadow: 0 8px 20px rgba(141, 109, 253, 0.3);
    }

    .icon-image {
      width: 20px;
      height: 20px;
      object-fit: contain;
      object-position: center;
      display: block;
      background: transparent;
      border: none;
      cursor: pointer;
      transition: var(--transition);
    }

    .right-area {
      display: flex;
      flex-direction: column;
      min-width: 0;
      min-height: 100vh;
    }

    .main {
      flex: 1;
      padding: 18px 22px 26px;
      max-width: 1200px;
      margin: 0 auto;
    }

    .row {
      display: flex;
      flex-wrap: wrap;
      gap: 16px;
      align-items: stretch;
    }

    .col-3 {
      flex: 0 0 calc(25% - 12px);
      min-width: 170px;
    }

    .col-4 {
      flex: 0 0 calc(33.33% - 12px);
      min-width: 230px;
    }

    .col-8 {
      flex: 1 1 0;
      min-width: 280px;
    }

    .col-12 {
      flex: 0 0 100%;
    }

    .card-stats {
      padding: 13px 14px 11px;
      border-radius: var(--radius-md);
      color: #ffffff;
      box-shadow: var(--shadow-soft);
      position: relative;
      overflow: hidden;
    }

    .card-stats small.label {
      font-size: 11px;
      text-transform: uppercase;
      letter-spacing: .06em;
      opacity: .85;
    }

    .card-stats .value {
      margin-top: 6px;
      font-size: 18px;
      font-weight: 600;
    }

    .card-stats .meta {
      font-size: 11px;
      margin-top: 2px;
      opacity: .8;
    }

    .card-red {
      background: #dc2626;
    }

    .card-blue {
      background: #2563eb;
    }

    .card-yellow {
      background: #facc15;
      color: #111827;
    }

    .card-green {
      background: #16a34a;
    }

    .card-purple {
      background: #7c3aed;
    }

    .card-teal {
      background: #0f766e;
    }

    .card-orange {
      background: #ea580c;
    }

    .card-maroon {
      background: #b91c1c;
    }

    .card,
    .table-card,
    .form-card {
      background: var(--bg-card);
      border-radius: var(--radius-lg);
      padding: 18px 16px 14px;
      box-shadow: var(--shadow-soft);
    }

    .card {
      padding: 16px 16px 14px;
    }

    .card+.card {
      margin-top: 16px;
    }

    .card-header {
      display: flex;
      justify-content: space-between;
      align-items: baseline;
      margin-bottom: 10px;
    }

    .card-title {
      font-size: 15px;
      font-weight: 600;
    }

    .card-subtitle {
      font-size: 12px;
      color: var(--text-muted);
    }

    table {
      width: 100%;
      border-collapse: collapse;
      font-size: 13px;
    }

    thead {
      background: #eff6ff;
    }

    th,
    td {
      padding: 8px 10px;
      border-bottom: 1px solid #e5e7eb;
      text-align: left;
      white-space: nowrap;
    }

    tbody tr:nth-child(even) {
      background: #f9fafb;
    }

    tbody tr:hover {
      background: #eef2ff;
    }

    .produk-terlaris {
      border-radius: var(--radius-lg);
      padding: 14px 14px 12px;
      color: #111827;
      background: linear-gradient(135deg, #fbbf24, #fb923c);
      box-shadow: var(--shadow-soft);
    }

    .produk-terlaris .title {
      font-size: 13px;
      text-transform: uppercase;
      letter-spacing: .08em;
      opacity: .85;
    }

    .produk-terlaris .name {
      font-size: 18px;
      font-weight: 700;
      margin-top: 4px;
    }

    .produk-terlaris small {
      font-size: 12px;
    }

    .form-card {
      width: 330px;
      max-width: 100%;
    }

    .form-card h3 {
      margin-bottom: 10px;
      font-size: 16px;
    }

    .form-group {
      margin-bottom: 10px;
    }

    .form-group label {
      display: block;
      font-size: 13px;
      margin-bottom: 4px;
      color: var(--text-muted);
    }

    .form-card input,
    .form-card select {
      width: 100%;
      padding: 8px 9px;
      border-radius: 8px;
      border: 1px solid #d1d5db;
      font-size: 13px;
      outline: none;
    }

    .btn-group {
      display: flex;
      gap: 8px;
      margin-top: 8px;
    }

    .btn {
      padding: 8px 12px;
      border-radius: 999px;
      border: none;
      font-size: 13px;
      font-weight: 500;
      box-shadow: 0 8px 18px rgba(15, 23, 42, .25);
      background: #e5e7eb;
      color: #6b7280;
      cursor: pointer;
    }

    .btn-edit {
      background-color: #3a71ff;
      color: white;
      padding: 6px 12px;
      border-radius: 8px;
      text-decoration: none;
      font-size: 13px;
      margin-right: 5px;
      transition: 0.3s;
    }

    .btn-edit:hover {
     background-color: #2d5de8;
    }

    .btn-delete {
      background-color: #dc2626;
      color: white;
      padding: 6px 12px;
      border-radius: 8px;
      text-decoration: none;
      font-size: 13px;
      transition: 0.3s;
    }

    .btn-delete:hover {
      background-color: #b91c1c;
    }

    .table-wrapper {
      display: flex;
      gap: 16px;
    }

    .table-wrapper .card {
      flex: 1;
    }

    @media (max-width: 960px) {
      .header-nav {
        padding-inline: 14px;
      }

      .main {
        padding-inline: 14px;
      }

      .nav-container {
        flex-direction: column;
        align-items: flex-start;
        gap: 6px;
      }

      .table-wrapper {
        flex-direction: column;
      }
    }

    @media (max-width: 640px) {
      .row {
        flex-direction: column;
      }

      .col-3,
      .col-4,
      .col-8 {
        flex: 0 0 100%;
      }

      th,
      td {
        font-size: 12px;
      }
    }

    .grafik-harian-wrapper {
      width: 100%;
      height: 180px;
      overflow: hidden;
    }

    .grafik-harian-img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: block;
    }
  </style>
</head>

<body>
  <header class="header">
    <a href="tampilanutama.html" class="back-button">
      Kembali ke Halaman Utama
    </a>
    <h1 class="page-title">Laporan Keuangan</h1>
  </header>

  <nav class="side-floating-nav" aria-label="Navigasi Utama">
    <div class="side-rail">
      <div class="side-top">
        <a href="konsultasiai.html">
          <button class="icon-pill" aria-label="AI Assistant">
            <img class="icon-image" src="chat-bot-removebg-preview (1).png" alt="">
          </button>
        </a>
        <a href="komunitas.html">
          <button class="icon-pill" aria-label="Komunitas">
            <img class="icon-image" src="komunitas_icon-removebg-preview.png" alt="">
          </button>
        </a>
      </div>

      <div class="side-icons">
        <a href="petaumkm.html">
          <button class="icon-pill" aria-label="Peta UMKM">
            <img class="icon-image" src="lokasi_icon-removebg-preview.png" alt="">
          </button>
        </a>
        <a href="kategori.html">
          <button class="icon-pill" aria-label="Jenis UMKM">
            <img class="icon-image" src="shop_icon-removebg-preview.png" alt="">
          </button>
        </a>
        <a href="delivery.html">
          <button class="icon-pill" aria-label="Delivery">
            <img class="icon-image" src="fast-delivery_icon-removebg-preview.png" alt="">
          </button>
        </a>
        <a href="pesan.html">
          <button class="icon-pill" aria-label="Pesan">
            <img class="icon-image" src="chat_icon-removebg-preview.png" alt="">
          </button>
        </a>
        <a href="investasi.html">
          <button class="icon-pill" aria-label="Investasi">
            <img class="icon-image" src="invest_icon-removebg-preview.png" alt="">
          </button>
        </a>
      </div>

      <div class="side-bottom">
        <a href="pengaturan.html">
          <button class="icon-pill" aria-label="Pengaturan">
            <img class="icon-image" src="pengaturan_icon-removebg-preview.png" alt="">
          </button>
        </a>
        <a href="logout.html">
          <button class="icon-pill" aria-label="Logout">
            <img class="icon-image" src="keluar_icon-removebg-preview.png" alt="">
          </button>
        </a>
      </div>
    </div>
  </nav>

  <main class="main">
    <div class="row" style="margin-bottom: 16px">
      <div class="col-3">
        <div class="card-stats card-red">
          <small class="label">Total penjualan hari ini</small>
          <div class="value">Rp 3.000.000</div>
          <div class="meta">Update dari transaksi hari ini</div>
        </div>
      </div>

      <div class="col-3">
        <div class="card-stats card-blue">
          <small class="label">Penjualan bulan ini</small>
          <div class="value">Rp 120.000.000</div>
          <div class="meta">Periode berjalan</div>
        </div>
      </div>

      <div class="col-3">
        <div class="card-stats card-yellow">
          <small class="label">Produk terjual</small>
          <div class="value">150 Produk</div>
          <div class="meta">Akumulasi bulan ini</div>
        </div>
      </div>

      <div class="col-3">
        <div class="card-stats card-green">
          <small class="label">Produk terlaris</small>
          <div class="value">Kopi Arabika</div>
          <div class="meta">Paling laku bulan ini</div>
        </div>
      </div>

      <div class="col-3">
        <div class="card-stats card-purple">
          <small class="label">Saldo akhir</small>
          <div class="value" id="saldoAkhir">Rp 0</div>
          <div class="meta">Saldo akhir saat ini</div>
        </div>
      </div>

      <div class="col-3">
        <div class="card-stats card-teal">
          <small class="label">Total pemasukan</small>
          <div class="value" id="totalMasuk">Rp 0</div>
          <div class="meta">Semua pemasukan tercatat</div>
        </div>
      </div>

      <div class="col-3">
        <div class="card-stats card-orange">
          <small class="label">Total pengeluaran</small>
          <div class="value" id="totalKeluar">Rp 0</div>
          <div class="meta">Semua pengeluaran tercatat</div>
        </div>
      </div>

      <div class="col-3">
        <div class="card-stats card-maroon">
          <small class="label">Laba / rugi</small>
          <div class="value" id="labaRugi">Rp 0</div>
          <div class="meta">Pemasukan - pengeluaran</div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-8" id="section-penjualan-harian">
        <div class="card">
          <div class="card-header">
            <div class="card-title">Grafik penjualan harian</div>
          </div>
          <div class="grafik-harian-wrapper">
            <img src="penjualan harian.webp" alt="Grafik penjualan harian" class="grafik-harian-img">
          </div>
        </div>

        <div class="card" style="margin-top:16px;">
          <div class="card-header">
            <div class="card-title">Laporan pemasukan & pengeluaran</div>
          </div>
          <table id="tabelKeuangan">
            <thead>
              <tr>
                <th>Tanggal</th>
                <th>Keterangan</th>
                <th>Jenis</th>
                <th>Jumlah</th>
              </tr>
            </thead>

            <tbody>
              <tr>
                <td>2024-09-01</td>
                <td>Penjualan Kopi</td>
                <td>Pemasukan</td>
                <td>1500000</td>
              </tr>

              <tr>
                <td>2024-09-01</td>
                <td>Pembelian Bahan</td>
                <td>Pengeluaran</td>
                <td>500000</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <div class="col-4" id="section-penjualan-bulanan">
        <div class="card">
          <div class="card-header">
            <div class="card-title">Penjualan bulanan</div>
          </div>
          <div style="display:flex;align-items:center;justify-content:center;">
            <img src="penjualan bulanan.png" alt="Grafik penjualan bulanan"
              style="max-height:100%;max-width:100%;object-fit:contain;">
          </div>
        </div>

        <div class="produk-terlaris" style="margin-top: 16px">
          <div class="title">Produk terlaris</div>
          <div class="name">Kopi Arabika</div>
          <small>Produk paling banyak terjual bulan ini</small>
        </div>
      </div>
    </div>

    <section id="section-laporan-manual" style="margin-top: 24px; scroll-margin-top: 80px">
      <h2 class="page-title">Laporan manual</h2>
      <p class="page-subtitle" style="margin-bottom: 14px">
        Input transaksi manual dan lihat rekapnya.
      </p>

      <div class="row">
        <div class="form-card">
          <h3>Input data</h3> 

        <form id="formManual" method="GET" action="">
          <!-- form untuk mengirim data ke server menggunakan metode GET 
           agar bisa diproses oleh PHP -->
          <input type="hidden" name="id" value="<?php echo $editData['id'] ?? ''; ?>">
          <!-- menyimpan id data secara tersembunyi untuk membedakan apakah data akan diupdate atau ditambah -->
          <div class="form-group">
            <label for="tgl">Tanggal</label>
            <input type="date" name="tanggal" value="<?php echo $editData['tanggal'] ?? ''; ?>">
            <!-- input untuk memasukkan tanggal transaksi, dan otomatis terisi saat mode edit -->
          </div>

          <div class="form-group">
            <label for="ket">Keterangan</label>
            <input type="text" name="keterangan" value="<?php echo $editData['keterangan'] ?? ''; ?>">
            <!-- input untuk menuliskan deskripsi atau keterangan transaksi -->
          </div>

          <div class="form-group">
            <label for="jenis">Jenis</label>
            <select name="jenis">
              <option value="Pemasukan" <?php if(($editData['jenis'] ?? '')=='Pemasukan') echo 'selected'; ?>>Pemasukan</option>
              <option value="Pengeluaran" <?php if(($editData['jenis'] ?? '')=='Pengeluaran') echo 'selected'; ?>>Pengeluaran</option>
              <!-- menentukan opsi yang dipilih otomatis saat edit data -->
          </select>
          </div>

          <div class="form-group">
            <label for="jumlah">Jumlah</label>
            <input type="number" name="jumlah" value="<?php echo $editData['jumlah'] ?? ''; ?>">
            <!-- input untuk memasukkan jumlah uang, hanya menerima angka -->
          </div>

          <div class="btn-group">
            <button type="submit" class="btn">Simpan</button>
            <button type="reset" class="btn">Reset</button>
          </div>
        </form>
        </div>

        <div class="table-card" style="flex:1;">
          <h3 style="margin-bottom: 10px; font-size: 16px">
            Data laporan manual
          </h3>
          <table id="tabelManual">
          <thead>
          <tr>
          <th>Tanggal</th>
          <th>Keterangan</th>
          <th>Jenis</th>
          <th>Jumlah</th>
          <th>Aksi</th>
          </tr>
          </thead>

          <tbody>
          <?php while($row = mysqli_fetch_assoc($dataLaporan)): ?>
            <!-- mengambil data satu per satu dari database lalu diulang sampai semua data tampil -->
          <tr>
              <td><?php echo $row['tanggal']; ?></td>
              <td><?php echo $row['keterangan']; ?></td>
              <td><?php echo $row['jenis']; ?></td>
              <td><?php echo $row['jumlah']; ?></td>
              <!-- menampilkan nilai dari database ke dalam tabel (tanggal, keterangan, jenis, jumlah) -->
              <td>
                <a href="?edit=<?php echo $row['id']; ?>" 
                   class="btn-edit">Edit</a>
                <!-- mengirim id ke URL untuk mengambil data dan menampilkannya ke form agar bisa diedit -->
                <a href="?hapus=<?php echo $row['id']; ?>" 
                   class="btn-delete"
                   onclick="return confirm('Yakin hapus?')">
                   Hapus
                </a> 
                <!-- mengirim id ke URL untuk menghapus data dari database
                 dan menampilkan pop up konfirmasi sebelum benar-benar dihapus -->
              </td>
          </tr>
          <?php endwhile; ?>
          </tbody>
          </table>
        </div>
      </div>
    </section>
  </main>
  </div>

  <script>
    function hitungRekap() {
      let masuk = 0, keluar = 0;
      document.querySelectorAll("#tabelManual tbody tr").forEach(r => { 
        const jenis = r.children[2].innerText;
        const jml = parseInt(r.children[3].innerText) || 0;
        if(jenis === "Pemasukan") masuk += jml;
        else keluar += jml;
      });

      document.getElementById("totalMasuk").innerText = "Rp " + masuk.toLocaleString("id-ID");
      document.getElementById("totalKeluar").innerText = "Rp " + keluar.toLocaleString("id-ID");
      document.getElementById("saldoAkhir").innerText = "Rp " + (masuk - keluar).toLocaleString("id-ID");
      document.getElementById("labaRugi").innerText = "Rp " + (masuk - keluar).toLocaleString("id-ID");
    }

    hitungRekap();
  </script>
</body>

</html>