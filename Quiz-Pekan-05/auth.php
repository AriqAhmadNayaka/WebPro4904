<?php
// Memulai session untuk menyimpan status login pengguna.
session_start();
// Menghubungkan file ini dengan konfigurasi koneksi database.
include 'koneksi.php';

// File auth hanya boleh diproses dari form dengan metode POST.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

// Mengambil input dari form login lalu menghapus spasi berlebih.
$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');
// Mengecek apakah checkbox "remember me" dipilih.
$remember = isset($_POST['remember']);

// Jika username atau password kosong, pengguna dikembalikan ke halaman login.
if ($username === '' || $password === '') {
    header('Location: login.php?error=' . urlencode('Username dan password wajib diisi.'));
    exit;
}

// Menyiapkan query untuk mencari user berdasarkan username dan password.
$sql = "SELECT id_user, username, password FROM quiz5 WHERE username = ? AND password = ? LIMIT 1";
$stmt = mysqli_prepare($koneksi, $sql);

// Jika query gagal disiapkan, tampilkan pesan error ke halaman login.
if (!$stmt) {
    header('Location: login.php?error=' . urlencode('Query login gagal dijalankan.'));
    exit;
}

// Mengikat nilai input ke query lalu menjalankannya.
mysqli_stmt_bind_param($stmt, 'ss', $username, $password);
mysqli_stmt_execute($stmt);

// Mengambil hasil query sebagai data user jika ditemukan.
$result = mysqli_stmt_get_result($stmt);
$user = $result ? mysqli_fetch_assoc($result) : null;

// Menutup statement dan koneksi setelah proses database selesai.
mysqli_stmt_close($stmt);
mysqli_close($koneksi);

// Jika user ditemukan, simpan informasi login ke dalam session.
if ($user) {
    $_SESSION['username'] = $username;
    $_SESSION['id_user'] = $user['id_user'];
    $_SESSION['login_status'] = 'Sudah login menggunakan session.';

    // Cookie dipakai untuk mengingat username selama 7 hari.
    if ($remember) {
        setcookie('remember_username', $username, time() + (60 * 60 * 24 * 7), '/');
    } else {
        // Jika checkbox tidak dipilih, cookie lama dihapus.
        setcookie('remember_username', '', time() - 3600, '/');
    }

    // Setelah login berhasil, arahkan pengguna ke dashboard.
    header('Location: dashboard.php');
    exit;
}

// Jika data tidak cocok, kembali ke halaman login dengan pesan gagal.
header('Location: login.php?error=' . urlencode('Username atau password salah.'));
exit;
