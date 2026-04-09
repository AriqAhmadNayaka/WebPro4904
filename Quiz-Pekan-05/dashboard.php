<?php
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

$username = $_SESSION['username'];
$loginStatus = $_SESSION['login_status'] ?? 'Session aktif.';
$rememberedUsername = $_COOKIE['remember_username'] ?? 'Belum ada cookie tersimpan.';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
    <div class="dashboard-card">
        <h1>Halo, <?= htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?>!</h1>
        <p>Anda berhasil login ke aplikasi sederhana berbasis PHP. Status login dipertahankan dengan session selama browser masih aktif.</p>

        <div class="info-grid">
            <div class="info-box">
                <h2>Status Session</h2>
                <strong><?= htmlspecialchars($loginStatus, ENT_QUOTES, 'UTF-8'); ?></strong>
            </div>
            <div class="info-box">
                <h2>Username dari Cookie</h2>
                <strong><?= htmlspecialchars($rememberedUsername, ENT_QUOTES, 'UTF-8'); ?></strong>
            </div>
        </div>

        <div class="actions">
            <a href="login.php" class="secondary-link">Kembali ke Login</a>
            <a href="logout.php" class="primary-link">Logout</a>
            <a href="logout.php?clear_cookie=1" class="secondary-link">Logout + Hapus Cookie</a>
        </div>
    </div>
</body>
</html>
