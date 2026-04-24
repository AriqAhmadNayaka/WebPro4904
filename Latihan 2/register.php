<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

require 'koneksi.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama     = trim($_POST['nama'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Validasi: field tidak boleh kosong
    if (empty($nama) || empty($email) || empty($password)) {
        $error = 'Semua field wajib diisi.';
    // Validasi: email harus valid
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Format email tidak valid.';
    // Validasi: password minimal 6 karakter
    } elseif (strlen($password) < 6) {
        $error = 'Password minimal 6 karakter.';
    } else {
        // Cek email sudah terdaftar
        $cek = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $cek->bind_param("s", $email);
        $cek->execute();
        $cek->store_result();

        if ($cek->num_rows > 0) {
            $error = 'Email sudah terdaftar.';
        } else {
            // Simpan user baru menggunakan POST
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (nama, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $nama, $email, $hash);
            if ($stmt->execute()) {
                $success = 'Registrasi berhasil! Silakan login.';
            } else {
                $error = 'Registrasi gagal, coba lagi.';
            }
            $stmt->close();
        }
        $cek->close();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Register - SmartParking</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<?php require 'navbar.php'; ?>

<div class="auth-wrap">
    <div class="card">
        <h2>Buat Akun</h2>

        <?php if ($error): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <form method="POST" action="register.php">
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama" value="<?= htmlspecialchars($_POST['nama'] ?? '') ?>" placeholder="Masukkan nama lengkap" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" placeholder="email@contoh.com" required>
            </div>
            <div class="form-group">
                <label>Password <small>(min. 6 karakter)</small></label>
                <input type="password" name="password" placeholder="Buat password" required>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%">Daftar</button>
        </form>

        <p class="auth-switch">Sudah punya akun? <a href="login.php">Login di sini</a></p>
    </div>
</div>

<?php require 'footer.php'; ?>
</body>
</html>