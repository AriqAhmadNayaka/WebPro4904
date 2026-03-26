<?php

echo '

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Register</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<link rel="stylesheet" href="style.css">// Ganti dengan path ke file CSS agar cssnya bisa ditampilin disini

</head>

<body>

<div class="auth-wrapper">

<div class="auth-container active">//containner 

<div class="auth-header">//header

<div class="logo-container">//logo container 
<div class="logo-icon">InS</div>//logo icon untuk logo inluskill
</div>

<h2 class="auth-title">Inkluskill</h2>// initu judul untuk halaman register
<p class="auth-subtitle">Bergabunglah dengan InkluSkill hari ini</p>// itu subjudul untuk halaman register

</div>

<form method="POST" action="proses_register.php" class="auth-form">// form untuk input data register

<div class="form-group">// form group untuk nama lengkap

<label>Nama Lengkap</label>//input untuk nama lengkapnya

<input type="text" name="nama" required>//type teks untuk nama lengkap

</div>

<div class="form-group">//form untu email

<label>Email</label>//label emailnya

<input type="email" name="email" required>//type email untuk input email

</div>

<div class="form-group">//form untuk password

<label>Password</label>//label untuk passwordnya

<input type="password" name="password" required>//type paswornya

</div>

<div class="form-group">//button untuk submit 

<label>Tipe Pengguna</label>//label untuk tipe pengguna karna ada 3 yaitu ada orhtua, sekolah,dinas

<select name="role" required>//select untuk memilih tipe pengguna

<option value="">Pilih tipe pengguna</option>//option untuk memilih tipe pengguna di bawah
<option value="sekolah">Sekolah</option>
<option value="orangtua">Orang Tua</option>
<option value="dinas">Dinas/BLK</option>
<option value="admin">Admin Sistem</option>

</select>

</div>

<button class="auth-btn" type="submit">
Daftar
</button>

</form>

<p class="auth-switch">

Sudah punya akun?//pertanyaan untuk opsi jika tidak punya akun, bisa langsung klik link untuk masuk
<a href="login.php">Masuk di sini</a>//link untuk masuk ke halaman login

</p>

</div>

</div>

</body>

</html>

';

?>