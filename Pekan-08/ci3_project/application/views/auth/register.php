<!DOCTYPE html>
<html lang="in">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pendaftaran - NaviBiz</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            min-height: 100vh;
            background-color: #ebfbfa;
            background-image:
                radial-gradient(circle at 10% 100%, rgba(5, 12, 87, 0.607), rgba(70, 79, 184, 0.281), transparent 40%),
                radial-gradient(circle at 100% 10%, rgba(60, 252, 242, 0.42), rgba(207, 244, 248, 0.756), transparent 60%);
            background-repeat: no-repeat;
            background-attachment: fixed;
        }

        .form-container { width: 100%; display: flex; justify-content: center; margin-top: 60px; }
        .form-box {
            width: 100%; max-width: 420px; background: #ffffff; border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12); padding: 24px;
            display: flex; flex-direction: column; gap: 15px;
        }

        .input-card {
            background: #fdfdfd; border: 1px solid #ddd; padding: 10px 12px; border-radius: 10px;
        }

        .input-card input {
            width: 100%; padding: 6px 0; border: none; outline: none; font-size: 15px; background: transparent;
        }

        button {
            padding: 12px; background: #956df3; color: #fff; border: none;
            border-radius: 999px; cursor: pointer; width: 100%; font-size: 15px;
        }

        button:hover { background: #7944f5; }

        header { margin-top: 30px; text-align: center; }

        .alert-error {
            background-color: #ffebee; color: #c62828; padding: 10px; 
            border-radius: 8px; text-align: center; font-size: 14px; margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <header>
        <h1 style="color:#956df3;">NaviBiz</h1>
        <h2 style="color:#956df3;">Pendaftaran / Registration</h2>
    </header>

    <div class="form-container">
        <?php echo form_open('auth/register', ['class' => 'form-box']); ?>

            <?php if($this->session->flashdata('error')): ?>
                <div class="alert-error">
                    <?php echo $this->session->flashdata('error'); ?>
                </div>
            <?php endif; ?>

            <div class="input-card">
                <input type="text" name="nama" placeholder="Masukkan nama lengkap" required>
            </div>

            <div class="input-card">
                <input type="email" name="email" placeholder="Masukkan email (contoh@gmail.com)" required>
            </div>

            <div class="input-card">
                <input type="text" name="username" placeholder="Buat Nama Pengguna" required>
            </div>

            <div class="input-card">
                <input type="password" name="password" placeholder="Buat password" required>
            </div>

            <div class="input-card">
                <input type="text" name="telepon" placeholder="Masukkan Nomor Telepon (08XXXXXXXXXX)" required>
            </div>

            <div class="input-card">
                <input type="text" name="alamat" placeholder="Masukkan alamat lengkap" required>
            </div>

            <button type="submit">Daftar</button>

            <div class="login-link" style="text-align:center; margin-top:20px;">
                <a href="<?php echo base_url('index.php/auth/login'); ?>" style="color: #6a0dad; text-decoration: none;">
                    Apakah Anda sudah memiliki akun? Login disini
                </a>
            </div>
        <?php echo form_close(); ?>
    </div>
</body>
</html>