<!DOCTYPE html>
<html lang="id">
<head>

<!-- Pengaturan karakter -->
<meta charset="UTF-8">

<!-- Responsive agar tampilan menyesuaikan layar -->
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<!-- Judul halaman -->
<title>Register</title>

<!-- Import font dari Google -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<!-- Menghubungkan file CSS -->
<link rel="stylesheet" href="style.css">

</head>

<body>

<!-- NAVBAR (menu navigasi atas) -->
<nav>
    <div class="nav__logo">
        <!-- Logo website -->
        <a href="#" class="logo">Inklu<span>Skill</span></a>
    </div>

    <!-- Menu navigasi -->
    <ul class="nav__links">
        <li><a href="tambah_anak.php">Tambah Anak</a></li>
        <li><a href="tampil_anak.php">Lihat Data</a></li>
    </ul>
</nav>

<main>

<!-- Wrapper utama form -->
<div class="auth-wrapper">

<!-- Container form register -->
<div class="auth-container active">

<!-- Header form -->
<div class="auth-header">

<!-- Icon/logo kecil -->
<div class="logo-container">
<div class="logo-icon">Inkluskill</div>
</div>

<!-- Judul aplikasi -->
<h2 class="auth-title">Inkluskill</h2>

<!-- Subtitle -->
<p class="auth-subtitle">Bergabunglah dengan InkluSkill hari ini</p>

</div>

<!-- FORM REGISTER -->
<form method="POST" action="proses_register.php" class="auth-form">

<!-- Input nama -->
<div class="form-group">
<label>Nama Lengkap</label>
<input type="text" name="nama" required>
</div>

<!-- Input email -->
<div class="form-group">
<label>Email</label>
<input type="email" name="email" required>
</div>

<!-- Input password -->
<div class="form-group">
<label>Password</label>
<input type="password" name="password" required>
</div>

<!-- Pilihan role user -->
<div class="form-group">
<label>Tipe Pengguna</label>

<select name="role" required>
<option value="">Pilih tipe pengguna</option>
<option value="sekolah">Sekolah</option>
<option value="orangtua">Orang Tua</option>
<option value="dinas">Dinas/BLK</option>
<option value="admin">Admin Sistem</option>
</select>

</div>

<!-- Tombol submit -->
<button class="auth-btn" type="submit">
Daftar
</button>

</form>

<!-- Link ke halaman login -->
<p class="auth-switch">
Sudah punya akun?
<a href="login.php">Masuk di sini</a>
</p>

<!-- Link tambahan ke input data anak -->
<p class="auth-switch">
Atau langsung ke <a href="proses_tambah.php">Input Data Anak</a>
</p>

</div>

</div>

</main>

</body>
</html>