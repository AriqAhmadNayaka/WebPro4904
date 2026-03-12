<?php
session_start();
if (!isset($_SESSION['users'])) {
    $_SESSION['users'] = ["selvinda" => "1234"];
}

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (isset($_SESSION['users'][$username]) && $_SESSION['users'][$username] == $password) {
        $_SESSION['login'] = true;
        $_SESSION['user'] = $username;
        header("Location: PR_02dashboard.php");
        exit;
    } else {
        $error = "Login gagal!";
    }
}
?>
<!DOCTYPE html>
<html>
<head><title>Login - Web Desa</title></head>
<body>
<h2>Login Warga Desa</h2>
<?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
<form method="POST">
    <input type="text" name="username" placeholder="Nama Warga" required />
    <input type="password" name="password" placeholder="Password" required />
    <button type="submit" name="login">Masuk</button>
</form>
<a href="PR_02register.php">Register</a>
</body>
</html>