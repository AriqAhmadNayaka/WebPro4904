<?php
include 'config.php';

if (isset($_POST['register'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    //  ngecek apakah username sudah ada di database
    $check_user = mysqli_query($conn, "SELECT username FROM users WHERE username = '$username'");
    
    if (mysqli_num_rows($check_user) > 0) {
        $error = "Username sudah terdaftar!";
    } else {
        // ngecek apakah password dan konfirmasi password cocok
        if ($password === $confirm_password) {
            
            // Hash Password (Mengubah password jadi kode acak agar aman)
            $password_aman = password_hash($password, PASSWORD_DEFAULT);

            // menyimpan ke database
            $query = "INSERT INTO users (username, password) VALUES ('$username', '$password_aman')";
            if (mysqli_query($conn, $query)) {
                echo "<script>alert('Registrasi Berhasil! Silakan Login'); window.location='login.php';</script>";
            } else {
                $error = "Gagal mendaftarkan user.";
            }
        } else {
            $error = "Konfirmasi password tidak cocok!";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Daftar Akun Baru</h2>
        <form method="post">
            <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
            
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="confirm_password" placeholder="Ulangi Password" required>
            
            <button type="submit" name="register">DAFTAR SEKARANG</button>
            <p>Sudah punya akun? <a href="login.php">Login di sini</a></p>
        </form>
    </div>
</body>
</html>