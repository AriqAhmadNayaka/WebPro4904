<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/koneksi.php';

redirect_if_logged_in();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($nama === '' || $email === '' || $password === '') {
        $error = 'Semua field wajib diisi.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Format email tidak valid.';
    } elseif (strlen($password) < 6) {
        $error = 'Password minimal 6 karakter.';
    } else {
        $cekStmt = mysqli_prepare($koneksi, 'SELECT id FROM users WHERE email = ? LIMIT 1');
        mysqli_stmt_bind_param($cekStmt, 's', $email);
        mysqli_stmt_execute($cekStmt);
        $cekResult = mysqli_stmt_get_result($cekStmt);
        $emailExists = $cekResult && mysqli_fetch_assoc($cekResult);
        mysqli_stmt_close($cekStmt);

        if ($emailExists) {
            $error = 'Email sudah terdaftar, silakan gunakan email lain.';
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = mysqli_prepare($koneksi, 'INSERT INTO users (nama, email, password) VALUES (?, ?, ?)');
            mysqli_stmt_bind_param($stmt, 'sss', $nama, $email, $hashedPassword);

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);
                header('Location: login.php?success=' . urlencode('Registrasi berhasil, silakan login.'));
                exit;
            }

            mysqli_stmt_close($stmt);
            $error = 'Registrasi gagal disimpan.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | SmartParking Report</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body class="auth-body">
    <?php include __DIR__ . '/navbar.php'; ?>
    <main class="container auth-layout">
        <section class="hero-card accent-card">
            <p class="eyebrow">Registrasi Operator</p>
            <h2>Buat akun baru untuk mulai mencatat parkir liar.</h2>
            <p>Validasi form mengikuti ketentuan tugas: field wajib, email valid, dan password minimal 6 karakter.</p>
        </section>

        <section class="form-card">
            <h3>Form Registrasi</h3>

            <?php if ($error !== ''): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form method="POST" class="form-grid">
                <label>
                    <span>Nama</span>
                    <input type="text" name="nama" required value="<?= htmlspecialchars($_POST['nama'] ?? ''); ?>">
                </label>

                <label>
                    <span>Email</span>
                    <input type="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? ''); ?>">
                </label>

                <label>
                    <span>Password</span>
                    <input type="password" name="password" required>
                </label>

                <button type="submit" class="btn btn-primary">Daftar</button>
            </form>

            <p class="helper-text">Sudah punya akun? <a href="login.php">Login sekarang</a>.</p>
        </section>
    </main>
    <?php include __DIR__ . '/footer.php'; ?>
</body>
</html>
