
<!DOCTYPE html>
<html lang="in">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pendaftaran</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            min-height: 100vh;
            background-color: #ebfbfa;
            background-image:
                radial-gradient(circle at 10% 100%,
                    rgba(5, 12, 87, 0.607),
                    rgba(70, 79, 184, 0.281),
                    transparent 40%),
                radial-gradient(circle at 100% 10%,
                    rgba(60, 252, 242, 0.42),
                    rgba(207, 244, 248, 0.756),
                    transparent 60%);
            background-repeat: no-repeat;
            background-attachment: fixed;
        }

        .form-container {
            width: 100%;
            display: flex;
            justify-content: center;
            margin-top: 60px;
        }

        /* CARD FORM UTAMA */
        .form-box {
            width: 100%;
            max-width: 420px;
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
            padding: 24px 24px 28px;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .input-card {
            background: #fdfdfd;
            border: 1px solid #ddd;
            padding: 10px 12px;
            border-radius: 10px;
            transition: 0.3s ease;
        }

        .input-card:hover {
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.12);
            transform: translateY(-2px);
        }

        .input-card:focus-within {
            border-color: #3a71ff;
            box-shadow: 0 3px 10px rgba(149, 109, 243, 0.8);
            transform: translateY(-2px);
        }

        .input-card input {
            width: 100%;
            padding: 6px 0;
            border: none;
            outline: none;
            font-size: 15px;
            background: transparent;
        }

        button {
            padding: 12px;
            background: #956df3;
            color: #fff;
            border: none;
            border-radius: 999px;
            cursor: pointer;
            transition: 0.2s ease;
            width: 100%;
            font-size: 15px;
        }

        button:hover {
            background: #7944f5;
        }

        .login-link {
            text-align: center;
            margin-top: 10px;
        }

        a {
            color: #6a0dad;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .login-options {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
        }

        .login-option {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
        }

        .login-option label {
            font-size: 12px;
            color: #333;
            text-align: center;
        }

        .login-option input[type="image"] {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            border: 2px solid #ccc;
            cursor: pointer;
            transition: 0.3s ease;
        }

        .login-option input[type="image"]:hover {
            border-color: #3a71ff;
            box-shadow: 0 4px 15px rgba(149, 109, 243, 1);
            transform: scale(1.1);
        }

        header h1,
        header h2 {
            margin: 0;
        }

        header {
            margin-top: 30px;
            text-align: center;
        }
    </style>
</head>

<body>
    <section id="home"></section>

    <section id="data-profile" class="container-profile">
        <header>
            <h1 style="color: #956df3;">NaviBiz</h1>
            <h2 style="color: #956df3;">Pendaftaran / Registration</h2>
        </header>
    </section>

    <div class="form-container">
        <form class="form-box" action="" method="POST">
            <div class="input-card">
                <input type="text" name="name" placeholder="Masukkan nama lengkap" required>
            </div>

            <div class="input-card">
                <input type="email" name="email" placeholder="Masukkan email" required>
            </div>

            <div class="input-card">
                <input type="text" name="username" placeholder="Buat Nama Pengguna" required>
            </div>

            <div class="input-card">
                <input type="password" name="password" placeholder="Buat password" required>
            </div>

            <div class="input-card">
                <input type="tel" name="telepon" placeholder="Masukkan Nomor Telepon" pattern="[0-9]{10,15}" required> 
            </div>

            <div class="input-card">
                <input type="text" name="alamat" placeholder="Masukkan alamat lengkap" required>
            </div>

            <button type="submit" name="submit">Daftar</button>
        </form>
    </div>

<?php
require "database.php"; // memanggil class koneksi database OOP
require "User.php"; // memanggil class user untuk registrasi

$db = new Database(); // membuat objek database untuk koneksi
$user = new User($db->conn); // kirim koneksi ke class User

$message = ""; // variabel untuk menyimpan pesan hasil proses

if(isset($_POST['submit'])){ // cek apakah form sudah disubmit user

    // ambil data dari form
    $data = [ // array untuk menampung semua input user
        "name" => $_POST['name'], // ambil input nama dari form
        "email" => $_POST['email'], // ambil input email dari form
        "username" => $_POST['username'], // ambil input username dari form
        "password" => $_POST['password'], // ambil input password dari form
        "telepon" => $_POST['telepon'], // ambil input nomor telepon user
        "alamat" => $_POST['alamat'] // ambil input alamat dari form
    ];

    // cek username
    if($user->cekUsername($data['username'])){ // cek apakah username sudah terdaftar
        $message = "Username sudah digunakan!"; // set pesan jika username sudah ada
    } else {

        if($user->register($data)){ // simpan data user ke database
            $message = "Registrasi berhasil!"; // pesan jika berhasil registrasi
        } else {
            $message = "Terjadi error!"; // pesan jika gagal simpan data
        }
    }
}
?>

    <h4 style="text-align: center; color: grey;">Atau Daftar Menggunakan</h4>
    <div class="login-options">
        <div class="login-option">
            <input type="image" src="google.icon.jpg" alt="Login dengan Google" name="login_google">
            <label>Google</label>
        </div>
        <div class="login-option">
            <input type="image" src="email.icon.jpg" alt="Login dengan Email" name="login_email">
            <label>Email</label>
        </div>
    </div>

    <br>
    <div class="login-link">
        <a href="login.php">Apakah Anda sudah memiliki akun? Login disini</a>
    </div>
</body>

</html>