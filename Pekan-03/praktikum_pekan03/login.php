<?php
session_start();

// ambil email & password dari session 
$prefill_email = $_SESSION["akun"]["email"]    ?? "";
$prefill_pass  = $_SESSION["akun"]["password"] ?? "";
$from_register = isset($_GET["from"]) && $_GET["from"] == "register";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>LifeTrack - Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="auth-wrapper">
    <div class="auth-card">
        <h2>Selamat Datang</h2>
        <p>Masuk untuk melanjutkan ke LifeTrack</p>

        <?php echo $from_register ? '<div class="alert-success">Akun berhasil dibuat! Langsung klik Login.</div>' : ''; ?>
        <?php echo isset($_GET["error"]) ? '<div style="background:#fee2e2;color:#991b1b;padding:11px 14px;border-radius:10px;font-size:13px;margin-bottom:16px;">' . $_GET["error"] . '</div>' : ''; ?>

        <form method="POST" action="proseslogin.php">
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email"
                       value="<?php echo htmlspecialchars($prefill_email); ?>"
                       placeholder="Masukkan email" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password"
                       value="<?php echo htmlspecialchars($prefill_pass); ?>"
                       placeholder="Masukkan password" required>
            </div>
            <button type="submit" class="btn">Login</button>
        </form>

        <div class="auth-link">
            Belum punya akun? <a href="registrasi.php">Buat Akun</a>
        </div>
    </div>
</div>
</body>
</html>
