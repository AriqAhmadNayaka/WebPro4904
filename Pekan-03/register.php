<?php
session_start();

// Jika sudah login, arahkan ke beranda
if (isset($_SESSION['is_logged_in'])) {
    header("Location: beranda.php");
    exit();
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Simulasi database menggunakan Session
    $_SESSION['user_db'] = [
        'username' => $username,
        'email' => $email,
        'password' => $password 
    ];

    $message = "success";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoTaste | Sign Up</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; display: flex; flex-direction: column; align-items: center; background: linear-gradient(135deg, #4CAF50, #8BC34A); min-height: 100vh; color: #333; }
        .logo { align-self: flex-start; padding: 20px; font-size: 32px; font-weight: bold; color: #fff; }
        .signup-container { flex-grow: 1; display: flex; justify-content: center; align-items: center; width: 100%; }
        .signup-box { background: #fff; padding: 45px; border-radius: 20px; box-shadow: 0 15px 40px rgba(0,0,0,0.2); max-width: 400px; width: 90%; text-align: center; }
        h2 { color: #4CAF50; font-size: 32px; margin-bottom: 25px; text-align: left; }
        .input-group { margin-bottom: 20px; position: relative; }
        .input-group i { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #8BC34A; }
        .input-field { width: 100%; padding: 12px 15px 12px 45px; border: 1px solid #ccc; border-radius: 30px; box-sizing: border-box; outline: none; }
        .signup-button { width: 100%; padding: 14px; border: none; border-radius: 30px; background: #4CAF50; color: white; font-size: 18px; font-weight: bold; cursor: pointer; transition: 0.3s; }
        .signup-button:hover { background: #388E3C; }
        .success-msg { background: #d4edda; color: #155724; padding: 10px; border-radius: 10px; margin-bottom: 15px; }
    </style>
</head>
<body>
    <div class="logo"><i class="fas fa-leaf"></i> EcoTaste</div>
    <div class="signup-container">
        <div class="signup-box">
            <h2>Sign Up</h2>
            
            <?php if($message == "success"): ?>
                <div class="success-msg">
                    Akun berhasil dibuat! <a href="login.php" style="color: #155724; font-weight: bold;">Klik untuk Login</a>
                </div>
            <?php endif; ?>

            <form action="register.php" method="POST">
                <div class="input-group">
                    <i class="fas fa-user"></i>
                    <input type="text" name="username" class="input-field" placeholder="Username" required>
                </div>
                <div class="input-group">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="email" class="input-field" placeholder="E-mail" required>
                </div>
                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" class="input-field" placeholder="Password" required>
                </div>
                <button type="submit" class="signup-button">Sign Up</button>
            </form>
            <div style="margin-top:15px;">Sudah punya akun? <a href="login.php" style="color:#4CAF50; text-decoration:none; font-weight:bold;">Login</a></div>
        </div>
    </div>
</body>
</html>