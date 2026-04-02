<?php
require 'db.php';
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, name, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        // Verifikasi password yang sudah di-hash
        if (password_verify($password, $user['password'])) {
            // Redirect ke dashboard dengan membawa ID di URL
            header("Location: dashboard.php?id=" . $user['id']);
            exit();
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Email tidak ditemukan!";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Nuids</title>
    <link rel="stylesheet" href="global.css">
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="container-login">
        <h2 class="greeting">Hi! Selamat Datang Kembali di Nuids.</h2>

        <div class="acrylic-card">
            <div class="logo-container">
                <img src="logo.png" alt="" width="50px">
            </div>

            <?php if(!empty($error)): ?>
                <div style=" <?php if (password_verify($password, $user['password'])) {
                  echo 'color: green;';
                } else  {
                  echo 'color: red;';
                }?>  margin-bottom: 15px; text-align: center; font-weight: bold;"><?= $error ?></div>
            <?php endif; ?>

            <form action="login.php" method="POST">
                <div class="form-group">
                    <input type="email" name="email" class="custom-input input-teal" placeholder="Email" required>
                </div>
                <div class="form-group">
                    <input type="password" name="password" class="custom-input input-pink" placeholder="Password" required>
                </div>

                <button type="submit" class="btn-login">
                    <span class="btn-text">Login</span>
                </button>
            </form>
            <a href="register.php">Belum punya akun? Daftar sekarang</a>
        </div>
    </div>
    <script src="main.js"></script>
</body>
</html>