<?php
session_start();
include "koneksi.php";

$username = "";

if(isset($_COOKIE['username'])){
    $username = $_COOKIE['username'];
}

if(isset($_SESSION['username'])){
    header("Location: dashboard.php");
    exit;
}

$error = "";

if(isset($_POST['login'])){
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $query = "SELECT * FROM users
              WHERE username='$username' AND password='$password'";
    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result) > 0){
        $_SESSION['username'] = $username;

        if(isset($_POST['remember'])){
            setcookie("username", $username, time() + (86400 * 7), "/");
        }

        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Username atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #8D6DFD, #3a71ff);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-box {
            background: white;
            padding: 30px;
            border-radius: 15px;
            width: 320px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
            color: #333;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ddd;
            border-radius: 8px;
            outline: none;
            transition: 0.3s;
        }

        input:focus {
            border-color: #8D6DFD;
            box-shadow: 0 0 5px rgba(141,109,253,0.5);
        }

        label {
            font-size: 14px;
            color: #555;
        }

        button {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border: none;
            border-radius: 8px;
            background: #8D6DFD;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background: #6f4df2;
        }

        a {
            color: #3a71ff;
            text-decoration: none;
            font-size: 14px;
        }

        a:hover {
            text-decoration: underline;
        }

        .error {
            margin-top: 10px;
            color: red;
            font-size: 14px;
        }
    </style>
</head>
<body>

<div class="login-box">
    <h2>Login</h2>

    <form method="POST">
        <input type="text" name="username" 
               value="<?php echo $username; ?>" 
               placeholder="Username" required>

        <input type="password" name="password" 
               placeholder="Password" required>

        <label>
            <input type="checkbox" name="remember"> Remember Me
        </label>

        <button type="submit" name="login">Login</button>

        <p>Belum punya akun? <a href="register.php">Daftar</a></p>
    </form>

    <?php if($error != "") echo "<div class='error'>$error</div>"; ?>
</div>

</body>
</html>