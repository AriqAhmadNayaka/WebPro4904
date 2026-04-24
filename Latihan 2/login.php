<?php
session_start();

// Proses logout via GET
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit;
}

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

require 'koneksi.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Validasi: field tidak boleh kosong
    if (empty($email) || empty($password)) {
        $error = 'Email dan password wajib diisi.';
    } else {
        // Cari user berdasarkan email
        $stmt = $conn->prepare("SELECT id, nama, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($id, $nama, $hash);
        $stmt->fetch();
        $stmt->close();

        if ($id && password_verify($password, $hash)) {
            // Simpan data ke session setelah login berhasil
            $_SESSION['user_id'] = $id;
            $_SESSION['nama']    = $nama;
            $_SESSION['email']   = $email;
            header("Location: dashboard.php");
            exit;
        } else {
            $error = 'Email atau password salah.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - SmartParking</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<?php require 'navbar.php'; ?>

<div class="auth-wrap">
    <div class="card">
        <h2>Login</h2>

        <?php if ($error): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" placeholder="email@contoh.com" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Masukkan password" required>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%">Masuk</button>
        </form>

        <p class="auth-switch">Belum punya akun? <a href="register.php">Daftar di sini</a></p>
    </div>
</div>

<?php require 'footer.php'; ?>
</body>
</html>