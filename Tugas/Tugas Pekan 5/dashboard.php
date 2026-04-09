<?php
session_start();

if(isset($_COOKIE['username']) && !isset($_SESSION['username'])){
    $_SESSION['username'] = $_COOKIE['username'];
}

if(!isset($_SESSION['username'])){
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #8D6DFD, #3a71ff);
            margin: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .dashboard-box {
            background: white;
            padding: 40px;
            border-radius: 16px;
            text-align: center;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            width: 350px;
        }

        h2 {
            color: #333;
            margin-bottom: 20px;
        }

        .username {
            color: #8D6DFD;
            font-weight: bold;
        }

        .logout-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background: #ff4d4d;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            transition: 0.3s;
        }

        .logout-btn:hover {
            background: #e60000;
        }
    </style>
</head>
<body>

<div class="dashboard-box">
    <h2>Selamat datang, <span class="username"><?php echo $username; ?></span> 🎉</h2>

    <p>Kamu berhasil login ke sistem</p>

    <a href="logout.php" class="logout-btn">Logout</a>
</div>

</body>
</html>