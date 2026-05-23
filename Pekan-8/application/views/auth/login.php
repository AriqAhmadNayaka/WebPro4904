<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Aplikasi</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #eef6ff, #d6eadf);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card {
            width: 100%;
            max-width: 420px;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 18px 45px rgba(17, 24, 39, 0.12);
            padding: 32px;
            box-sizing: border-box;
        }
        h1 {
            margin: 0 0 8px;
            font-size: 28px;
            color: #17324d;
        }
        p {
            color: #5a6a7a;
            margin-bottom: 24px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 700;
            color: #17324d;
        }
        input {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #ccd5df;
            border-radius: 10px;
            margin-bottom: 16px;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 12px;
            border: 0;
            border-radius: 10px;
            background: #1f7a4f;
            color: #fff;
            font-weight: 700;
            cursor: pointer;
        }
        .alert {
            padding: 12px 14px;
            border-radius: 10px;
            margin-bottom: 16px;
            font-size: 14px;
        }
        .alert-error {
            background: #ffe4e6;
            color: #9f1239;
        }
        .demo {
            margin-top: 16px;
            font-size: 13px;
            color: #52606d;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="card">
        <h1>Login</h1>

        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-error"><?php echo $this->session->flashdata('error'); ?></div>
        <?php endif; ?>

        <?php echo form_open('login'); ?>
            <label for="username">Username</label>
            <input type="text" id="username" name="username" value="<?php echo set_value('username'); ?>" placeholder="Masukkan username">

            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Masukkan password">

            <button type="submit">Login</button>
        <?php echo form_close(); ?>

</body>
</html>
