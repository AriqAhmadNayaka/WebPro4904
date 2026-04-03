<?php
session_start(); //memulai session (biar PHP bisa simpan data login user)
include "koneksi.php"; //menghubungkan file ini ke database MySQL

$username = ""; //mencegah error

if(isset($_COOKIE['username'])){
    $username = $_COOKIE['username']; //mengecek apakah ada cookie bernama username
}

if(isset($_SESSION['username'])){
    header("Location: dashboard.php"); //kalau user sudah login, 
                                     //agar user tidak harus balik ke halaman login
    exit;
}

$error = ""; //untuk menyimpan pesan error

if(isset($_POST['login'])){ //mengecek apakah tombol login sudah ditekan

    //ambil data dari form
    $username = mysqli_real_escape_string($conn, $_POST['username']); 
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $query = "SELECT * FROM user WHERE username='$username' AND password='$password'";
    //mencari data user di database yang cocok dengan input

    $result = mysqli_query($conn, $query);
    //menjalankan query ke database

    if(mysqli_num_rows($result) > 0){ //mengecek apakah data user ditemukan (login berhasil)
        $_SESSION['username'] = $username; //menyimpan username ke session sebagai tanda user sudah login

        //REMEMBER ME untuk menyimpan username ke cookie
        if(isset($_POST['remember'])){
            setcookie("username", $username, time() + (86400 * 7), "/");
        } else {
            setcookie("username", "", time() - 3600, "/"); // hapus cookie
        }
        echo "<script>
        alert('Login berhasil!');
        window.location='dashboard.php'; 
        </script>";
        //menampilkan notifikasi dan mengarahkan ke halaman dashboard
    } else {
        $error = "Username atau password salah!"; //menyimpan pesan error jika login tidak berhasil
    }
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
            font-family: 'Poppins', sans-serif;
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

        .error-box {
            background: #ffe5e5;
            color: #d8000c;
            padding: 10px;
            border-radius: 8px;
            font-size: 13px;
            text-align: center;
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
            <h2 style="color: #956df3;">Masuk / Login</h2>
        </header>
    </section>

    <div class="form-container">
        <form class="form-box" method="POST" action=""> 
           
            <div class="input-card">
                <input type="text" name="username" 
                value="<?php echo $username; ?>" 
                placeholder="Masukkan Nama Pengguna" required> 
            </div>

            <div class="input-card">
                <input type="password" name="password" placeholder="Masukkan password" required>
            </div>

            <?php if($error != ""): ?>
                <div class="error-box">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <label>
                <input type="checkbox" name="remember"> Remember Me
            </label>
 
            <button type="submit" name="login">Login</button>
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
        <a href="Resgister.php">Apakah Anda belum memiliki akun? Daftar disini</a>
    </div>
</body>

</html>