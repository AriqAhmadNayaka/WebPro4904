<?php require_once __DIR__ . '/partials/auth_guard.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Warisan & Cagar Budaya - WeBandoo</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link rel="stylesheet" href="assets/css/webandoo-enhancements.css">
<link rel="stylesheet" href="assets/css/webandoo-layout.css">
<script src="https://mcp.figma.com/mcp/html-to-design/capture.js" async></script>

<style>
    :root {
        --color-primary-green: #4A8645; 
        --color-white: #ffffff;
        --color-off-white: #f0f2f5; 
        --color-text-dark: #2c3e50;
        --color-text-grey: #7f8c8d;
        --shadow-elevation: 0 8px 30px rgba(0, 0, 0, 0.08);
        --color-red-danger: #e74c3c;
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

    .main-content {
        flex-grow: 1;
        margin-top: 80px;
        padding: 40px clamp(72px, 5vw, 120px);
    }

    .header-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 40px;
        background: var(--color-white);
        padding: 20px 30px;
        border-radius: 12px;
        box-shadow: var(--shadow-elevation);
    }

    .header-bar h1 {
        font-size: 30px;
        font-weight: 700;
        color: var(--color-text-dark);
    }

    .user-profile {
        display: flex;
        align-items: center;
        font-weight: 600;
        color: var(--color-text-grey);
    }

    .user-profile .avatar {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        margin-left: 15px;
        border: 2px solid var(--color-primary-green);
        overflow: hidden;
        cursor: pointer;
    }

    .user-profile .avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .page-intro {
        background: linear-gradient(135deg, #2c3e50, #4A8645);
        color: white;
        border-radius: 22px;
        padding: 34px;
        margin-bottom: 28px;
        box-shadow: var(--shadow-elevation);
    }

    .page-intro h2 {
        font-size: 34px;
        margin-bottom: 10px;
    }

    .page-intro p {
        color: #e8f1e8;
        line-height: 1.7;
        max-width: 860px;
    }

    .heritage-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 22px;
    }

    .heritage-card {
        background: white;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: var(--shadow-elevation);
        border: 1px solid #eef1ee;
    }

    .heritage-card img {
        width: 100%;
        height: 190px;
        object-fit: cover;
    }

    .heritage-body {
        padding: 20px;
    }

    .heritage-body h3 {
        margin-bottom: 8px;
        color: var(--color-text-dark);
    }

    .heritage-body p {
        color: var(--color-text-grey);
        font-size: 13px;
        line-height: 1.7;
        margin-bottom: 12px;
    }

    .info-list {
        display: grid;
        gap: 8px;
        font-size: 13px;
        color: var(--color-text-dark);
    }

    .badge-row {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        margin: 14px 0;
    }

    .badge {
        background: #eef5ee;
        color: var(--color-primary-green);
        padding: 6px 10px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
    }

    @media (max-width: 768px) {
        .navbar {
            flex-direction: column;
            padding: 10px;
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
    }
</style>
</head>
<body>

    <nav class="navbar">
        <a href="home.php" class="logo">WeBandoo+</a>
        <ul class="nav-links">
            <li><a href="home.php" class="nav-link"><i class="fas fa-chart-line"></i> Dashboard</a></li>
            <li><a href="warisan.php" class="nav-link active"><i class="fas fa-landmark"></i> Warisan & Cagar Budaya</a></li>
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
            <a href="pengaturan.php" class="toggle-btn-leave"><i class="fas fa-cog"></i></a>
            <a href="keluar.php" class="toggle-btn-set"><i class="fas fa-sign-out-alt"></i> Keluar</a>
        </div>
    </nav>

    <div class="main-content">
        <div class="header-bar">
            <h1>Halo, Selamat Datang di WeBandoo!</h1>
            </div>
        <section class="page-intro">
            <h2>Informasi Wisata & Budaya Bandung</h2>
            <p>Halaman ini membantu pengguna memahami cerita singkat, sejarah tempat, tiket masuk, jam operasional, spot foto, review, dan estimasi biaya sebelum datang.</p>
        </section>

        <div class="heritage-grid">
            <article class="heritage-card">
                <img src="bdg2.jpg" alt="Gedung Sate">
                <div class="heritage-body">
                    <h3>Gedung Sate</h3>
                    <p>Ikon arsitektur Bandung yang dibangun pada 1920 dan menjadi simbol pemerintahan Jawa Barat.</p>
                    <div class="badge-row">
                        <span class="badge">Wisata Sejarah</span>
                        <span class="badge">Spot Foto</span>
                        <span class="badge">Rating 4.8</span>
                    </div>
                    <div class="info-list">
                        <span><i class="fas fa-ticket-alt"></i> Tiket museum: Rp5.000</span>
                        <span><i class="fas fa-clock"></i> 08.00 - 16.00</span>
                        <span><i class="fas fa-wallet"></i> Estimasi biaya: Rp25.000 - Rp60.000</span>
                    </div>
                </div>
            </article>

            <article class="heritage-card">
                <img src="bdg6.jpg" alt="Jalan Braga">
                <div class="heritage-body">
                    <h3>Jalan Braga</h3>
                    <p>Kawasan art deco yang menjadi ruang jalan, kuliner, kafe, dan foto favorit wisatawan.</p>
                    <div class="badge-row">
                        <span class="badge">Hidden Gem Sekitar</span>
                        <span class="badge">Gratis</span>
                        <span class="badge">Rating 4.7</span>
                    </div>
                    <div class="info-list">
                        <span><i class="fas fa-ticket-alt"></i> Tiket: Gratis</span>
                        <span><i class="fas fa-clock"></i> 24 jam</span>
                        <span><i class="fas fa-wallet"></i> Estimasi biaya: Rp30.000 - Rp80.000</span>
                    </div>
                </div>
            </article>

            <article class="heritage-card">
                <img src="bdg5.jpg" alt="Asia Afrika">
                <div class="heritage-body">
                    <h3>Kawasan Asia Afrika</h3>
                    <p>Koridor sejarah Konferensi Asia Afrika dengan museum, bangunan ikonik, dan rute jalan kaki.</p>
                    <div class="badge-row">
                        <span class="badge">Budaya</span>
                        <span class="badge">Walking Tour</span>
                        <span class="badge">Rating 4.6</span>
                    </div>
                    <div class="info-list">
                        <span><i class="fas fa-ticket-alt"></i> Tiket museum: Rp10.000</span>
                        <span><i class="fas fa-clock"></i> 09.00 - 16.00</span>
                        <span><i class="fas fa-wallet"></i> Estimasi biaya: Rp20.000 - Rp70.000</span>
                    </div>
                </div>
            </article>
        </div>
    </div>

<script src="assets/js/webandoo-layout.js"></script>
<script src="assets/js/webandoo-app.js"></script>
</body>
</html>


