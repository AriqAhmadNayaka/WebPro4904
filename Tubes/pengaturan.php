<?php require_once __DIR__ . '/partials/auth_guard.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Dashboard WeBandoo - Warisan Bandung (V2)</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link rel="stylesheet" href="assets/css/webandoo-enhancements.css">
<link rel="stylesheet" href="assets/css/webandoo-layout.css">
<script src="https://mcp.figma.com/mcp/html-to-design/capture.js" async></script>

<style>
    :root {
        --color-primary-green: #4A8645; 
        --color-secondary-brown: #8B4513;
        --color-white: #ffffff;
        --color-off-white: #f0f2f5; 
        --color-text-dark: #2c3e50;
        --color-text-grey: #7f8c8d;
        --shadow-elevation: 0 8px 30px rgba(0, 0, 0, 0.08);
    }

    * {
        margin: 0; padding: 0; box-sizing: border-box;
        font-family: "Poppins", sans-serif;
    }

    body {
        min-height: 100vh;
        background-color: var(--color-off-white);
        color: var(--color-text-dark);
        display: flex;
        flex-direction: column;
    }

    .navbar {
        width: 100%;
        background-color: var(--color-text-dark);
        color: var(--color-white);
        padding: 20px 30px;
        box-shadow: var(--shadow-elevation);
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: fixed;
        top: 0;
        z-index: 100;
    }

    .navbar .logo {
        font-size: 26px;
        font-weight: 700;
        color: var(--color-primary-green);
        text-decoration: none;
    }

    .nav-links {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .nav-links li {
        margin-left: 0;
    }

    .nav-link {
        display: flex;
        align-items: center;
        padding: 10px 15px;
        color: #bdc3c7; 
        text-decoration: none;
        border-radius: 8px;
        transition: background-color 0.3s, color 0.3s;
        font-weight: 500;
    }

    .nav-link i {
        margin-right: 8px;
        font-size: 18px;
    }

    .nav-link:hover {
        background-color: #34495e;
        color: var(--color-white);
    }
    
    .nav-link.active {
        background-color: var(--color-primary-green);
        color: var(--color-white);
        box-shadow: 0 4px 10px rgba(74, 134, 69, 0.4);
    }

    .navbar-right {
        display: flex;
        align-items: center;
    }

    .avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        margin-left: 15px;
        border: 2px solid var(--color-primary-green);
        overflow: hidden;
        cursor: pointer;
    }
    
    .avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    #imageViewer {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.85);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }
    #imageViewer img {
        max-width: 90%;
        max-height: 90%;
        border-radius: 12px;
    }

    .toggle-btn-leave {
        background-color: var(--color-primary-green);
        color: var(--color-white);
        box-shadow: 0 4px 10px rgba(74, 134, 69, 0.4);
        font-size: 15px;
        cursor: pointer;
        padding: 10px;
        border-radius: 8px;
        transition: background-color 0.3s;
        margin-left: 15px;
        text-decoration: none;
    }

    .toggle-btn-set {
        background-color: var(--color-primary-green);
        color: var(--color-white);
        box-shadow: 0 4px 10px rgba(74, 134, 69, 0.4);
        font-size: 15px;
        cursor: pointer;
        padding: 10px;
        border-radius: 8px;
        transition: background-color 0.3s;
        margin-left: 15px;
        text-decoration: none;
    }


    .overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: none;
        z-index: 99;
    }

    .overlay.active {
        display: block;
    }

    .main-content {
        flex-grow: 1;
        margin-top: 80px;
        padding: 40px clamp(72px, 5vw, 120px);
    }

    .header-bar {
        background: var(--color-white);
        padding: 24px 30px;
        border-radius: 16px;
        box-shadow: var(--shadow-elevation);
        margin-bottom: 28px;
    }

    .settings-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 22px;
    }

    .settings-card {
        background: var(--color-white);
        border-radius: 18px;
        padding: 24px;
        box-shadow: var(--shadow-elevation);
        border: 1px solid #eef1ee;
    }

    .settings-card h2 {
        color: var(--color-primary-green);
        margin-bottom: 12px;
    }

    .settings-card p {
        color: var(--color-text-grey);
        line-height: 1.7;
        font-size: 13px;
        margin-bottom: 16px;
    }

    .setting-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 14px;
        padding: 13px 0;
        border-bottom: 1px solid #edf0ed;
        font-size: 14px;
        font-weight: 600;
    }

    .setting-row:last-child {
        border-bottom: none;
    }

    .toggle {
        width: 48px;
        height: 26px;
        background: var(--color-primary-green);
        border-radius: 999px;
        position: relative;
        flex: 0 0 auto;
    }

    .toggle::after {
        content: "";
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: white;
        position: absolute;
        right: 3px;
        top: 3px;
    }

    .preference-list {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .preference-list span {
        background: #eef5ee;
        color: var(--color-primary-green);
        border-radius: 999px;
        padding: 9px 12px;
        font-size: 13px;
        font-weight: 700;
    }

    @media (max-width: 768px) {

        .main-content {
            margin-top: 250%;
            padding: 20px;
            padding-top: 20px;
        }

        .navbar {
            flex-direction: column;
            padding: 10px 15px;
        }
        .nav-links {
            flex-direction: column;
            margin-top: 10px;
        }
        .nav-links li {
            margin-left: 0;
            margin-bottom: 10px;
        }
        .navbar-right {
            margin-top: 10px;
        }
        .main-content {
            margin-top: 150px; 
            padding: 20px;
        }
        .iklan {
            grid-template-columns: 1fr;
            height: auto;
        }
        .search-bar-container {
            max-width: 100%;
            padding: 10px 15px;
        }
        .search-input {
            font-size: 14px;
        }
        .search-btn {
            padding: 8px 12px;
            font-size: 14px;
        }
    }
</style>
</head>

<body>

    <nav class="navbar">
        <a href="home.php" class="logo">WeBandoo+</a>
        <ul class="nav-links">
            <li><a href="home.php" class="nav-link"><i class="fas fa-chart-line"></i> Dashboard</a></li>
            <li><a href="warisan.php" class="nav-link"><i class="fas fa-landmark"></i> Warisan & Cagar Budaya</a></li>
            <li><a href="peta.php" class="nav-link"><i class="fas fa-map-pin"></i> Peta Lokasi</a></li>
            <li><a href="kuliner.php" class="nav-link"><i class="fas fa-utensils"></i> Kuliner</a></li>
            <li><a href="kafe.php" class="nav-link"><i class="fas fa-coffee"></i> Kafe</a></li>
            <li><a href="event.php" class="nav-link"><i class="fas fa-calendar-day"></i> Event & Jadwal</a></li>
            <li><a href="eduction.php" class="nav-link"><i class="fas fa-book"></i> Belajar</a></li>
        </ul>
        <div class="navbar-right">
            <div class="avatar">
                <a href="profil.php">
                    <img src="Raihan.jpg" alt="Avatar"> 
                </a>
            </div>
            <a href="pengaturan.php" class="toggle-btn-leave" style="background:#3e7039;"><i class="fas fa-cog"></i></a>
            <a href="keluar.php" class="toggle-btn-set"><i class="fas fa-sign-out-alt"></i> Keluar</a>
        </div>
    </nav>

    <div class="main-content">
        <div class="header-bar">
            <h1>Pengaturan Pengalaman WeBandoo+</h1>
            <p style="color: var(--color-text-grey); margin-top: 8px;">Atur tampilan, rekomendasi, budget, dan kebutuhan akses agar aplikasi nyaman untuk semua umur.</p>
        </div>

        <div class="settings-grid">
            <section class="settings-card">
                <h2><i class="fas fa-universal-access"></i> Aksesibilitas</h2>
                <p>Mode ini membantu pengguna lansia atau pengguna yang ingin tampilan lebih sederhana.</p>
                <div class="setting-row"><span>Mode teks besar</span><span class="toggle"></span></div>
                <div class="setting-row"><span>Kontras tinggi</span><span class="toggle"></span></div>
                <div class="setting-row"><span>Tombol aksi diperbesar</span><span class="toggle"></span></div>
                <div class="setting-row"><span>Kurangi animasi</span><span class="toggle"></span></div>
            </section>

            <section class="settings-card">
                <h2><i class="fas fa-sliders"></i> Preferensi Rekomendasi</h2>
                <p>Pilih minat agar pencarian cerdas bisa memberi hasil yang lebih sesuai.</p>
                <div class="preference-list">
                    <span>Wisata sejarah</span>
                    <span>Kuliner murah</span>
                    <span>Kafe WiFi</span>
                    <span>Hidden gem</span>
                    <span>Spot foto</span>
                    <span>Event budaya</span>
                </div>
            </section>

            <section class="settings-card">
                <h2><i class="fas fa-wallet"></i> Budget Default</h2>
                <p>Budget membantu sistem menyarankan lokasi yang tidak melebihi perkiraan biaya pengguna.</p>
                <div class="setting-row"><span>Budget makan</span><strong>Rp50.000</strong></div>
                <div class="setting-row"><span>Budget tiket</span><strong>Rp25.000</strong></div>
                <div class="setting-row"><span>Budget parkir</span><strong>Rp5.000</strong></div>
            </section>

            <section class="settings-card">
                <h2><i class="fas fa-bell"></i> Notifikasi</h2>
                <p>Pengingat event, wishlist, dan rute bisa membantu pengguna merencanakan perjalanan.</p>
                <div class="setting-row"><span>Event akhir pekan</span><span class="toggle"></span></div>
                <div class="setting-row"><span>Lokasi wishlist terdekat</span><span class="toggle"></span></div>
                <div class="setting-row"><span>Rekomendasi hidden gem</span><span class="toggle"></span></div>
            </section>
        </div>
    </div>
<script src="assets/js/webandoo-layout.js"></script>
<script src="assets/js/webandoo-app.js"></script>
</body>


