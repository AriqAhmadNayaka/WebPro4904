<?php require_once __DIR__ . '/partials/guest_guard.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Daftar - WeBandoo+</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link rel="stylesheet" href="assets/css/webandoo-enhancements.css">
<script src="https://mcp.figma.com/mcp/html-to-design/capture.js" async></script>

<style>
    :root {
        --color-primary-green: #4A8645;
        --color-white: #ffffff;
        --color-off-white: #f7f7f7;
        --color-text-dark: #333;
        --color-text-grey: #666;
        --shadow-deep: 0 20px 60px rgba(0, 0, 0, 0.3);
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: "Poppins", sans-serif;
    }

    body {
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        background-image: radial-gradient(#9dc599, #4A8645);
        padding: 28px;
    }

    .outer-container {
        display: flex;
        width: 980px;
        min-height: 620px;
        border-radius: 25px;
        overflow: hidden;
        box-shadow: var(--shadow-deep);
        background: var(--color-white);
    }

    .image-panel {
        width: 42%;
        position: relative;
        color: var(--color-white);
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        background: url('bdg6.jpg') center/cover;
    }

    .image-panel::before {
        content: "";
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.78), rgba(0,0,0,0.16));
    }

    .image-content {
        position: relative;
        z-index: 2;
        padding: 34px;
    }

    .image-content h3 {
        font-size: 26px;
        margin-bottom: 10px;
    }

    .image-content p {
        line-height: 1.7;
        font-size: 14px;
    }

    .benefit-list {
        margin-top: 22px;
        display: grid;
        gap: 10px;
    }

    .benefit-list span {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        font-size: 13px;
        font-weight: 600;
    }

    .register-container {
        width: 58%;
        padding: 38px 42px;
        background: var(--color-off-white);
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .logo-header {
        text-align: right;
        margin-bottom: 12px;
    }

    .logo-header span {
        font-size: 18px;
        font-weight: 700;
        color: var(--color-primary-green);
    }

    .greeting h1 {
        font-size: 32px;
        margin-bottom: 6px;
        color: var(--color-text-dark);
    }

    .greeting p {
        font-size: 14px;
        color: var(--color-text-grey);
        margin-bottom: 24px;
        line-height: 1.6;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
    }

    .input-box.full {
        grid-column: 1 / -1;
    }

    .input-box input,
    .input-box select {
        width: 100%;
        padding: 12px 14px;
        border: 1px solid #ddd;
        border-radius: 8px;
        outline: none;
        color: var(--color-text-dark);
        font-size: 14px;
        background: white;
        transition: border-color 0.3s, box-shadow 0.3s;
    }

    .input-box input:focus,
    .input-box select:focus {
        border-color: var(--color-primary-green);
        box-shadow: 0 0 5px rgba(74, 134, 69, 0.3);
    }

    .interest-row {
        grid-column: 1 / -1;
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin: 2px 0 4px;
    }

    .interest-row label {
        background: white;
        border: 1px solid #e2e8e2;
        border-radius: 999px;
        padding: 8px 11px;
        color: var(--color-text-dark);
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
    }

    .interest-row input {
        margin-right: 5px;
        accent-color: var(--color-primary-green);
    }

    .terms {
        grid-column: 1 / -1;
        font-size: 12px;
        color: var(--color-text-grey);
        line-height: 1.5;
        display: flex;
        gap: 9px;
        align-items: flex-start;
    }

    .terms input {
        margin-top: 3px;
        accent-color: var(--color-primary-green);
    }

    .btn {
        grid-column: 1 / -1;
        width: 100%;
        padding: 14px;
        border: none;
        border-radius: 8px;
        background: var(--color-primary-green);
        font-weight: 600;
        cursor: pointer;
        color: var(--color-white);
        text-decoration: none;
        display: block;
        text-align: center;
        transition: 0.3s ease;
        box-shadow: 0 4px 15px rgba(87, 95, 87, 0.5);
    }

    .btn:hover {
        background: #5e9d57;
        transform: translateY(-2px);
    }

    .login-link {
        margin-top: 16px;
        text-align: center;
        font-size: 14px;
    }

    .login-link a {
        color: var(--color-primary-green);
        font-weight: 700;
        text-decoration: none;
    }

    @media (max-width: 900px) {
        .outer-container {
            width: 100%;
            flex-direction: column;
        }
        .image-panel, .register-container {
            width: 100%;
        }
        .image-panel {
            min-height: 260px;
        }
        .form-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
</head>
<body class="login-page">
<div class="outer-container">
    <div class="image-panel">
        <div class="image-content">
            <h3>Mulai Eksplor Bandung Lebih Praktis</h3>
            <p>Buat akun untuk menyimpan wishlist, melihat rekomendasi sesuai minat, mengatur budget, dan menyusun rute eksplorasi Bandung.</p>
            <div class="benefit-list">
                <span><i class="fas fa-heart"></i> Simpan tempat favorit</span>
                <span><i class="fas fa-wallet"></i> Atur estimasi biaya</span>
                <span><i class="fas fa-route"></i> Rekomendasi rute dan itinerary</span>
                <span><i class="fas fa-star"></i> Beri review pengalaman</span>
            </div>
        </div>
    </div>

    <div class="register-container">
        <div class="logo-header">
            <span>WeBandoo+</span>
        </div>

        <div class="greeting">
            <h1>Buat Akun</h1>
            <p>Lengkapi data singkat agar rekomendasi wisata, kuliner, kafe, dan event Bandung lebih sesuai dengan kebutuhanmu.</p>
        </div>

        <form class="form-grid" data-register-form>
            <div class="input-box">
                <input type="text" name="nama_lengkap" placeholder="Nama Lengkap" required>
            </div>
            <div class="input-box">
                <input type="text" name="kota" placeholder="Kota / Domisili" required>
            </div>
            <div class="input-box full">
                <input type="email" name="email" placeholder="Email" required>
            </div>
            <div class="input-box">
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <div class="input-box">
                <input type="password" name="confirm_password" placeholder="Konfirmasi Password" required>
            </div>
            <div class="input-box full">
                <select name="kebutuhan_utama" required>
                    <option value="">Pilih kebutuhan utama</option>
                    <option>Wisata sejarah dan budaya</option>
                    <option>Kuliner murah dan populer</option>
                    <option>Kafe untuk nongkrong / kerja</option>
                    <option>Event dan festival Bandung</option>
                    <option>Hidden gem dan spot foto</option>
                </select>
            </div>
            <div class="interest-row">
                <label><input type="checkbox" name="minat" value="Wisata"> Wisata</label>
                <label><input type="checkbox" name="minat" value="Kuliner"> Kuliner</label>
                <label><input type="checkbox" name="minat" value="Kafe"> Kafe</label>
                <label><input type="checkbox" name="minat" value="Event"> Event</label>
                <label><input type="checkbox" name="minat" value="Hidden Gem"> Hidden Gem</label>
                <label><input type="checkbox" name="minat" value="Spot Foto"> Spot Foto</label>
            </div>
            <label class="terms">
                <input type="checkbox" required>
                Saya setuju menggunakan WeBandoo+ untuk menyimpan preferensi eksplorasi Bandung dan menerima rekomendasi lokasi.
            </label>
            <button type="submit" class="btn">Daftar Sekarang</button>
        </form>

        <div class="login-link">
            Sudah punya akun? <a href="login.php">Masuk di sini</a>
        </div>
    </div>
</div>
<script src="assets/js/webandoo-app.js"></script>
<script src="assets/js/auth.js"></script>
</body>
</html>


