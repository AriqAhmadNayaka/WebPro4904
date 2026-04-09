<?php
include "koneksi.php";

$message = "";

if(isset($_POST['register'])){
    $username = $_POST['username'];
    $password = $_POST['password'];

    $cek = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");

    if(mysqli_num_rows($cek) > 0){
        $message = "Username sudah digunakan!";
    } else {
        mysqli_query($conn, 
        "INSERT INTO users (username, password) 
         VALUES ('$username','$password')");

        $message = "Registrasi berhasil! Silakan login.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>

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

        .register-box {
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

        input {
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

        .message {
            margin-top: 10px;
            font-size: 14px;
        }

        .error {
            color: red;
        }

        .success {
            color: green;
        }

        a {
            display: block;
            margin-top: 10px;
            color: #3a71ff;
            text-decoration: none;
            font-size: 14px;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="register-box">
    <h2>Register</h2>

    <form method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>

        <button type="submit" name="register">Daftar</button>
    </form>

    <?php if($message != ""): ?>
        <div class="message <?php echo (strpos($message, 'berhasil') !== false) ? 'success' : 'error'; ?>">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <a href="login.php">Kembali ke Login</a>
</div>

</body>
</html>