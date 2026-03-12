<?php session_start(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>LifeTrack - Registrasi</title>
    <link rel="stylesheet" href="style.css"> <?php //buat ngelink css?>
</head>
<body>
<div class="auth-wrapper">
    <div class="auth-card">
        <h2>Buat Akun</h2>
        <p>Daftar untuk mulai menggunakan LifeTrack</p> 

        <form method="POST" action="prosesregistrasi.php"> <?php //form menggunakan method post, data dikirim ke prosesregistrasi.php?>
            <div class="form-group">
                <label>Daftar sebagai</label>
                <select name="role" required>  <?php //form required wajib dipilih sebelum form bisa dikirim?>
                    <option value="">-- Pilih Peran --</option>
                    <option value="pasien">Pasien</option>
                    <option value="tenaga_medis">Tenaga Medis</option>
                </select>
            </div>

            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama" placeholder="Masukkan nama lengkap" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" placeholder="Masukkan email" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Minimal 6 karakter" minlength="6" required>
            </div>
            <button type="submit" class="btn">Buat Akun</button>
        </form>
        
        <div class="auth-link"> <?php //Link ke halaman login jika sudah punya akun?>
            Sudah punya akun? <a href="login.php">Login</a>
        </div>
    </div>
</div>
</body>
</html>