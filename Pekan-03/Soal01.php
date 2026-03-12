<?php
session_start();

if(!isset($_SESSION['user'])){
    $_SESSION['user'] = [];
}

if(isset($_POST['register'])){
    $_SESSION['user'] = [
        "username" => $_POST['username'],
        "password" => $_POST['password']
    ];
    $message = "Registrasi berhasil! Silakan login.";
}

if(isset($_POST['login'])){
    $username = $_POST['username'];
    $password = $_POST['password'];

    if($username == $_SESSION['user']['username'] &&
       $password == $_SESSION['user']['password']){
        $_SESSION['login'] = true;
        header("Location: app.php");
        exit;
    } else {
        $error = "Username atau Password salah!";
    }
}
?>

<h2>Registrasi</h2>
<form method="POST">
    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <button type="submit" name="register">Daftar</button>
</form>

<hr>

<h2>Login</h2>
<form method="POST">
    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <button type="submit" name="login">Login</button>
</form>

<?php
if(isset($message)) echo "<p style='color:green;'>$message</p>";
if(isset($error)) echo "<p style='color:red;'>$error</p>";
?>