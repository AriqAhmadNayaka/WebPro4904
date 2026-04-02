<?php
session_start();
require 'koneksi.php'; 

if(isset($_POST['login_btn'])){
    if (!$conn) {
        die("Koneksi ke database hilang!");
    }

    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $query = "SELECT * FROM ecotaste WHERE username = '$username'";
    $result = mysqli_query($conn, $query);

    if($result && mysqli_num_rows($result) === 1){
        $row = mysqli_fetch_assoc($result);
        
        if($password === $row['password']){ 
            // --- IMPLEMENTASI COOKIE (Sesuai Modul) ---
            // Simpan username ke cookie selama 1 jam (3600 detik)
            setcookie("user_login", $username, time() + 3600, "/");

            $_SESSION['login'] = true;
            $_SESSION['user'] = $username;
            header("Location: homepage.php");
            exit;
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Username tidak ditemukan!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login EcoTaste</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root { --hijau: #4CAF50; --putih: #ffffff; }
        body { font-family: sans-serif; background: linear-gradient(135deg, #66BB6A, #8BC34A); min-height: 100vh; display: flex; flex-direction: column; align-items: center; margin: 0; }
        .logo { align-self: flex-start; padding: 20px; font-size: 28px; font-weight: bold; color: white; }
        .login-box { background: white; padding: 40px; border-radius: 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); width: 350px; margin-top: 50px; text-align: center; }
        h2 { color: var(--hijau); text-align: left; }
        .input-group { margin-bottom: 20px; position: relative; }
        .input-group i { position: absolute; left: 15px; top: 12px; color: var(--hijau); }
        .input-field { width: 100%; padding: 10px 10px 10px 40px; border: 1px solid #ddd; border-radius: 25px; box-sizing: border-box; }
        .login-button { width: 100%; padding: 12px; border: none; border-radius: 25px; background: var(--hijau); color: white; font-weight: bold; cursor: pointer; }
        .error-msg { color: white; background: #e74c3c; padding: 10px; border-radius: 10px; margin-bottom: 15px; font-size: 14px; }
    </style>
</head>
<body>
    <div class="logo"><i class="fas fa-leaf"></i> EcoTaste</div>
    <div class="login-box">
        <h2>Sign In</h2>
        <?php if(isset($error)): ?>
            <div class="error-msg"><?= $error; ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="input-group">
                <i class="fas fa-user"></i>
                <input type="text" name="username" class="input-field" placeholder="Username" 
                       value="<?php echo isset($_COOKIE['user_login']) ? $_COOKIE['user_login'] : ''; ?>" required>
            </div>
            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" class="input-field" placeholder="Password" required>
            </div>
            <button type="submit" name="login_btn" class="login-button">Login</button>
        </form>
    </div>
</body>
</html>