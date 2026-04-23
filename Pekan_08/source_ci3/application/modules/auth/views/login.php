<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login NaviBiz</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            font-family: Arial, sans-serif;
            background-color: #ebfbfa;
            background-image:
                radial-gradient(circle at 10% 100%, rgba(5, 12, 87, 0.6), rgba(70, 79, 184, 0.28), transparent 40%),
                radial-gradient(circle at 100% 10%, rgba(60, 252, 242, 0.42), rgba(207, 244, 248, 0.75), transparent 60%);
            background-repeat: no-repeat;
            background-attachment: fixed;
            color: #0f172a;
        }

        .login-wrap {
            width: 100%;
            max-width: 430px;
        }

        .brand {
            text-align: center;
            margin-bottom: 18px;
        }

        .brand h1,
        .brand h2 {
            margin: 0;
        }

        .brand h1 {
            color: #956df3;
            font-size: 38px;
            margin-bottom: 6px;
        }

        .brand h2 {
            color: #956df3;
            font-size: 22px;
        }

        .login-card {
            width: 100%;
            background: #ffffff;
            border-radius: 18px;
            padding: 24px;
            box-shadow: 0 16px 40px rgba(15, 23, 42, 0.14);
        }

        .intro {
            margin: 0 0 18px;
            color: #475569;
            text-align: center;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: 700;
        }

        .input-card {
            background: #fdfdfd;
            border: 1px solid #ddd;
            padding: 10px 12px;
            border-radius: 10px;
            margin-bottom: 14px;
            transition: 0.2s ease;
        }

        .input-card:focus-within {
            border-color: #3a71ff;
            box-shadow: 0 3px 10px rgba(149, 109, 243, 0.45);
            transform: translateY(-1px);
        }

        input {
            width: 100%;
            padding: 4px 0;
            border: 0;
            outline: none;
            background: transparent;
        }

        button {
            width: 100%;
            border: 0;
            border-radius: 999px;
            background: #956df3;
            color: #ffffff;
            padding: 13px 18px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
        }

        .alert {
            padding: 12px 14px;
            border-radius: 12px;
            margin-bottom: 16px;
            font-size: 14px;
        }

        .alert.success {
            background: #dcfce7;
            color: #166534;
        }

        .alert.error {
            background: #fee2e2;
            color: #b91c1c;
        }

        .hint {
            margin-top: 16px;
            font-size: 13px;
            color: #6b7280;
            text-align: center;
        }

        .switch-link {
            margin-top: 18px;
            text-align: center;
            font-size: 14px;
            color: #475569;
        }

        .switch-link a {
            color: #6a0dad;
            text-decoration: none;
            font-weight: 700;
        }
    </style>
</head>
<body>
    <div class="login-wrap">
        <div class="brand">
            <h1>NaviBiz</h1>
            <h2>Masuk / Login</h2>
        </div>

        <form class="login-card" method="post" action="<?php echo site_url('login'); ?>">
            <p class="intro">Masuk terlebih dahulu untuk membuka dashboard dan mengelola data laporan.</p>

            <?php if (!empty($message)) : ?>
                <div class="alert <?php echo html_escape($message['type']); ?>">
                    <?php echo html_escape($message['text']); ?>
                </div>
            <?php endif; ?>

            <label for="username">Username</label>
            <div class="input-card">
                <input id="username" type="text" name="username" placeholder="Masukkan Nama Pengguna" required>
            </div>

            <label for="password">Password</label>
            <div class="input-card">
                <input id="password" type="password" name="password" placeholder="Masukkan Password" required>
            </div>

            <button type="submit">Login</button>
            <div class="hint">Login memakai data pada tabel <strong>users</strong>. Contoh seed: <strong>admin</strong> / <strong>12345</strong></div>
            <div class="switch-link">Belum punya akun? <a href="<?php echo site_url('register'); ?>">Daftar di sini</a></div>
        </form>
    </div>
</body>
</html>
