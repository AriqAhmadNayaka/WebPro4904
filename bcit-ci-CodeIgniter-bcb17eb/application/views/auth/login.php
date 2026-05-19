<?php $page_title = 'Login'; $this->load->view('templates/header'); ?>

<div class="container">
    <h2>Masuk</h2>

    <?php if ($error): ?>
        <p class="msg-error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="post" action="<?= site_url('auth/login') ?>">
        <input type="text"
               name="username"
               placeholder="Username"
               required
               autocomplete="username">

        <input type="password"
               name="password"
               placeholder="Password"
               required
               autocomplete="current-password">

        <button type="submit" name="login">Login</button>

        <p>Belum punya akun? <a href="<?= site_url('auth/register') ?>">Daftar di sini</a></p>
    </form>
</div>

</body>
</html>
