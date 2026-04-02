<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "cybervault");

if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $password = $_POST['password'];

    $result = mysqli_query($conn, "SELECT * FROM datauser WHERE email = '$email'");

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        // Verifikasi password hash
        if (password_verify($password, $row['password'])) {
            $_SESSION['login'] = true;
            $_SESSION['nama'] = $row['nama'];
            header("Location: datauser.php");
            exit;
        }
    }
    $error = "Email atau Password salah!";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login • CyberVault</title>
    <style>
        body { font-family: sans-serif; background: #0a0a0f; color: #fff; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .box { background: rgba(255,255,255,0.05); padding: 30px; border-radius: 12px; border: 1px solid #00d4ff; width: 300px; }
        input { width: 100%; padding: 10px; margin: 10px 0; background: #1a1a2e; border: 1px solid #333; color: #fff; border-radius: 5px; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background: #00d4ff; border: none; font-weight: bold; cursor: pointer; border-radius: 5px; color: #000; }
        .error { color: #ff4d4d; font-size: 12px; text-align: center; }
    </style>
</head>
<body>
    <div class="box">
        <h1 style="text-align:center; color:#00d4ff;">CyberVault</h1>
        <h2 style="text-align:center; color:#00d4ff;">Login</h2>
        <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form action="" method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="login">Masuk</button>
            <p style="text-align:center; font-size:12px;">Belum punya akun? <a href="daftar.php" style="color:#00d4ff;">Daftar</a></p>
        </form>
    </div>
</body>
</html>