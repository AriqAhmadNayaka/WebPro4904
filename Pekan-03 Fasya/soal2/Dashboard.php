<?php

// validasi sesi
$nama = $_GET['nama'] ?? "User";
if(isset($_POST['keluhan']) && isset($_POST['tanggal'])){

  // Ambil data dari form
  $tanggal = $_POST['tanggal'];
  $keluhan = $_POST['keluhan'];

  $data = $tanggal . "|" . $keluhan . "\n";

  // Simpan data ke file konsultasi.txt (format: tanggal|keluhan)
  file_put_contents("konsultasi.txt", $data, FILE_APPEND);
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Nuids</title>
  <link rel="stylesheet" href="global.css">
  <link rel="stylesheet" href="dashboard.css">
</head>
<body>
  <h2>Halo <?php echo $nama; ?></h2>
  <p>Selamat datang di Nuids Health Tracker</p>
  <div class="card">
    <h3>Tambah Catatan Konsultasi (Create)</h3>

    <form method="POST">
      <label>Tanggal</label>
      <input type="date" name="tanggal" required>
      <label>Keluhan / Catatan Kesehatan</label>
      <textarea name="keluhan" placeholder="Contoh: sakit kepala, demam ringan..." required></textarea>
      <button type="submit">Simpan Catatan</button>
    </form>
  </div>

  <div class="card">
    <h3>Riwayat Catatan Kesehatan (Read)</h3>

<?php

// Tampilkan data konsultasi dari file konsultasi.txt
if(file_exists("konsultasi.txt")){// Cek apakah file konsultasi.txt ada
  $data = file("konsultasi.txt");// Baca file konsultasi.txt dan simpan setiap baris ke dalam array $data
  echo "<ul>";
  foreach($data as $d){
    list($tanggal,$keluhan) = explode("|",$d);// Pisahkan tanggal dan keluhan menggunakan explode() dengan delimiter "|"
    echo "<li><b>$tanggal</b> - $keluhan</li>";// Tampilkan tanggal dan keluhan dalam format list item
  }
  echo "</ul>";
  }else{
  echo "Belum ada catatan kesehatan.";// Tampilkan pesan jika file konsultasi.txt belum ada
  }

?>
  </div>

  <a href="login.php">Logout</a>

<script src="main.js"></script>
</body>
</html>