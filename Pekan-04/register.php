<?php
include 'koneksi.php';

if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    mysqli_query($conn, "INSERT INTO users VALUES('', '$username', '$email', '$password', NOW())");
    header("Location: login.php");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Register</title>
<style>
body {
    font-family: Arial;
    background: linear-gradient(135deg, #43cea2, #185a9d);
}
.container {
    width: 350px;
    margin: 100px auto;
    background: white;
    padding: 30px;
    border-radius: 10px;
    text-align: center;
}
input {
    width: 100%;
    padding: 10px;
    margin: 10px 0;
}
button {
    width: 100%;
    padding: 10px;
    background: #43cea2;
    color: white;
    border: none;
}
</style>
</head>
<body>

<div class="container">
    <h2>Register</h2>
    <form method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button name="register">Daftar</button>
    </form>
</div>

</body>
</html>