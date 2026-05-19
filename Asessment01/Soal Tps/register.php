<?php
require_once 'User.php';
$userObj = new User($conn);

if (isset($POST['register'])) {
    $username        = $_POST['username'];
    $password        = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    $hasil = $userObj->register($username, $password, $confirmPassword);

    if (hasil === "sukses") {
        echo "<script>alert('Registrasi Berhasil! Silakan Login'); window.location='login.php';</script>";
    } else {
        $error = $hasil;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <form method="POST">
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

            <input type="text"     name="username"         placeholder="Username"       required>
            <input type="password" name="password"         placeholder="Password"       required>
            <input type="password" name="confirm_password" placeholder="Ulangi Password" required>

            <button type="submit" name="register">DAFTAR SEKARANG</button>
            <p>Sudah punya akun? <a href="login.php">Login di sini</a></p>
    </form>
</body>
</html>
?>