<?php
require_once 'User.php'; // Muat kelas User (yang sudah muat Database.php di dalamnya)

// Ambil koneksi database menggunakan Singleton Database
$conn = Database::getInstance()->getConnection();

// Buat objek User dengan menyuntikkan koneksi (Dependency Injection)
$userObj = new User($conn);

// Proses registrasi jika tombol ditekan
if (isset($_POST['register'])) {
    $username        = $_POST['username'];
    $password        = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    // Panggil method register() dari objek User
    $hasil = $userObj->register($username, $password, $confirmPassword);

    if ($hasil === "sukses") {
        // Redirect ke halaman login dengan JavaScript alert
        echo "<script>alert('Registrasi Berhasil! Silakan Login'); window.location='login.php';</script>";
    } else {
        $error = $hasil; // Tampilkan pesan error dari method
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Daftar Akun Baru</h2>
        <form method="post">
            <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

            <input type="text"     name="username"         placeholder="Username"       required>
            <input type="password" name="password"         placeholder="Password"       required>
            <input type="password" name="confirm_password" placeholder="Ulangi Password" required>

            <button type="submit" name="register">DAFTAR SEKARANG</button>
            <p>Sudah punya akun? <a href="login.php">Login di sini</a></p>
        </form>
    </div>
</body>
</html>