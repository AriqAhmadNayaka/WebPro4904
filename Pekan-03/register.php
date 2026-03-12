<?php

echo '

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Register</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<link rel="stylesheet" href="style.css">

</head>

<body>

<div class="auth-wrapper">

<div class="auth-container active">

<div class="auth-header">

<div class="logo-container">
<div class="logo-icon">InS</div>
</div>

<h2 class="auth-title">Inkluskill</h2>
<p class="auth-subtitle">Bergabunglah dengan InkluSkill hari ini</p>

</div>

<form method="POST" action="proses_register.php" class="auth-form">

<div class="form-group">

<label>Nama Lengkap</label>

<input type="text" name="nama" required>

</div>

<div class="form-group">

<label>Email</label>

<input type="email" name="email" required>

</div>

<div class="form-group">

<label>Password</label>

<input type="password" name="password" required>

</div>

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

<button class="auth-btn" type="submit">
Daftar
</button>

</form>

<p class="auth-switch">

Sudah punya akun?
<a href="login.php">Masuk di sini</a>

</p>

</div>

</div>

</body>

</html>

';

?>