<?php
include 'koneksi.php';

if (isset($_POST['register'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $query = "INSERT INTO users (username, password) VALUES ('$username', '$password')";
    
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Registrasi Berhasil!'); window.location='login.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>EcoTaste | Registrasi</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Menggunakan palette warna yang sama dengan Beranda kamu */
        body { font-family: 'Poppins', sans-serif; background: #F4F9F4; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .auth-card { background: white; padding: 40px; border-radius: 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); width: 350px; text-align: center; }
        .auth-card h2 { color: #4CAF50; margin-bottom: 20px; }
        input { width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #ddd; border-radius: 8px; box-sizing: border-box; }
        .btn-auth { background: #4CAF50; color: white; border: none; width: 100%; padding: 12px; border-radius: 8px; cursor: pointer; font-weight: bold; }
        .btn-auth:hover { background: #1B5E20; }
        a { color: #4CAF50; text-decoration: none; font-size: 14px; }
    </style>
</head>
<body>
    <div class="auth-card">
        <h2><i class="fas fa-leaf"></i> Daftar EcoTaste</h2>
        <form action="" method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="register" class="btn-auth">Daftar Sekarang</button>
        </form>
        <p style="font-size: 14px; margin-top: 15px;">Sudah punya akun? <a href="login.php">Login di sini</a></p>
    </div>
</body>
</html>