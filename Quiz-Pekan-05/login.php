<?php
session_start();
include 'koneksi.php';

if (isset($_SESSION['username'])) {
    header('Location: dashboard.php');
    exit;
}

$rememberedUsername = $_COOKIE['remember_username'] ?? '';
$errorMessage = $_GET['error'] ?? '';
$dbUsernameValue = 'Belum ada data';
$dbPasswordValue = 'Belum ada data';

$sql = "SELECT username, password FROM quiz5 ORDER BY id_user ASC LIMIT 1";
$result = mysqli_query($koneksi, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    $akun = mysqli_fetch_assoc($result);
    $dbUsernameValue = $akun['username'] ?? 'Belum ada data';
    $dbPasswordValue = $akun['password'] ?? 'Belum ada data';
}

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
        <div class="brand">
            <h1>Sign in</h1>
            <p>Masuk ke akun Anda untuk melanjutkan aktivitas pada sistem.</p>
        </div>

        <div class="demo-box">
            <strong>Akun Yang sudah ada di database : </strong><br>
            Username: <strong><?= htmlspecialchars($dbUsernameValue, ENT_QUOTES, 'UTF-8'); ?></strong><br>
            Password: <strong><?= htmlspecialchars($dbPasswordValue, ENT_QUOTES, 'UTF-8'); ?></strong>
            <br>
            <br>
            (Username dan Password ditampilkan disini hanya contoh untuk tugas pekan 5)
        </div>

        <?php if ($errorMessage !== '') : ?>
            <div class="error-message">
                <?= htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endif; ?>

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

            <div class="checkbox-group">
                <input type="checkbox" id="remember" name="remember" value="1">
                <label for="remember">Ingat username dengan cookie</label>
            </div>

            <button type="submit">Login</button>
        </form>

        <p class="footer-note">
            Session dipakai untuk menyimpan status login, sedangkan cookie dipakai untuk mengingat username.
        </p>
    </div>
</body>
</html>
