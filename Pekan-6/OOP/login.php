<?php
require_once "classes/User.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = new User();
    $result = $user->login($_POST["email"], $_POST["password"]);
    if ($result) {
        $_SESSION["currentUser"] = $result;
        header("Location: home.php");
        exit();
    } else {
        echo "Login gagal!";
    }
}
?>
