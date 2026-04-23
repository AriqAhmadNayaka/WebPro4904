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
    overflow: hidden;
}
.image-panel .bg {
    position: absolute; inset: 0; background-size: cover; background-position: center;
    transition: opacity 1s ease;
}
.image-panel .overlay {
    position: absolute; inset: 0;
    background: linear-gradient(to top, rgba(0,0,0,0.7), rgba(0,0,0,0.1));
    z-index: 1;
}
.image-content { position: relative; z-index: 2; padding: 28px; color: white; }
.image-content h3 { font-size: 20px; font-weight: 700; margin-bottom: 6px; }
.image-content p  { font-size: 13px; opacity: 0.85; }
.login-panel { width: 55%; padding: 40px 44px; display: flex; flex-direction: column; justify-content: center; }
.login-panel h1 { font-size: 28px; color: #0f4c3a; margin-bottom: 6px; }
.login-panel .sub { color: #888; font-size: 14px; margin-bottom: 28px; }
.alert { padding: 10px 14px; border-radius: 8px; margin-bottom: 16px; font-size: 13px; }
.alert-error   { background: #fdecea; color: #c0392b; border-left: 3px solid #e74c3c; }
.alert-success { background: #eafaf1; color: #1e8449; border-left: 3px solid #28a745; }
.field { margin-bottom: 16px; }
.field label { display: block; font-size: 13px; font-weight: 600; color: #444; margin-bottom: 6px; }
.field input {
    width: 100%; padding: 11px 14px; border: 1.5px solid #ddd;
    border-radius: 8px; font-size: 14px; outline: none; transition: border-color 0.2s;
}
.field input:focus { border-color: #00b894; }
.pass-wrap { position: relative; }
.pass-wrap input { padding-right: 40px; }
.toggle-pass { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #aaa; }
.btn-login {
    width: 100%; padding: 12px; background: #0f4c3a; color: white;
    border: none; border-radius: 8px; font-size: 15px; font-weight: 600;
    cursor: pointer; margin-top: 6px; transition: background 0.2s;
}
.btn-login:hover { background: #0a3328; }
.register-link { text-align: center; margin-top: 18px; font-size: 13px; color: #666; }
.register-link a { color: #00b894; font-weight: 600; text-decoration: none; }
</style>
</head>
<body>
<div class="outer-container">
    <div class="image-panel">
        <div class="bg" id="slideBg"></div>
        <div class="overlay"></div>
        <div class="image-content">
            <h3 id="slideTitle">Jelajahi Wisata Bandung</h3>
            <p id="slideDesc">Temukan keindahan alam dan budaya Kota Kembang</p>
        </div>
    </div>
    <div class="login-panel">
        <h1>Selamat Datang</h1>
        <p class="sub">Masukkan email dan password untuk masuk</p>

        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?php echo $this->session->flashdata('error'); ?></div>
        <?php endif; ?>
        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?php echo $this->session->flashdata('success'); ?></div>
        <?php endif; ?>

        <?php echo form_open('Auth/proses_login'); ?>
            <div class="field">
                <label>Email</label>
                <input type="email" name="email" placeholder="email@contoh.com" required>
            </div>
            <div class="field">
                <label>Password</label>
                <div class="pass-wrap">
                    <input type="password" name="password" id="passInput" placeholder="Password" required>
                    <i class="fa fa-eye toggle-pass" id="togglePass"></i>
                </div>
            </div>
            <button type="submit" class="btn-login">Masuk</button>
        <?php echo form_close(); ?>

        <div class="register-link">
            Belum punya akun? <a href="<?php echo base_url('register'); ?>">Daftar sekarang</a>
        </div>
    </div>
</div>
<script>
const slides = [
    { img: 'bdg1.jpg', title: 'Jelajahi Warisan Bandung', desc: 'Temukan sejarah dan budaya Kota Kembang' },
    { img: 'bdg2.jpg', title: 'Asia Afrika',              desc: 'Kawasan sejarah terkenal di Bandung' },
    { img: 'bdg3.jpg', title: 'Jalan Braga',              desc: 'Tempat wisata klasik penuh sejarah' },
];
let i = 0;
function slide() {
    const s = slides[i];
    document.getElementById('slideBg').style.backgroundImage   = `url(${s.img})`;
    document.getElementById('slideTitle').textContent = s.title;
    document.getElementById('slideDesc').textContent  = s.desc;
    i = (i + 1) % slides.length;
}
slide(); setInterval(slide, 3500);

document.getElementById('togglePass').onclick = function() {
    const p = document.getElementById('passInput');
    p.type = p.type === 'password' ? 'text' : 'password';
    this.classList.toggle('fa-eye'); this.classList.toggle('fa-eye-slash');
};
</script>
</body>
</html>
