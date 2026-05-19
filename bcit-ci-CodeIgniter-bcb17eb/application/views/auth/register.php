<?php $page_title = 'Daftar Akun'; $this->load->view('templates/header'); ?>

<div class="container">
    <h2>Daftar Akun Baru</h2>

    <?php if ($error): ?>
        <p class="msg-error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <?php if ($sukses): ?>
        <p class="msg-sukses"><?= htmlspecialchars($sukses) ?></p>
    <?php endif; ?>

    <form method="post" action="<?= site_url('auth/register') ?>">
        <input type="text"
               name="username"
               placeholder="Username"
               required
               autocomplete="username">

        <input type="password"
               name="password"
               placeholder="Password"
               required
               autocomplete="new-password">

        <input type="password"
               name="confirm_password"
               placeholder="Ulangi Password"
               required
               autocomplete="new-password">

        <button type="submit" name="register">DAFTAR SEKARANG</button>

        <p>Sudah punya akun? <a href="<?= site_url('auth/login') ?>">Login di sini</a></p>
    </form>
</div>

</body>
</html>
