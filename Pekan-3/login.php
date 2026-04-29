<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = htmlspecialchars($_POST["email"]);
    $password = htmlspecialchars($_POST["password"]);

    // Validasi sederhana (contoh: email & password harus sama dengan data registrasi)
    if (isset($_SESSION["user"]) && $email == $_SESSION["user"]["email"] && $password == $_SESSION["user"]["password"]) {
        $_SESSION["currentUser"] = $_SESSION["user"];
        header("Location: home.php");
        exit();
    } else {
        $error = "Email atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
<div class="container-login">
    <div class="acrylic-card">
        <h2>Login</h2>
        <form method="post">
            <div class="form-group">
                <input type="email" name="email" placeholder="Email" class="custom-input input-teal" required>
            </div>
            <div class="form-group">
                <input type="password" name="password" placeholder="Password" class="custom-input input-pink" required>
            </div>
            <button type="submit" class="btn-login">Login</button>
        </form>
        <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
        <a href="register.php">Belum punya akun? Registrasi</a>
    </div>
</div>
</body>
</html>
