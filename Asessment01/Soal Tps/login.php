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

<?php 
session_start();
require_once 'dashboard.php';

$conn = Database::getInstance()->getConnection();

$userObj = new User($conn);

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($userObj->login($username, $password)) {
        header("Location: dashboard.php");
        exit;
    } else {
        $error = true;
    }
}
?>