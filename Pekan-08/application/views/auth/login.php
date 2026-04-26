<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Aplikasi</title>
    <style>
        /* Semua elemen pakai box-sizing ini biar ukuran lebih gampang diatur. */
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #a5b7c4;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }
        /* Card utama buat form login. */
        .login-box {
            width: 100%;
            max-width: 500px;
            background: #6f8892;
            border-radius: 28px;
            padding: 54px 50px 46px;
            box-shadow: 0 18px 38px rgba(67, 78, 86, 0.35);
        }
        h2 {
            margin: 0 0 34px;
            text-align: center;
            color: #ffffff;
            font-size: 28px;
            font-weight: 700;
        }
        .field {
            margin-bottom: 22px;
        }
        .field label {
            display: none;
        }
        input {
            width: 100%;
            border: none;
            outline: none;
            background: #efefef;
            color: #6b7280;
            padding: 18px 20px;
            border-radius: 999px;
            font-size: 17px;
        }
        input::placeholder {
            color: #7a7a7a;
        }
        
        .button-wrap {
            text-align: center;
            margin-top: 8px;
        }
        button {
            min-width: 240px;
            padding: 16px 28px;
            background: #ffffff;
            color: #000000;
            border: none;
            border-radius: 999px;
            font-size: 18px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(255, 255, 255, 0.15);
        }
        button:hover {
            background: #f4f4f4;
        }
        .alert {
            background: rgba(255, 237, 237, 0.95);
            color: #991b1b;
            padding: 12px 16px;
            border-radius: 16px;
            margin-bottom: 20px;
            text-align: center;
        }
        @media (max-width: 600px) {
            /* Pas dibuka di HP, jarak dan ukuran card diperkecil dikit. */
            .login-box {
                padding: 38px 24px 32px;
                border-radius: 22px;
            }
            h2 {
                font-size: 24px;
                margin-bottom: 28px;
            }
            button {
                width: 100%;
                min-width: 0;
            }
        }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>Login Aplikasi Desa</h2>
        <!-- Kalau login gagal, pesannya nongol di atas form -->
        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert"><?php echo $this->session->flashdata('error'); ?></div>
        <?php endif; ?>
        <!-- Form ini ngarah ke method auth/login -->
        <form action="<?php echo site_url('auth/login'); ?>" method="post">
            <div class="field">
                <label for="username">Username</label>
                <input id="username" type="text" name="username" placeholder="Username" required>
            </div>
            <div class="field">
                <label for="password">Password</label>
                <input id="password" type="password" name="password" placeholder="Password" required>
            </div>
            <div class="button-wrap">
                <button type="submit">Login</button>
            </div>
        </form>
    </div>
</body>
</html>
