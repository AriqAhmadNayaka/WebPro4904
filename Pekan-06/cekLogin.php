<?php
//cekLogin.php
session_start();
require_once 'app/bootstrap.php';

//ambil data email dan password dari form login
$email = trim($_POST['email'] ?? $_POST['username'] ?? '');
$password = $_POST['password'] ?? '';
$role = trim($_POST['role'] ?? 'Orang Tua');

//jika email atau password kosong, redirect kembali ke halaman login    
if ($email === '' || $password === '') {
    header("Location: auth.php");
    exit;
}

//cek login dengan mencari data user berdasarkan email dan role
$data = $userRepository->findByEmailAndRole($email, $role);

//jika data ditemukan dan password cocok, set session dan redirect ke dashboard
if ($data && password_verify($password, $data['password'])) {
    $_SESSION['login'] = true;
    $_SESSION['username'] = $data['name'];
    $_SESSION['current_user'] = [
        'id' => $data['id'],
        'name' => $data['name'],
        'email' => $data['email'],
        'role' => $data['role'],
    ];

    //jika login berhasil, redirect ke dashboard orang tua
    header("Location: dasboard/dashboardOrtu.php");
    exit;
} else {
    header("Location: auth.php");
    exit;
}
?>
