<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Nuids</title>
    <link rel="stylesheet" href="<?= base_url('global.css') ?>">
    <link rel="stylesheet" href="<?= base_url('login.css') ?>">
</head>

<body>
    <!-- Halaman login: hanya tampilan + form, proses validasi ada di Auth controller -->
    <div class="container-login">
        <h2 class="greeting">Hi! Selamat Datang Kembali di Nuids.</h2>

        <div class="acrylic-card">
            <div class="logo-container">
                <img src="<?= base_url('logo.png') ?>" alt="" width="50px">
            </div>

            <!-- Pesan error dikirim dari controller jika login gagal -->
            <?php if (!empty($error)): ?>
                <div style="color: red; margin-bottom: 15px; text-align: center; font-weight: bold;"><?= $error ?></div>
            <?php endif; ?>

            <!-- Action form mengarah ke route login (Auth::login) -->
            <form action="<?= base_url('index.php?c=auth&m=login') ?>" method="POST">
                <div class="form-group">
                    <input type="email" name="email" class="custom-input input-teal" placeholder="Email" required>
                </div>
                <div class="form-group">
                    <input type="password" name="password" class="custom-input input-pink" placeholder="Password" required>
                </div>

                <button type="submit" class="btn-login">
                    <span class="btn-text">Login</span>
                </button>
            </form>
            <a href="<?= base_url('index.php?c=auth&m=register') ?>">Belum punya akun? Daftar sekarang</a>
        </div>
    </div>
    <script src="<?= base_url('main.js') ?>"></script>
</body>

</html>
