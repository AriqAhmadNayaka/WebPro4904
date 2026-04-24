<div class="auth-wrap">
    <div class="auth-card card">
        <h2 style="margin-top:0;">Login EcoTaste</h2>
        <p>Masuk terlebih dahulu untuk membuka dashboard aplikasi.</p>

        <?php echo form_open('login'); ?>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" value="<?php echo set_value('username'); ?>" required>
                <?php echo form_error('username', '<div class="error-text">', '</div>'); ?>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
                <?php echo form_error('password', '<div class="error-text">', '</div>'); ?>
            </div>

            <button type="submit">Login</button>
        <?php echo form_close(); ?>
    </div>
</div>
