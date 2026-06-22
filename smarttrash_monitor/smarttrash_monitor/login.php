<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/koneksi.php';

redirect_if_logged_in();

$error = $_GET['error'] ?? '';
$success = $_GET['success'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $error = 'Email dan password wajib diisi.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Format email tidak valid.';
    } else {
        $stmt = mysqli_prepare($koneksi, 'SELECT id, nama, email, password FROM users WHERE email = ? LIMIT 1');
        mysqli_stmt_bind_param($stmt, 's', $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = $result ? mysqli_fetch_assoc($result) : null;
        mysqli_stmt_close($stmt);

        if (!$user || !password_verify($password, $user['password'])) {
            $error = 'Email atau password salah.';
        } else {
            $_SESSION['user_id'] = (int) $user['id'];
            $_SESSION['user_name'] = $user['nama'];
            $_SESSION['user_email'] = $user['email'];

            header('Location: dashboard.php');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | SmartParking Report</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body class="auth-body">
    <?php include __DIR__ . '/navbar.php'; ?>
    <main class="container auth-layout">
        <section class="hero-card">
            <p class="eyebrow">Sistem Pemantauan Sampah</p>
            <h2>Masuk untuk memantau sampah lebih cepat.</h2>
            <p>Dashboard akan menampilkan total pemantauan, status kapasitas, dan data pemantauan terbaru sesuai user login.</p>
        </section>

        <section class="form-card">
            <h3>Login Akun</h3>

            <?php if ($error !== ''): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <?php if ($success !== ''): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success); ?></div>
            <?php endif; ?>

            <form method="POST" class="form-grid">
                <label>
                    <span>Email</span>
                    <input type="email" name="email" placeholder="operator@email.com" required value="<?= htmlspecialchars($_POST['email'] ?? ''); ?>">
                </label>

                <label>
                    <span>Password</span>
                    <input type="password" name="password" placeholder="Minimal 6 karakter" required>
                </label>

                <button type="submit" class="btn btn-primary">Login</button>
            </form>

            <p class="helper-text">Belum punya akun? <a href="register.php">Daftar di sini</a>.</p>
        </section>
    </main>
    <?php include __DIR__ . '/footer.php'; ?>
</body>
</html>
