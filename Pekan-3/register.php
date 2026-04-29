<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST["name"]);
    $email = htmlspecialchars($_POST["email"]);
    $password = htmlspecialchars($_POST["password"]);

    // Simpan data user ke session (belum pakai database)
    $_SESSION["user"] = [
        "name" => $name,
        "email" => $email,
        "password" => $password
    ];

    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registrasi</title>
    <link rel="stylesheet" href="register.css">
</head>
<body>
<div class="container-register">
    <div class="acrylic-card">
        <h2>Registrasi</h2>
        <form method="post">
            <div class="form-group">
                <input type="text" name="name" placeholder="Nama Lengkap" class="custom-input input-teal" required>
            </div>
            <div class="form-group">
                <input type="email" name="email" placeholder="Email" class="custom-input input-pink-blue" required>
            </div>
            <div class="form-group">
                <input type="password" name="password" placeholder="Password" class="custom-input input-pink" required>
            </div>
            <button type="submit" class="btn-register">Register</button>
        </form>
        <a href="login.php">Sudah punya akun? Login</a>
    </div>
</div>
</body>
</html>
