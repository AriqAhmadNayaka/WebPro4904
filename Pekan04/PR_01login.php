<?php
include "koneksi.php";
session_start();

if(isset($_POST['login'])){
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0){
        $row = $result->fetch_assoc();
        if(password_verify($password, $row['password'])){
            $_SESSION['login'] = true;
            $_SESSION['user'] = $row['username'];
            header("Location: PR_01dashboard.php");
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
    <title>Halaman Login</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; font-family:"Poppins", Arial, sans-serif; }
        body {
          background: linear-gradient(to top, rgba(255,255,255,0.9), rgba(255,255,255,0)),
                      url("asetgambar/bg-1.jpg");
          background-size: cover; height:100vh; display:flex;
          justify-content:center; align-items:center; position:relative;
        }
        body::before { content:""; position:absolute; width:100%; height:100%;
          background:rgba(84,114,131,0.625); backdrop-filter:blur(3px); z-index:-1; }
        .container {
          background:rgba(91,118,124,0.85); width:400px; padding:40px;
          border-radius:20px; text-align:center; color:white;
          box-shadow:0 10px 25px rgba(0,0,0,0.35);
          animation:fadeIn 0.8s ease-in-out;
        }
        .container h2 { font-size:30px; font-weight:bold; margin-bottom:25px; }
        .container input {
          width:100%; padding:14px 16px; margin-bottom:18px;
          border:none; border-radius:30px; background:#e2e2e2;
          font-size:15px; transition:0.3s;
        }
        .container input:focus {
          background:#fff; box-shadow:0 0 10px rgba(255,255,255,0.6); outline:none;
        }
        .container button {
          width:60%; padding:12px; border:none; border-radius:30px;
          background:#fff; font-size:17px; font-weight:bold;
          cursor:pointer; transition:0.3s;
        }
        .container button:hover { background:#f2e6d7; transform:scale(1.05); }
        .register-link { margin-top:22px; font-size:15px; opacity:0.9; }
        .register-link a { color:#ffe7ad; font-weight:bold; text-decoration:none; transition:0.3s; }
        .register-link a:hover { color:#fff3ce; text-decoration:underline; }
        @keyframes fadeIn { from{opacity:0; transform:translateY(25px);} to{opacity:1; transform:translateY(0);} }
    </style>
</head>
<body>
<div class="container">
    <h2>Masuk</h2>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="POST" action="PR_01login.php">
        <input type="text" name="username" placeholder="Username" required />
        <input type="password" name="password" placeholder="Password" required />
        <button type="submit" name="login">Masuk</button>
        <div class="register-link">
            <span>Belum punya akun? </span>
            <a href="PR_01register.php">Register</a>
        </div>
    </form>
</div>
</body>
</html>