<?php
session_start();
require_once 'database.php';

$pesan = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $auth = new Auth();
    $hasil = $auth->register($_POST['username'], $_POST['email'], $_POST['password']);
    
    if ($hasil === "success") {
        echo "<script>alert('Registrasi berhasil!'); window.location.href='login.php';</script>";
        exit;
    } else {
        $pesan = "<p style='color:red; text-align:center;'>$hasil</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: linear-gradient(135deg, #000000, #5d5af2); min-height: 100vh; display: flex; flex-direction: column; align-items: center; margin: 0; }
        .logo { padding: 25px; font-size: 24px; font-weight: 700; color: white; width: 100%; position: absolute; top: 0; left: 0; }
        .box { background: white; padding: 40px; border-radius: 15px; width: 100%; max-width: 400px; margin-top: 100px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        h2 { color: #5d5af2; margin-bottom: 25px; }
        input { width: 100%; padding: 12px 15px; border: 1px solid #ddd; border-radius: 25px; outline: none; margin-bottom: 15px; box-sizing: border-box; }
        button { width: 100%; padding: 12px; border: none; border-radius: 25px; background: #5d5af2; color: white; font-weight: bold; cursor: pointer; margin-top: 10px; }
        button:hover { background: #000000; }
        .link { text-align: center; margin-top: 20px; font-size: 14px; }
        .link a { color: #5d5af2; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>
    <div class="box">
        <h2>Sign Up</h2>
        <?php echo $pesan; ?>
        <form action="register.php" method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="E-mail" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Sign Up</button>
        </form>
        <div class="link">Sudah punya akun? <a href="login.php">Login</a></div>
    </div>
</body>
</html>