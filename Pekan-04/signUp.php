<?php
session_start();
// HAPUS tanda // di bawah ini agar file koneksi database terpanggil
require 'config.php';

$pesan = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Enkripsi password

    // HAPUS tanda /* dan */ agar logika database berjalan
    $cek = $conn->query("SELECT * FROM users WHERE username='$username' OR email='$email'");
    if ($cek->num_rows > 0) {
        $pesan = "<p style='color:red; text-align:center; font-size:14px;'>Username atau Email sudah terdaftar!</p>";
    } else {
        $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";
        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Registrasi berhasil! Silakan login.'); window.location.href='login.php';</script>";
            exit;
        } else {
            $pesan = "<p style='color:red; text-align:center; font-size:14px;'>Error: " . $conn->error . "</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up Page - EcoTaste</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        /* Mengatur ulang margin dan padding default */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            /* Gradient hijau sesuai gambar */
            background: linear-gradient(135deg, #5cb85c 0%, #8bc34a 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
        }

        /* Styling Logo di Kiri Atas */
        .logo {
            align-self: flex-start;
            padding: 25px 35px;
            font-size: 24px;
            font-weight: 700;
            color: white;
            display: flex;
            align-items: center;
            gap: 8px;
            width: 100%;
            position: absolute;
            top: 0;
            left: 0;
        }

        .signup-container {
            flex-grow: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            padding: 20px;
        }

        /* Kartu Form Putih */
        .signup-box {
            background-color: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            width: 100%;
            max-width: 400px;
        }

        h2 {
            color: #5cb85c;
            font-size: 28px;
            margin-bottom: 25px;
            font-weight: 600;
        }

        .input-group {
            margin-bottom: 20px;
            position: relative;
        }

        /* Ikon di dalam input */
        .input-group i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #888;
            font-size: 14px;
        }

        /* Kolom Input */
        .input-field {
            width: 100%;
            padding: 12px 15px 12px 40px;
            border: 1px solid #ddd;
            border-radius: 25px; /* Membuat bentuk pil */
            font-size: 14px;
            outline: none;
            transition: border-color 0.3s;
        }

        .input-field:focus {
            border-color: #5cb85c;
        }

        .input-field::placeholder {
            color: #aaa;
        }

        /* Tombol Sign Up */
        .signup-button {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 25px;
            background-color: #5cb85c;
            color: white;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 5px;
            box-shadow: 0 4px 10px rgba(92, 184, 92, 0.3);
            transition: background 0.3s, transform 0.1s;
        }

        .signup-button:hover {
            background-color: #4cae4c;
            transform: translateY(-1px);
        }

        /* Garis Pemisah "or" */
        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 25px 0;
            color: #999;
            font-size: 12px;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #eee;
        }

        .divider:not(:empty)::before {
            margin-right: .5em;
        }

        .divider:not(:empty)::after {
            margin-left: .5em;
        }

        /* Tombol Sosial Media */
        .social-login {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 25px;
        }

        .social-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            border: 1px solid #ddd;
            background: white;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.3s;
        }

        .social-btn:hover {
            background: #f5f5f5;
        }

        /* Warna Ikon Sosial */
        .fa-google { color: #DB4437; }
        .fa-facebook-f { color: #1877F2; }

        /* Tautan Login di Bawah */
        .login-link {
            text-align: center;
            font-size: 13px;
            color: #666;
        }

        .login-link a {
            color: #5cb85c;
            text-decoration: none;
            font-weight: 700;
        }

        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="logo">
        <i class="fas fa-leaf"></i> EcoTaste
    </div>

    <div class="signup-container">
        <div class="signup-box">
            <h2>Sign Up</h2>
            
            <?php echo $pesan; ?>
            
            <form action="signUp.php" method="POST">
                <div class="input-group">
                    <i class="fas fa-user"></i>
                    <input type="text" name="username" class="input-field" placeholder="Username" required>
                </div>
                <div class="input-group">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="email" class="input-field" placeholder="E-mail" required>
                </div>
                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" class="input-field" placeholder="Password" required>
                </div>
                <button type="submit" class="signup-button">Sign Up</button>
            </form>

            <div class="divider">or</div>

            <div class="social-login">
                <a href="#" class="social-btn">
                    <i class="fab fa-google"></i>
                </a>
                <a href="#" class="social-btn">
                    <i class="fab fa-facebook-f"></i>
                </a>
            </div>

            <div class="login-link">
                Already have account? <a href="login.php">Login</a>
            </div>

        </div>
    </div>

</body>
</html>