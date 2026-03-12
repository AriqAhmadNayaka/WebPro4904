<?php
session_start();

// Cek jika sudah login
if (isset($_SESSION['is_logged_in'])) {
    header("Location: beranda.php");
    exit();
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_input = $_POST['username_email'];
    $pass_input = $_POST['password'];

    // Cek apakah ada data di "database session"
    if (isset($_SESSION['user_db'])) {
        $db = $_SESSION['user_db'];

        if (($user_input == $db['username'] || $user_input == $db['email']) && $pass_input == $db['password']) {
            $_SESSION['is_logged_in'] = true;
            $_SESSION['current_user'] = $db['username'];
            header("Location: beranda.php");
            exit();
        } else {
            $error = "Username atau password salah!";
        }
    } else {
        $error = "Akun tidak ditemukan. Silakan registrasi dahulu.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoTaste | Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { font-family: 'Segoe UI', sans-serif; margin: 0; display: flex; flex-direction: column; align-items: center; background: linear-gradient(135deg, #66BB6A, #8BC34A); min-height: 100vh; }
        .logo { align-self: flex-start; padding: 20px; font-size: 32px; font-weight: bold; color: #fff; }
        .login-box { background: #fff; padding: 45px; border-radius: 20px; box-shadow: 0 15px 40px rgba(0,0,0,0.2); max-width: 400px; width: 90%; margin-top: 50px; text-align: center; }
        h2 { color: #4CAF50; font-size: 32px; text-align: left; }
        .input-group { margin-bottom: 20px; position: relative; }
        .input-group i { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #8BC34A; }
        .input-field { width: 100%; padding: 12px 15px 12px 45px; border: 1px solid #ccc; border-radius: 30px; box-sizing: border-box; outline: none; }
        .login-button { width: 100%; padding: 14px; border: none; border-radius: 30px; background: #4CAF50; color: white; font-size: 18px; font-weight: bold; cursor: pointer; transition: 0.3s; }
        .login-button:hover { background: #388E3C; }
        .error-msg { color: #721c24; background: #f8d7da; padding: 10px; border-radius: 10px; margin-bottom: 15px; font-size: 14px; }
    </style>
</head>
<body>
    <div class="logo"><i class="fas fa-leaf"></i> EcoTaste</div>
    <div class="login-box">
        <h2>Sign In</h2>
        
        <?php if($error != ""): ?>
            <div class="error-msg"><?php echo $error; ?></div>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <div class="input-group">
                <i class="fas fa-user"></i>
                <input type="text" name="username_email" class="input-field" placeholder="Username / E-mail" required>
            </div>
            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" class="input-field" placeholder="Password" required>
            </div>
            <button type="submit" class="login-button">Login</button>
        </form>
        <div style="margin-top:15px;">Belum punya akun? <a href="register.php" style="color:#4CAF50; text-decoration:none; font-weight:bold;">Daftar</a></div>
    </div>
</body>
</html>