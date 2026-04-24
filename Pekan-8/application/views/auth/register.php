<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? html_escape($title) : 'Sign Up'; ?> - CyberVault</title>
    <style>
        body {
            font-family: sans-serif;
            background: #0a0a0f;
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        .box {
            background: rgba(255,255,255,0.05);
            padding: 30px;
            border-radius: 12px;
            border: 1px solid #00d4ff;
            width: 300px;
            box-shadow: 0 0 25px rgba(0, 212, 255, 0.12);
        }

        input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            background: #1a1a2e;
            border: 1px solid #333;
            color: #fff;
            border-radius: 5px;
            box-sizing: border-box;
        }

        input:focus {
            outline: none;
            border-color: #00d4ff;
            box-shadow: 0 0 8px rgba(0, 212, 255, 0.2);
        }

        button {
            width: 100%;
            padding: 10px;
            background: #00d4ff;
            border: none;
            font-weight: bold;
            cursor: pointer;
            border-radius: 5px;
            color: #000;
        }

        .error {
            background: rgba(255, 77, 77, 0.12);
            border: 1px solid rgba(255, 77, 77, 0.3);
            color: #ffb3b3;
            border-radius: 6px;
            padding: 10px 12px;
            margin-bottom: 14px;
            font-size: 13px;
        }

        .hint {
            text-align: center;
            font-size: 12px;
            margin-top: 20px;
            color: #888;
        }

        .hint a {
            color: #00d4ff;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="box">
        <h1 style="text-align:center; color:#00d4ff;">CyberVault</h1>
        <h2 style="text-align:center; color:#00d4ff;">Sign Up</h2>

        <?php if ($this->session->flashdata('error')): ?>
            <div class="error"><?php echo strip_tags($this->session->flashdata('error')); ?></div>
        <?php endif; ?>

        <form action="<?php echo site_url('register/store'); ?>" method="POST">
            <input type="text" name="nama" placeholder="Nama Lengkap" required value="<?php echo set_value('nama'); ?>">
            <input type="email" name="email" placeholder="Email" required value="<?php echo set_value('email'); ?>">
            <input type="password" name="password" placeholder="Password" required>

            <button type="submit" name="daftar">Daftar Akun</button>

            <p class="hint">
                Sudah punya akun? <a href="<?php echo site_url('login'); ?>">Login</a>
            </p>
        </form>
    </div>
</body>
</html>
