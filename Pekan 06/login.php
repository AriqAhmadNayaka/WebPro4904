<?php
session_start();
require_once 'User.php'; // Muat kelas User (yang sudah muat Database.php di dalamnya)

// Ambil koneksi database menggunakan Singleton Database
$conn = Database::getInstance()->getConnection();

// Buat objek User dengan menyuntikkan koneksi (Dependency Injection)
$userObj = new User($conn);

// Proses login jika tombol ditekan
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Panggil method login() dari objek User
    if ($userObj->login($username, $password)) {
        header("Location: index.php");
        exit;
    } else {
        $error = true;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Masuk</h2>

    <form method="post">
        <?php if (isset($error)) echo "<p>Login Gagal!</p>"; ?>
        <input type="text"     name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="login">Login</button>
        <p>Belum punya akun? <a href="register.php">Daftar</a></p>
    </form>
</body>
</html>
