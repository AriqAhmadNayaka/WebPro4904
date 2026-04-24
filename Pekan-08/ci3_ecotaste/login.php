<?php
session_start();
require 'database.php';
$db = new Database();

if(isset($_POST['login_btn'])){
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $user = $db->login($username, $password);

    if($user){
        setcookie("user_login", $username, time() + 3600, "/");
        $_SESSION['login'] = true;
        $_SESSION['user'] = $username;
        header("Location: homepage.php");
        exit;
    } else {
        $error = "Username atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login EcoTaste</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <style>
        body { 
            background: #8BC34A; 
            font-family: 'Poppins', sans-serif; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            height: 100vh; 
            margin: 0;
        }

        .card { 
            background: white; 
            padding: 40px; 
            border-radius: 20px; 
            width: 340px; 
            box-shadow: 0 15px 35px rgba(0,0,0,0.2); 
            text-align: center;
        }

        .logo-icon {
            background: #4CAF50;
            color: white;
            width: 60px;
            height: 60px;
            line-height: 60px;
            border-radius: 50%;
            font-size: 24px;
            margin: 0 auto 20px;
        }

        h2 { 
            margin-bottom: 10px; 
            color: #4CAF50; 
            font-weight: 700;
        }

        p.subtitle {
            color: #777;
            font-size: 14px;
            margin-bottom: 25px;
        }

        .input-group {
            position: relative;
            margin-bottom: 15px;
        }

        .input-group i {
            position: absolute;
            left: 15px;
            top: 15px;
            color: #4CAF50;
        }

        input { 
            width: 100%; 
            padding: 12px 15px 12px 45px; 
            box-sizing: border-box; 
            border: 2px solid #eee; 
            border-radius: 10px; 
            font-family: inherit;
            transition: 0.3s;
        }

        input:focus {
            border-color: #4CAF50;
            outline: none;
            background: #f9fff9;
        }

        button { 
            width: 100%; 
            padding: 12px; 
            background: #4CAF50; 
            color: white; 
            border: none; 
            border-radius: 10px; 
            cursor: pointer; 
            font-weight: bold; 
            font-size: 16px;
            transition: 0.3s;
            margin-top: 10px;
        }

        button:hover {
            background: #388E3C;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(76, 175, 80, 0.3);
        }

        .err { 
            background: #ffebee;
            color: #c62828; 
            padding: 10px;
            border-radius: 8px;
            font-size: 13px; 
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
        }

        .reg-link { 
            display: block; 
            margin-top: 20px; 
            font-size: 14px; 
            color: #666; 
            text-decoration: none; 
        }

        .reg-link b { color: #4CAF50; }
        .reg-link:hover b { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="card">
        <div class="logo-icon">
            <i class="fas fa-leaf"></i>
        </div>
        <h2>EcoTaste</h2>
        <p class="subtitle">Silakan masuk ke akun Anda</p>

        <?php if (isset($error)) : ?>
            <div class="err">
                <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="input-group">
                <i class="fas fa-user"></i>
                <input type="text" name="username" placeholder="Username" required>
            </div>
            
            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" placeholder="Password" required>
            </div>
            
            <button type="submit" name="login_btn">LOGIN</button>
        </form>
        
        <a href="register.php" class="reg-link">
            Belum punya akun? <b>Daftar di sini</b>
        </a>
    </div>
</body>
</html>