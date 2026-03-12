<?php
session_start();

$error = "";
$success = "";

if(isset($_POST['register'])){

    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    if(strlen($password) < 6){
        $error = "Password minimal 6 karakter!";
    } else {
        $_SESSION['akun'] = [
            "email" => $email,
            "password" => $password,
            "role" => $role
        ];
        $success = "Registrasi berhasil! Silakan login.";
    }
}

if(isset($_POST['login'])){

    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    if(
        isset($_SESSION['akun']) &&
        $email == $_SESSION['akun']['email'] &&
        $password == $_SESSION['akun']['password'] &&
        $role == $_SESSION['akun']['role']
    ){
        $_SESSION['login'] = true;
        $_SESSION['email'] = $email;
        $_SESSION['role'] = $role;

        header("Location: Soal2.php");
        exit;
    } else {
        $error = "Email, password, atau role salah!";
    }
}
?>

<form method="POST">

    <div>
        <input type="radio" name="role" value="user" checked> User
        <input type="radio" name="role" value="admin"> Admin
    </div>

    <input type="email" name="email" placeholder="Email" required>

    <input type="password" name="password" placeholder="Password" required>

    <button type="submit" name="login">Login</button>

    <button type="submit" name="register">Register</button>

</form>

<?php if($error != "") echo "<p style='color:red;'>$error</p>"; ?>
<?php if($success != "") echo "<p style='color:green;'>$success</p>"; ?>