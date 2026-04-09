<?php
require 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    // Enkripsi password demi keamanan
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); 
    $notelp = $_POST['notelp'];
    $alamat = $_POST['alamat'];

    // Insert data ke tabel users
    $stmt = $conn->prepare("INSERT INTO users (nama_lengkap, email, username, password, no_telp, alamat) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $nama, $email, $username, $password, $notelp, $alamat);
    
    if ($stmt->execute()) {
        echo "<script>alert('Pendaftaran Berhasil! Silakan Login.'); window.location='login.php';</script>";
    } else {
        echo "<script>alert('Pendaftaran Gagal!');</script>";
    }
    $stmt->close();
}
?>

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
            margin-top: 40px;
        }

        .form-box {
            width: 380px;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .input-card {
            background: #fff;
            border: 1px solid #ccc;
            padding: 12px;
            border-radius: 10px;
            transition: 0.3s ease;
        }

        .input-card:hover {
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.15);
            transform: translateY(-4px);
        }

        .input-card:focus-within {
            border-color: #3a71ff;
            box-shadow: 0 3px 10px rgba(149, 109, 243, 1);
            transform: translateY(-4px);
        }

        .input-card input {
            width: 100%;
            padding: 8px;
            border: none;
            outline: none;
            font-size: 15px;
        }

        button {
            padding: 12px;
            background: #956df3;
            color: #fff;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.2s ease;
            width: 100%;
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
    </style>
</head>

<body>
    <a href="landing.html">Home</a>

    <section id="home"></section>

    <section id="data-profile" class="container-profile">
        <header>
            <h1 style="text-align: center;color: white;">NaviBiz</h1>
            <h2 style="text-align: center;color: white;">Pendaftaran / Registration</h2>
        </header>
    </section>

    <div class="form-container">
        <form class="form-box" action="" method="post">

            <div class="input-card">
                <input type="text" name="nama" placeholder="Masukkan nama lengkap" required>
            </div>

            <div class="input-card">
                <input type="email" name="email" placeholder="Masukkan email (contoh@gmail.com)" required>
            </div>

            <div class="input-card">
                <input type="text" name="username" placeholder="Buat Nama Pengguna" required>
            </div>

            <div class="input-card">
                <input type="password" name="password" placeholder="Buat password" required>
            </div>

            <div class="input-card">
                <input type="tel" name="notelp" placeholder="Masukkan Nomor Telepon" pattern="[0-9]{10,15}" required>
            </div>

            <div class="input-card">
                <input type="text" name="alamat" placeholder="Masukkan alamat lengkap" required>
            </div>
            <button type="submit">Daftar</button>
        </form>
    </div>

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
        <a href="login.html">Apakah Anda sudah memiliki akun? Login disini</a>
    </div>
</body>

</html>