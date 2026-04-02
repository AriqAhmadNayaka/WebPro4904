<?php
session_start();
include 'koneksi.php';
include 'profil_helper.php';

ensureProfileSchema($conn);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email_input = mysqli_real_escape_string($conn, $_POST['email']);
    $password_input = $_POST['password'];

    $query = "SELECT * FROM users WHERE email = '$email_input'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result);
        if (password_verify($password_input, $user['password'])) {
            syncUserSessionFromProfile($user);
            // Setelah login berhasil, pengguna diarahkan ke halaman utama aplikasi.
            header("Location: dashboard.php");
            exit;
        }
    }
    header("Location: login.php?status=login-failed");
    exit;
}
?>
