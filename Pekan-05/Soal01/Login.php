<?php
session_start();
// Menghubungkan file ini dengan koneksi database
include "koneksi.php";

// Mengecek apakah tombol login ditekan
if (isset($_POST['login'])) {

    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = mysqli_query($conn, "SELECT * FROM user WHERE email='$email' AND password='$password'");

    if (mysqli_num_rows($query) > 0) {

    $_SESSION['login'] = true; 
    header("Location: dashboard.php");
    exit;

} else {

    echo "<script>alert('Email atau Password salah!');</script>";
}
}
?>
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
      <a href="HomePage.html" class="logo">CyberVault</a>
      <ul class="nav-links">
        <li><a href="HomePage.html">Home</a></li>
        <li><a href="About.html">About</a></li>
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

      
      <div class="role-selection">
        <div class="role-option">
          <input type="radio" id="user" name="role" value="user" checked>
          <label for="user">User</label>
        </div>
        <div class="role-option">
          <input type="radio" id="admin" name="role" value="admin">
          <label for="admin">Admin</label>
        </div>
      </div>

      <form id="loginForm" method="POST">
        <div class="input-group">
          <input type="email" name="email" id="email" class="input" placeholder="Email" required>
        </div>

        <div class="input-group">
          <input type="password" name="password" id="password" class="input" placeholder="Password" required>
          <span class="toggle" onclick="togglePass()">👁️ View</span>
        </div>

        <div class="error-message" id="errorMsg"></div>

        <button type="submit" name="login" class="btn-login" id="loginBtn">Masuk Sekarang</button>
      </form>

      <div class="login-text">
        Belum punya akun? <a href="daftarakun.html">Daftar di sini</a>
      </div>
    </div>

  </div>
<?php
$name = $email = $password = "";
$nameErr = $emailErr = $passwordErr = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    function test_input($data) {
        return htmlspecialchars(stripslashes(trim($data)));
    }

    if (isset($_POST["email"])) {
        $email = test_input($_POST["email"]);
        if (empty($email)) {
            $emailErr = "Email harus diisi";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Format email tidak valid";
        }
    }

    if (isset($_POST["password"])) {
        $password = test_input($_POST["password"]);
        if (empty($password)) {
            $passwordErr = "Password harus diisi";
        } elseif (strlen($password) < 6) {
            $passwordErr = "Password harus minimal 6 karakter";
        } elseif (!preg_match("/[\W]/", $password)) {
            $passwordErr = "Password harus mengandung minimal 1 karakter spesial (!@#$%^&*)";
        }
    }

    if (!$emailErr && !$passwordErr) {

        if($email == "futuwah@gmail.com" && $password == "123456") {
                var_dump("Berhasil!");
                header("Location: page.php");
            }
            var_dump("Gagal!");
    }
}
?>
</body>
</html>