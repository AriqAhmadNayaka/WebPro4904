<?php
session_start();
if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $konfirmasi = $_POST['konfirmasi'];

    if ($password === $konfirmasi) {
        $_SESSION['users'][$username] = $password;
        $success = "Registrasi berhasil! Silakan login.";
    } else {
        $error = "Password tidak sama!";
    }
}
?>
<!DOCTYPE html>
<html>
<head><title>Register - Web Desa</title></head>
<body>
<h2>Registrasi Akun Warga Desa</h2>
<?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
<?php if (isset($success)) echo "<p style='color:green;'>$success</p>"; ?>
<form method="POST">
    <input type="text" name="username" placeholder="Nama Warga" required />
    <input type="password" name="password" placeholder="Password" required />
    <input type="password" name="konfirmasi" placeholder="Konfirmasi Password" required />
    <button type="submit" name="register">Daftar</button>
</form>
<a href="PR_02login.php">Login</a>
</body>
</html>