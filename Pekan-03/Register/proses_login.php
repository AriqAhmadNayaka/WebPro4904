<?php
session_start();

$username = $_POST['username'];
$password = $_POST['password'];

if($username == "admin" && $password == "123"){

    $_SESSION['user'] = $username;

    header("Location: dashboard.php");

}else{

    echo "Login gagal! <br>";
    echo "<a href='index.php'>Kembali</a>";

}

?>