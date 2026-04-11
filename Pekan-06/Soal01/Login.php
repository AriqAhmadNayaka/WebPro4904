<?php
// Aktifkan error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require_once __DIR__ . '/classes/User.php';

if (isset($_POST['login'])) {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        echo "<script>alert('Email dan Password harus diisi!');</script>";
    } else {
        $userObj = new User();
        $user = $userObj->login($email, $password);

        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['login'] = true;
            header("Location: dashboard.php");
            exit;
        } else {
            echo "<script>alert('Email atau Password salah!');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Masuk - CyberVault</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="login.css">
</head>
<body>

  <header class="main-header">
    <nav class="nav-bar">
      <a href="#" class="logo">CyberVault</a>
      <ul class="nav-links">
        <li><a href="#">Home</a></li>
        <li><a href="#">About</a></li>
      </ul>
    </nav>
  </header>

  <div class="login-wrapper">
    <div class="image-box">
      <img src="foto1.png" alt="Cyber Security Protection">
    </div>

    <div class="form-box">
      <div class="top">
        <img src="foto2.png" alt="Logo" class="logo-img">
        <h2>CyberVault</h2>
        <p>Masuk ke akun Anda</p>
      </div>

      <form method="POST">
        <div class="input-group">
          <input type="email" name="email" class="input" placeholder="Email" required>
        </div>

        <div class="input-group">
          <input type="password" name="password" id="password" class="input" placeholder="Password" required>
          <span class="toggle" onclick="togglePass()">👁️ View</span>
        </div>

        <button type="submit" name="login" class="btn-login">Masuk Sekarang</button>
      </form>

      <div class="login-text">
        Belum punya akun? <a href="Registrasi.php">Daftar di sini</a>
      </div>
    </div>
  </div>

  <script>
    function togglePass() {
      const pass = document.getElementById('password');
      pass.type = (pass.type === 'password') ? 'text' : 'password';
    }
  </script>
</body>
</html>