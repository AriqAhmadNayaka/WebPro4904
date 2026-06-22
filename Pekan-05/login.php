<?php
session_start();
// HAPUS tanda // di bawah ini agar file koneksi database terpanggil
require 'config.php';

// Jika sudah login, langsung arahkan ke beranda
if (isset($_SESSION['isLoggedIn'])) {
    header("Location: beranda.php");
    exit;
}

$pesan = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // HAPUS tanda /* dan */ di sini agar logika pengecekan berjalan
    $result = $conn->query("SELECT * FROM users WHERE username='$username' OR email='$username'");
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Verifikasi password yang di-hash
        if (password_verify($password, $row['password'])) {
            $_SESSION['isLoggedIn'] = true;
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            
            header("Location: beranda.php");
            exit;
        } else {
            $pesan = "<p style='color:red; text-align:center; font-size:14px; margin-bottom:15px;'>Password salah!</p>";
        }
    } else {
        $pesan = "<p style='color:red; text-align:center; font-size:14px; margin-bottom:15px;'>Username tidak ditemukan!</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In Page - EcoTaste</title>
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

        .login-container {
            flex-grow: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            padding: 20px;
        }

        /* Kartu Form Putih */
        .login-box {
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

        /* Baris untuk Remember Me & Forgot Password */
        .options-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            font-size: 13px;
        }

        .remember-me {
            display: flex;
            align-items: center;
            color: #555;
            cursor: pointer;
        }

        .remember-me input {
            margin-right: 8px;
            cursor: pointer;
            accent-color: #5cb85c;
        }

        .forgot-password {
            color: #5cb85c;
            text-decoration: none;
            font-weight: 600;
        }

        .forgot-password:hover {
            text-decoration: underline;
        }

        /* Tombol Login */
        .login-button {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 25px;
            background-color: #5cb85c;
            color: white;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(92, 184, 92, 0.3);
            transition: background 0.3s, transform 0.1s;
        }

        .login-button:hover {
            background-color: #4cae4c;
            transform: translateY(-1px);
        }

        /* Tautan Sign Up di Bawah */
        .signup-text {
            text-align: center;
            font-size: 13px;
            color: #666;
            margin-top: 25px;
        }

        .signup-text a {
            color: #5cb85c;
            text-decoration: none;
            font-weight: 700;
        }

        .signup-text a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="logo">
        <i class="fas fa-leaf"></i> EcoTaste
    </div>

    <div class="login-container">
        <div class="login-box">
            <h2>Sign In</h2>
            
            <?php echo $pesan; ?>
            
            <form action="login.php" method="POST">
                <div class="input-group">
                    <i class="fas fa-user"></i>
                    <input type="text" name="username" class="input-field" placeholder="Username / E-mail" required>
                </div>
                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" class="input-field" placeholder="Password" required>
                </div>
                
                <div class="options-row">
                    <label class="remember-me">
                        <input type="checkbox" name="remember"> Remember me
                    </label>
                    <a href="#" class="forgot-password">Forgot password?</a>
                </div>

                <button type="submit" class="login-button">Login</button>
            </form>

            <div class="signup-text">
                Don't have account? <a href="signUp.php">Sign Up</a>
            </div>
        </div>
    </div>

</body>
</html>