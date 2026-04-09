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

// Menyiapkan session manager untuk kebutuhan login.
$session = new SessionManager();
// Memastikan session aktif.
$session->start();
// Menyiapkan repository data user.
$userRepository = new UserRepository(Database::getConnection());
// Menyiapkan service auth yang menangani login dan logout.
$authService = new AuthService($session, $userRepository, new RememberMeService($userRepository));

// Variabel output dipakai untuk menampilkan pesan login.
$output = "";
// Menandai apakah user baru selesai register.
$forceLoginPage = isset($_GET["registered"]);

// Jika datang dari halaman register, bersihkan login lama lalu tampilkan pesan sukses.
if ($forceLoginPage) {
    $authService->logout();
    $session->start();
    $output = '<p style="color:green;">Registrasi berhasil. Silakan login.</p>';
}

// Jika user sudah login, langsung arahkan ke halaman kelola peserta.
if (!$forceLoginPage && $authService->currentUser()) {
    Response::redirect("kelolapeserta.php");
}

// Jika belum dipaksa ke halaman login, coba pulihkan session dari cookie remember me.
if (!$forceLoginPage) {
    $authService->restoreUserFromCookie();
}

// Jika login dari cookie berhasil, arahkan ke halaman peserta.
if (!$forceLoginPage && $authService->currentUser()) {
    Response::redirect("kelolapeserta.php");
}

// Proses login dijalankan saat form dikirim.
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Kirim data form login ke service auth.
    [$success, $message] = $authService->login(
        trim((string)($_POST["email"] ?? "")),
        (string)($_POST["password"] ?? ""),
        trim((string)($_POST["role"] ?? "")),
        isset($_POST["remember_me"])
    );

    // Jika login berhasil, arahkan ke halaman kelola peserta.
    if ($success) {
        Response::redirect("kelolapeserta.php");
    }

    // Simpan pesan error jika login gagal.
    $output = '<p style="color:red;">' . htmlspecialchars($message) . '</p>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<!-- Menentukan charset halaman. -->
<meta charset="UTF-8">
<!-- Membuat halaman responsif di berbagai ukuran layar. -->
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Judul tab browser. -->
<title>Login | InkluSkill</title>
<!-- Memanggil file CSS utama. -->
<link rel="stylesheet" href="styles.css">
</head>
<body class="auth-body">
<div class="auth-container">
<!-- Judul halaman login. -->
<h2 class="auth-title">Masuk ke InkluSkill</h2>

<!-- Form login user. -->
<form method="POST" action="" class="auth-form">
<div class="form-group">
<label>Email</label>
<input type="email" name="email" placeholder="Masukkan email" required>
</div>

<div class="form-group">
<!-- Input password user. -->
<label>Password</label>
<input type="password" name="password" placeholder="Masukkan password" required>
</div>

<div class="form-group">
<!-- Pilihan role untuk login. -->
<label>Pilih Role</label>
<select name="role" required>
<option value="">-- Pilih Role --</option>
<option value="sekolah">Sekolah</option>
</select>
</div>

<div class="form-group">
<label>
<!-- Checkbox untuk remember me. -->
<input type="checkbox" name="remember_me" value="1">
Remember me
</label>
</div>

<!-- Tombol submit login. -->
<button class="btn auth-btn" type="submit">Masuk</button>
</form>

<!-- Menampilkan pesan login dari backend. -->
<?php echo $output; ?>

<p class="auth-switch">
<!-- Link menuju halaman daftar akun. -->
Belum punya akun?
<a href="register.php">Daftar</a>
</p>
</div>
</body>
</html>
