<?php
// Memulai session agar bisa mengecek apakah user sudah login.
session_start();
// Memuat file koneksi database.
include 'koneksi.php';

// Jika user sudah login, langsung arahkan ke dashboard.
if (isset($_SESSION['username'])) {
    header('Location: dashboard.php');
    exit;
}

// Mengambil username yang tersimpan di cookie jika ada.
$rememberedUsername = $_COOKIE['remember_username'] ?? '';
// Mengambil pesan error dari URL jika login sebelumnya gagal.
$errorMessage = $_GET['error'] ?? '';
// Nilai default untuk contoh akun yang akan ditampilkan di halaman login.
$dbUsernameValue = 'Belum ada data';
$dbPasswordValue = 'Belum ada data';

// Mengambil satu akun pertama dari database sebagai contoh login.
$sql = "SELECT username, password FROM quiz5 ORDER BY id_user ASC LIMIT 1";
$result = mysqli_query($koneksi, $sql);

// Jika data ditemukan, simpan username dan password ke variabel tampilan.
if ($result && mysqli_num_rows($result) > 0) {
    $akun = mysqli_fetch_assoc($result);
    $dbUsernameValue = $akun['username'] ?? 'Belum ada data';
    $dbPasswordValue = $akun['password'] ?? 'Belum ada data';
}

// Menutup koneksi database karena sudah tidak dipakai lagi.
mysqli_close($koneksi);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="login.css" />
</head>
<body>
    <div class="login-card">
        <!-- Bagian judul dan deskripsi halaman login. -->
        <div class="brand">
            <h1>Sign in</h1>
            <p>Masuk ke akun Anda untuk melanjutkan aktivitas pada sistem.</p>
        </div>

        <!-- Menampilkan contoh akun yang tersedia di database. -->
        <div class="demo-box">
            <strong>Akun Yang sudah ada di database : </strong><br>
            Username: <strong><?= htmlspecialchars($dbUsernameValue, ENT_QUOTES, 'UTF-8'); ?></strong><br>
            Password: <strong><?= htmlspecialchars($dbPasswordValue, ENT_QUOTES, 'UTF-8'); ?></strong>
            <br>
            <br>
            (Username dan Password ditampilkan disini hanya contoh untuk tugas pekan 5)
        </div>

        <!-- Pesan error hanya muncul jika ada parameter error di URL. -->
        <?php if ($errorMessage !== '') : ?>
            <div class="error-message">
                <?= htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endif; ?>

        <!-- Form utama untuk mengirim data login ke auth.php. -->
        <form action="auth.php" method="post">
            <div class="input-group">
                <label for="username">Username</label>
                <input 
                    type="text"
                    id="username"
                    name="username"
                    placeholder="Masukkan username"
                    value="<?= htmlspecialchars($rememberedUsername, ENT_QUOTES, 'UTF-8'); ?>"
                    required
                >
            </div>

            <div class="input-group">
                <label for="password">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Masukkan password"
                    required
                >
            </div>

            <!-- Checkbox untuk mengaktifkan penyimpanan username di cookie. -->
            <div class="checkbox-group">
                <input type="checkbox" id="remember" name="remember" value="1">
                <label for="remember">Ingat username dengan cookie</label>
            </div>

            <button type="submit">Login</button>
        </form>

        <!-- Penjelasan singkat fungsi session dan cookie pada halaman ini. -->
        <p class="footer-note">
            Session dipakai untuk menyimpan status login, sedangkan cookie dipakai untuk mengingat username.
        </p>
    </div>
</body>
</html>
