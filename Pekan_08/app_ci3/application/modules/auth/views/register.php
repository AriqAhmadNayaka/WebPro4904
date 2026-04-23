<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi NaviBiz</title>
    <style>
        * { box-sizing: border-box; }
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
        .register-wrap { width: 100%; max-width: 470px; }
        .brand { text-align: center; margin-bottom: 18px; }
        .brand h1, .brand h2 { margin: 0; }
        .brand h1 { color: #956df3; font-size: 38px; margin-bottom: 6px; }
        .brand h2 { color: #956df3; font-size: 22px; }
        .register-card {
            background: #ffffff;
            border-radius: 18px;
            padding: 24px;
            box-shadow: 0 16px 40px rgba(15, 23, 42, 0.14);
        }
        .intro { margin: 0 0 18px; color: #475569; text-align: center; }
        label { display: block; margin-bottom: 6px; font-weight: 700; }
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
        input, textarea {
            width: 100%;
            padding: 4px 0;
            border: 0;
            outline: none;
            background: transparent;
            font-family: inherit;
            resize: vertical;
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
        .alert.success { background: #dcfce7; color: #166534; }
        .alert.error { background: #fee2e2; color: #b91c1c; }
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
    <div class="register-wrap">
        <div class="brand">
            <h1>NaviBiz</h1>
            <h2>Pendaftaran / Registration</h2>
        </div>
        <form class="register-card" method="post" action="<?php echo site_url('register'); ?>">
            <p class="intro">Buat akun baru untuk masuk ke dashboard dan mengelola data laporan.</p>
            <?php if (!empty($message)) : ?>
                <div class="alert <?php echo html_escape($message['type']); ?>">
                    <?php echo html_escape($message['text']); ?>
                </div>
            <?php endif; ?>
            <label for="nama">Nama Lengkap</label>
            <div class="input-card"><input id="nama" type="text" name="nama" placeholder="Masukkan nama lengkap" required></div>
            <label for="email">Email</label>
            <div class="input-card"><input id="email" type="email" name="email" placeholder="Masukkan email" required></div>
            <label for="username">Username</label>
            <div class="input-card"><input id="username" type="text" name="username" placeholder="Buat nama pengguna" required></div>
            <label for="password">Password</label>
            <div class="input-card"><input id="password" type="password" name="password" placeholder="Buat password minimal 6 karakter" required></div>
            <label for="telepon">Telepon</label>
            <div class="input-card"><input id="telepon" type="text" name="telepon" placeholder="Masukkan nomor telepon" required></div>
            <label for="alamat">Alamat</label>
            <div class="input-card"><textarea id="alamat" name="alamat" rows="3" placeholder="Masukkan alamat lengkap" required></textarea></div>
            <button type="submit">Daftar</button>
            <div class="switch-link">Sudah punya akun? <a href="<?php echo site_url('login'); ?>">Login di sini</a></div>
        </form>
    </div>
</body>
</html>
