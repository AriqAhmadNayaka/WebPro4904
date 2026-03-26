<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
</head>
<body>
    <h2>Registrasi</h2>
    <form method="POST" action="">
        Username: <input type="text" name="username" required><br>
        Password: <input type="password" name="password" required><br>
        Email: <input type="email" name="email" required><br>
        <button type="submit" name="register">Daftar</button>
    </form>

<?php
include 'PemeriksaKoneksi.php';

if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $email = $_POST['email'];

    mysqli_query($conn, "INSERT INTO users VALUES('', '$username', '$password', '$email')");
    echo "Registrasi berhasil!";
}
?>
</body>
</html>