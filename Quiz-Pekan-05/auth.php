<?php
session_start();
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');
$remember = isset($_POST['remember']);

if ($username === '' || $password === '') {
    header('Location: login.php?error=' . urlencode('Username dan password wajib diisi.'));
    exit;
}

$sql = "SELECT id_user, username, password FROM quiz5 WHERE username = ? AND password = ? LIMIT 1";
$stmt = mysqli_prepare($koneksi, $sql);

if (!$stmt) {
    header('Location: login.php?error=' . urlencode('Query login gagal dijalankan.'));
    exit;
}

mysqli_stmt_bind_param($stmt, 'ss', $username, $password);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = $result ? mysqli_fetch_assoc($result) : null;
mysqli_stmt_close($stmt);
mysqli_close($koneksi);

if ($user) {
    $_SESSION['username'] = $username;
    $_SESSION['id_user'] = $user['id_user'];
    $_SESSION['login_status'] = 'Sudah login menggunakan session.';

    if ($remember) {
        setcookie('remember_username', $username, time() + (60 * 60 * 24 * 7), '/');
    } else {
        setcookie('remember_username', '', time() - 3600, '/');
    }

    header('Location: dashboard.php');
    exit;
}

header('Location: login.php?error=' . urlencode('Username atau password salah.'));
exit;
