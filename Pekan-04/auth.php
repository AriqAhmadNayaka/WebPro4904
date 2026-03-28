<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<!-- membuat halaman responsive di mobile -->
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Login | InkluSkill</title>

<!-- memanggil file CSS -->
<link rel="stylesheet" href="styles.css">

</head>

<body class="auth-body">

<div class="auth-container">

<!-- judul halaman login -->
<h2 class="auth-title">Masuk ke InkluSkill</h2>


<!-- FORM LOGIN -->
<form method="POST" action="" class="auth-form">

<!-- INPUT EMAIL -->
<div class="form-group">
<label>Email</label>
<input type="email" name="email" placeholder="Masukkan email">
</div>


<!-- INPUT PASSWORD -->
<div class="form-group">
<label>Password</label>
<input type="password" name="password" placeholder="Masukkan password">
</div>


<!-- PILIH ROLE -->
<div class="form-group">
<label>Pilih Role</label>

<select name="role">

<option value="">-- Pilih Role --</option>

<option value="sekolah">Sekolah</option>

</select>

</div>


<!-- TOMBOL LOGIN -->
<button class="btn auth-btn" type="submit">
Masuk
</button>

</form>


<!-- MENAMPILKAN PESAN VALIDASI -->
<?php echo $output; ?>


<!-- LINK KE HALAMAN REGISTER -->
<p class="auth-switch">

Belum punya akun?

<a href="register.php">Daftar</a>

</p>

</div>

</body>
</html>
