<?php
session_start();

if(isset($_POST['login_btn'])){// Cek apakah tombol login ditekan
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Cek sesuai instruksi: admin & 123456
    if($username == "admin" && $password == "123456"){
        $_SESSION['login'] = true;
        $_SESSION['user'] = $username;
        header("Location: homepage.php"); // Pindah ke halaman desain EcoTaste
        exit;
    } else {
        $error = "Login gagal. Username atau password salah!";// Pesan error jika login gagal
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login EcoTaste</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
       
        :root {
            --hijau-primer: #4CAF50;
            --hijau-sekunder: #8BC34A;
            --hijau-gelap: #2E7D32;
            --putih: #FFFFFF;
            --abu-teks: #555;
            --background-start: #66BB6A;
            --background-end: #8BC34A;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            background: linear-gradient(135deg, var(--background-start), var(--background-end));
            min-height: 100vh;
        }

        .logo {
            align-self: flex-start;
            padding: 20px;
            font-size: 32px;
            font-weight: bold;
            color: var(--putih);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .login-container {
            flex-grow: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
        }

        .login-box {
            background-color: var(--putih);
            padding: 45px;
            border-radius: 20px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
            max-width: 400px;
            width: 90%;
            text-align: center;
        }

        h2 { color: var(--hijau-primer); font-size: 32px; margin-bottom: 25px; text-align: left; }

        .input-group { margin-bottom: 20px; position: relative; text-align: left; }
        .input-group i { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: var(--hijau-sekunder); }

        .input-field {
            width: 100%;
            padding: 12px 15px 12px 45px;
            border: 1px solid #ccc;
            border-radius: 30px;
            box-sizing: border-box;
            outline: none;
        }

        .login-button {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 30px;
            background: linear-gradient(to right, #66BB6A, var(--hijau-primer));
            color: white;
            font-size: 18px;
            font-weight: 700;
            cursor: pointer;
        }

        .error-msg { color: #d32f2f; background: #ffcdd2; padding: 10px; border-radius: 10px; margin-bottom: 20px; font-size: 14px; }
    </style>
</head>
<body>

    <div class="logo"><i class="fas fa-leaf"></i> EcoTaste</div>

    <div class="login-container">
        <div class="login-box">
            <h2>Sign In</h2>

            <?php if(isset($error)): ?>
                <div class="error-msg"><?php echo $error; ?></div>
            <?php endif; ?>

            <form action="" method="POST">
                <div class="input-group">
                    <i class="fas fa-user"></i>
                    <input type="text" name="username" class="input-field" placeholder="Username" required>
                </div>

                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" class="input-field" placeholder="Password" required>
                </div>

                <button type="submit" name="login_btn" class="login-button">Login</button>
            </form>

            <div style="margin-top:20px; font-size:14px; color:#666;">
                Belum punya akun? <a href="#" style="color:var(--hijau-primer); font-weight:bold; text-decoration:none;">Sign Up</a>
            </div>
        </div>
    </div>

</body>
</html>