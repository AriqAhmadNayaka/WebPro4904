<?php
// Memulai sesi untuk melacak status login pengguna (Syarat Tugas)
session_start();

// Mengimpor file koneksi agar terhubung ke database NaviBiz_db
require 'koneksi.php';

// Mengecek apakah form telah disubmit menggunakan metode POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Mengambil data username dan password dari input form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Mencegah SQL Injection dengan menggunakan Prepared Statement
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username); // 's' berarti parameter berupa string
    $stmt->execute();
    $result = $stmt->get_result();

    // Mengecek apakah username ditemukan di database
    if ($row = $result->fetch_assoc()) {
        // Memverifikasi apakah password yang diinput cocok dengan yang ada di database
        if (password_verify($password, $row['password'])) {
            
            // --- IMPLEMENTASI SESSION ---
            // Jika cocok, simpan username ke dalam session agar diakses di halaman lain
            $_SESSION['username'] = $username;
            
            // --- IMPLEMENTASI COOKIE (Syarat Tugas Modul 5) ---
            // Mengecek apakah checkbox "Ingat Saya" (name='remember') dicentang oleh user
            if (isset($_POST['remember'])) {
                // Membuat cookie bernama 'remember_user' berisi username.
                // time() + (86400 * 30) mengatur masa aktif cookie selama 30 hari.
                setcookie('remember_user', $username, time() + (86400 * 30), "/"); 
            } else {
                // Jika tidak dicentang, hapus cookie dengan menyetel waktunya mundur (-3600 detik)
                setcookie('remember_user', "", time() - 3600, "/"); 
            }

            // Mengarahkan pengguna ke halaman dompet.php setelah login dan cookie diset
            header("Location: dompet.php");
            exit();
        } else {
            // Menampilkan pesan error pop-up jika password salah
            echo "<script>alert('Password salah!');</script>";
        }
    } else {
        // Menampilkan pesan error pop-up jika username tidak ada di database
        echo "<script>alert('Username tidak ditemukan!');</script>";
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
        <form class="form-box" action="" method="post">
            <div class="input-card">
                <?php $saved_username = isset($_COOKIE['remember_user']) ? $_COOKIE['remember_user'] : ''; ?>
                <input type="text" name="username" placeholder="Masukkan Nama Pengguna" value="<?php echo $saved_username; ?>" required>
            </div>
            
            <div class="input-card">
                <input type="password" name="password" placeholder="Masukkan password" required>
            </div>
            
            <div style="text-align: left; margin-bottom: 15px;">
                <input type="checkbox" name="remember" id="remember">
                <label for="remember" style="color: grey; font-size: 14px;">Ingat Saya</label>
            </div>

            <button type="submit">Login</button>
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
        <a href="menupendaftaran.html">Apakah Anda belum memiliki akun? Daftar disini</a>
    </div>
</body>

</html>