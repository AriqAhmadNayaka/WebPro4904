<?php
session_start();
//identitas anak yang akan disimpan di session, tapi bisa juga diubah sesuai kebutuhan
$nama = "";
$gender = "";
$tanggal = "";
$kelas = "";
$alamat = "";
$catatan = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){//cek form disubmit

$nama = $_POST['nama'];
$gender = $_POST['gender'];
$tanggal = $_POST['tanggal'];
$kelas = $_POST['kelas'];
$alamat = $_POST['alamat'];
$catatan = $_POST['catatan'];

$_SESSION['anak'] = [//menyimpan data
"nama"=>$nama,
"gender"=>$gender,
"tanggal"=>$tanggal,
"kelas"=>$kelas,
"alamat"=>$alamat,
"catatan"=>$catatan
];

}

if(isset($_SESSION['anak'])){//cek apakah data anak sudah ada di session
$data = $_SESSION['anak'];

$nama = $data['nama'];
$gender = $data['gender'];
$tanggal = $data['tanggal'];
$kelas = $data['kelas'];
$alamat = $data['alamat'];
$catatan = $data['catatan'];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Data Anak</title>

<link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet"/>
<link rel="stylesheet" href="styles.css">//link untuk css

</head>

<body>

<div class="sidebar">//sidebar buat di kiri

<h2 class="logo">InkluSkill</h2>//logo inluskill

<a href="../dashboard/dashboardOrtu.php"><i class="ri-dashboard-line"></i>Dashboard</a>//link dasboard dengan bisa di klik dengan link di bawah ini
<a href="#" class="active"><i class="ri-user-3-line"></i>Data Anak</a>
<a href="../jadwal/index.php"><i class="ri-calendar-check-line"></i>Jadwal</a>
<a href="../perkembangan/index.php"><i class="ri-bar-chart-line"></i>Status & Perkembangan</a>
<a href="../profil/index.php"><i class="ri-user-settings-line"></i>Profil</a>

<button class="logout"><i class="ri-logout-circle-line"></i>Logout</button>

</div>

<div class="content">//kalo ini untuk di bagian kanannya

<div class="page-header">
<h1><i class="ri-user-3-fill"></i> Data Anak</h1>//judul halaman data anak
<p>Informasi ini membantu sekolah memahami kebutuhan dan perkembangan anak Anda</p>//subjudul halaman data anak
</div>

<div class="profile-box">

<div class="photo-box">
<img src="https://i.ibb.co/YfyqgLw/default-avatar.png">//foto untuk anak
</div>

<div class="big-card">

<h3>Informasi Anak</h3>//judul untuk bagian informasi anak

<form method="POST">

<div class="info-grid">//grid untuk form input data anak

<div class="input-group">
<label>Nama Lengkap</label>//label untuk nama lengkap
<input type="text" name="nama" value="<?php echo $nama ?>">//input untuk nama lengkap
</div>

<div class="input-group">
<label>Jenis Kelamin</label>//label untuk jenis kelamin

<select name="gender">//select untuk jenis kelamin contohnya perempuan atau laki laki
<option value="">Pilih</option>

<option value="Laki-laki" <?php if($gender=="Laki-laki") echo "selected"; ?>>Laki-laki</option>

<option value="Perempuan" <?php if($gender=="Perempuan") echo "selected"; ?>>Perempuan</option>

</select>

</div>

<div class="input-group">
<label>Tanggal Lahir</label>//label untuk tanggal lahir
<input type="date" name="tanggal" value="<?php echo $tanggal ?>">
</div>

<div class="input-group">
<label>Kelas / Program</label>//label untuk kelas atau program yang diikuti anak
<input type="text" name="kelas" value="<?php echo $kelas ?>">
</div>

<div class="input-group full">//input untuk alamat anak
<label>Alamat</label>
<input type="text" name="alamat" value="<?php echo $alamat ?>">
</div>

<div class="input-group full">
<label>Catatan Khusus</label>//label untuk catatan khusus tentang anak, misalnya alergi atau kebutuhan khusus lainnya
<textarea name="catatan"><?php echo $catatan ?></textarea>//nampilin catatannya
</div>

</div>

<button class="save-btn">//button untuk menyimpan data anak
<i class="ri-save-line"></i> Simpan Data
</button>

</form>

</div>

</div>

</div>

</body>
</html>