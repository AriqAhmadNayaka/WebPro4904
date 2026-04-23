<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo $title; ?></title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
* { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
body {
    min-height: 100vh; display: flex; justify-content: center; align-items: center;
    background: radial-gradient(circle at 60%, #9dc599, #0f4c3a);
}
.outer-container {
    display: flex; width: 860px; min-height: 520px;
    border-radius: 20px; overflow: hidden; background: white;
    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
}
.image-panel {
    width: 45%; background: #0f4c3a; position: relative;
    display: flex; flex-direction: column; justify-content: flex-end;
}
.image-panel .bg { position: absolute; inset: 0; background-size: cover; background-position: center; }
.image-panel .overlay {
    position: absolute; inset: 0;
    background: linear-gradient(to top, rgba(0,0,0,0.65), rgba(0,0,0,0.1)); z-index: 1;
}
.image-content { position: relative; z-index: 2; padding: 28px; color: white; }
.image-content h3 { font-size: 20px; font-weight: 700; margin-bottom: 6px; }
.image-content p  { font-size: 13px; opacity: 0.85; }
.register-panel { width: 55%; padding: 36px 44px; display: flex; flex-direction: column; justify-content: center; }
.register-panel h1 { font-size: 26px; color: #0f4c3a; margin-bottom: 4px; }
.register-panel .sub { color: #888; font-size: 13px; margin-bottom: 22px; }
.alert { padding: 10px 14px; border-radius: 8px; margin-bottom: 14px; font-size: 13px; }
.alert-error { background: #fdecea; color: #c0392b; border-left: 3px solid #e74c3c; }
.field { margin-bottom: 14px; }
.field label { display: block; font-size: 13px; font-weight: 600; color: #444; margin-bottom: 5px; }
.field input {
    width: 100%; padding: 10px 14px; border: 1.5px solid #ddd;
    border-radius: 8px; font-size: 14px; outline: none; transition: border-color 0.2s;
}
.field input:focus { border-color: #00b894; }
.pass-wrap { position: relative; }
.pass-wrap input { padding-right: 40px; }
.toggle-pass { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #aaa; }
.btn-register {
    width: 100%; padding: 12px; background: #0f4c3a; color: white;
    border: none; border-radius: 8px; font-size: 15px; font-weight: 600;
    cursor: pointer; margin-top: 4px; transition: background 0.2s;
}
.btn-register:hover { background: #0a3328; }
.login-link { text-align: center; margin-top: 16px; font-size: 13px; color: #666; }
.login-link a { color: #00b894; font-weight: 600; text-decoration: none; }
</style>
</head>
<body>
<div class="outer-container">
    <div class="image-panel">
        <div class="bg" style="background-image: url('bdg1.jpg');"></div>
        <div class="overlay"></div>
        <div class="image-content">
            <h3>Bergabunglah Bersama Kami</h3>
            <p>Simpan destinasi impianmu di Bandung</p>
        </div>
    </div>
    <div class="register-panel">
        <h1>Buat Akun</h1>
        <p class="sub">Daftarkan diri untuk mulai menjelajah</p>

        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?php echo $this->session->flashdata('error'); ?></div>
        <?php endif; ?>

        <?php echo form_open('Auth/proses_register'); ?>
            <div class="field">
                <label>Nama Lengkap</label>
                <input type="text" name="nama" placeholder="Nama kamu" required>
            </div>
            <div class="field">
                <label>Email</label>
                <input type="email" name="email" placeholder="email@contoh.com" required>
            </div>
            <div class="field">
                <label>Password</label>
                <div class="pass-wrap">
                    <input type="password" name="password" id="passInput" placeholder="Minimal 6 karakter" required>
                    <i class="fa fa-eye toggle-pass" id="togglePass"></i>
                </div>
            </div>
            <button type="submit" class="btn-register">Daftar Sekarang</button>
        <?php echo form_close(); ?>

        <div class="login-link">
            Sudah punya akun? <a href="<?php echo base_url('login'); ?>">Masuk di sini</a>
        </div>
    </div>
</div>
<script>
document.getElementById('togglePass').onclick = function() {
    const p = document.getElementById('passInput');
    p.type = p.type === 'password' ? 'text' : 'password';
    this.classList.toggle('fa-eye'); this.classList.toggle('fa-eye-slash');
};
</script>
</body>
</html>
