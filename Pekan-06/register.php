<?php

declare(strict_types=1);

// Memuat autoloader class OOP aplikasi.
require_once __DIR__ . "/app/bootstrap.php";

use App\Config\Database;
use App\Repositories\UserRepository;
use App\Services\AuthService;
use App\Services\RememberMeService;
use App\Support\Response;
use App\Support\SessionManager;

// Menyiapkan session manager untuk kebutuhan halaman register.
$session = new SessionManager();
// Memastikan session aktif.
$session->start();
// Menyiapkan repository untuk akses data user.
$userRepository = new UserRepository(Database::getConnection());
// Menyiapkan service auth yang menangani proses register.
$authService = new AuthService($session, $userRepository, new RememberMeService($userRepository));

// Variabel pesan dipakai untuk menampilkan error ke pengguna.
$pesan = "";

// Jalankan proses register hanya saat form dikirim dengan method POST.
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Kirim data form ke service auth.
    [$success, $message] = $authService->register(
        trim((string)($_POST["username"] ?? "")),
        trim((string)($_POST["email"] ?? "")),
        (string)($_POST["password"] ?? ""),
        trim((string)($_POST["role"] ?? ""))
    );

    // Jika register berhasil, arahkan user ke halaman login.
    if ($success) {
        Response::redirect("auth.php?registered=1");
    }

    // Simpan pesan error jika register gagal.
    $pesan = $message;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<!-- agar halaman responsive di mobile -->
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Register | InkluSkill</title>

<!-- memanggil file CSS -->
<link rel="stylesheet" href="styles.css">

</head>

<body class="auth-body">
    <?php
    // Tampilkan notifikasi jika ada pesan dari proses register.
    if ($pesan !== "") {
        echo '<script>alert("' . addslashes($pesan) . '");</script>';
    }
    ?>


<div class="auth-container">

<!-- Judul halaman register -->
<h2 class="auth-title">Buat Akun Baru</h2>


<!-- FORM REGISTER -->
<form method="POST" class="auth-form">


<!-- INPUT NAMA -->
<div class="form-group">

<label>Nama Lengkap</label>

<input type="text" name="username" placeholder="Masukkan nama" required>

</div>


<!-- INPUT EMAIL -->
<div class="form-group">

<label>Email</label>

<input type="email" name="email" placeholder="Masukkan email" required>

</div>


<!-- INPUT PASSWORD -->
<div class="form-group">

<label>Password</label>

<input type="password" name="password" placeholder="Buat password" required>

</div>


<!-- PILIH ROLE -->
<div class="form-group">

<label>Pilih Role</label>

<select name="role">

<option value="">-- Pilih Role --</option>

<option value="sekolah">Sekolah</option>

</select>

</div>


<!-- TOMBOL DAFTAR -->
<button class="btn auth-btn" type="submit">

Daftar

</button>

</form>




<!-- LINK KE HALAMAN LOGIN -->
<p class="auth-switch">

Sudah punya akun?

<a href="auth.php">Masuk</a>

</p>

</div>

</body>
</html>
