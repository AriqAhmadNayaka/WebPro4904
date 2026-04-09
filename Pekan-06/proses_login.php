<?php
session_start();
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email_input = mysqli_real_escape_string($conn, $_POST['email']);
    $password_input = $_POST['password'];
    $query = "SELECT * FROM users WHERE email = '$email_input'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result);
        if (password_verify($password_input, $user['password'])) {
            $_SESSION['user_login'] = $user['nama'];
            if(isset($_POST['remember'])){
                setcookie("user_login", $user['nama'], time() + (86400 * 7), "/"); // 7 hari
            }
            header("Location: dasboard.php");
            exit;
        }
    }
    echo "<script>alert('Email atau Password salah!'); window.location='login.php';</script>";
}
?>