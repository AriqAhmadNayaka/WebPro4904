<?php
session_start();

// Proses registrasi langsung di file ini
if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $konfirmasi = $_POST['konfirmasi'];

    if ($password !== $konfirmasi) {
        $error = "Password dan konfirmasi tidak sama!";
    } else {
        $_SESSION['users'][$username] = $password;
        $success = "Registrasi berhasil! Silakan login.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Halaman Registrasi</title>
    <style>
        /* CSS sama persis dengan login.php biar konsisten */
        * { margin:0; padding:0; box-sizing:border-box; font-family:"Poppins", Arial, sans-serif; }
        body {
          background: linear-gradient(to top, rgba(255,255,255,0.9), rgba(255,255,255,0)),
                      url("/asetgambar/bg-1.jpg");
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
        .container input:focus { background:#fff; box-shadow:0 0 10px rgba(255,255,255,0.6); outline:none; }
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
    <h2>Daftar</h2>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <?php if (isset($success)) echo "<p style='color:lightgreen;'>$success</p>"; ?>
    <form method="POST">
        <input type="text" name="username" placeholder="Username" required />
        <input type="password" name="password" placeholder="Buat Password" required />
        <input type="password" name="konfirmasi" placeholder="Konfirmasi Password" required />
        <button type="submit" name="register">Daftar</button>
        <div class="register-link">
            <span>Sudah punya akun? </span>
            <a href="PR_01login.php">Login</a>
        </div>
    </form>
</div>
</body>
</html>