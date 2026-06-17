<?php
session_start();

if (!isset($_SESSION['user'])){
    header("Location: index.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>
    <h2>cukimai</h2>
    <p>Login berhasil</p>
    <a href="logout.php">Logout</a>
</body>
</html>