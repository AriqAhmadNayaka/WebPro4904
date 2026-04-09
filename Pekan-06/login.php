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
<html>
<head>
    <title>Login EcoTaste</title>
    <style>
        body { background: #8BC34A; font-family: sans-serif; display: flex; justify-content: center; padding-top: 100px; }
        .card { background: white; padding: 20px; border-radius: 10px; width: 300px; box-shadow: 0 5px 15px rgba(0,0,0,0.2); }
        h2 { text-align: center; color: #4CAF50; }
        input { width: 100%; padding: 10px; margin: 10px 0; box-sizing: border-box; border: 1px solid #ddd; border-radius: 5px; }
        button { width: 100%; padding: 10px; background: #4CAF50; color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; }
        .err { color: red; text-align: center; font-size: 14px; }
    </style>
</head>
<body>
    <div class="card">
        <h2>EcoTaste Login</h2>
        <?php if (isset($error)) : ?>
            <p class="err"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php endif; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="login_btn">LOGIN</button>
        </form>
    </div>
</body>
</html>
