<?php require_once __DIR__ . '/partials/auth_guard.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Event & Jadwal - WeBandoo+</title>
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
        max-width: 820px;
        color: #e8f1e8;
        line-height: 1.7;
    }

    .filter-row {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        margin-bottom: 24px;
    }

    .filter-chip {
        background: white;
        border-radius: 999px;
        padding: 10px 16px;
        box-shadow: var(--shadow-elevation);
        font-size: 13px;
        font-weight: 600;
        color: var(--color-text-dark);
    }

    .event-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 22px;
    }

    .event-card {
        background: white;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: var(--shadow-elevation);
        border: 1px solid #eef1ee;
    }

    .event-card img {
        width: 100%;
        height: 170px;
        object-fit: cover;
    }

    .event-body {
        padding: 20px;
    }

    .event-date-tag {
        display: inline-block;
        background: #eef5ee;
        color: var(--color-primary-green);
        padding: 6px 10px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .event-body h3 {
        margin-bottom: 8px;
        color: var(--color-text-dark);
    }

    .event-body p {
        color: var(--color-text-grey);
        font-size: 13px;
        line-height: 1.6;
        margin-bottom: 10px;
    }

    .event-meta {
        display: grid;
        gap: 7px;
        color: var(--color-text-dark);
        font-size: 13px;
        margin-top: 12px;
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
            <li><a href="warisan.php" class="nav-link"><i class="fas fa-landmark"></i> Warisan & Cagar Budaya</a></li>
            <li><a href="peta.php" class="nav-link"><i class="fas fa-map-pin"></i> Peta Lokasi</a></li>
            <li><a href="kuliner.php" class="nav-link"><i class="fas fa-utensils"></i> Kuliner</a></li>
            <li><a href="kafe.php" class="nav-link"><i class="fas fa-coffee"></i> Kafe</a></li>
            <li><a href="event.php" class="nav-link active"><i class="fas fa-calendar-day"></i> Event & Jadwal</a></li>
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
            <h2>Event Bandung</h2>
            <p>Temukan festival, konser, pameran, acara budaya, dan agenda komunitas di Bandung lengkap dengan jadwal, lokasi, harga tiket, dan rekomendasi rute.</p>
        </section>

        <div class="filter-row">
            <span class="filter-chip"><i class="fas fa-calendar-day"></i> Minggu Ini</span>
            <span class="filter-chip"><i class="fas fa-music"></i> Konser</span>
            <span class="filter-chip"><i class="fas fa-palette"></i> Pameran</span>
            <span class="filter-chip"><i class="fas fa-landmark"></i> Acara Budaya</span>
            <span class="filter-chip"><i class="fas fa-wallet"></i> Gratis / Murah</span>
        </div>

        <div class="event-grid">
            <article class="event-card">
                <img src="event1.jpg" alt="Festival Asia Afrika">
                <div class="event-body">
                    <span class="event-date-tag">24 Maret 2026</span>
                    <h3>Festival Asia Afrika</h3>
                    <p>Parade budaya, kuliner lokal, musik jalanan, dan pameran sejarah Bandung.</p>
                    <div class="event-meta">
                        <span><i class="fas fa-map-marker-alt"></i> Kawasan Asia Afrika</span>
                        <span><i class="fas fa-ticket-alt"></i> Gratis - Rp50.000</span>
                        <span><i class="fas fa-route"></i> 2.1 km dari Braga</span>
                    </div>
                </div>
            </article>

            <article class="event-card">
                <img src="event2.jpg" alt="Workshop Angklung">
                <div class="event-body">
                    <span class="event-date-tag">10 April 2026</span>
                    <h3>Workshop Angklung</h3>
                    <p>Belajar memainkan angklung dan mengenal filosofi musik tradisional Sunda.</p>
                    <div class="event-meta">
                        <span><i class="fas fa-map-marker-alt"></i> Saung Angklung Udjo</span>
                        <span><i class="fas fa-ticket-alt"></i> Rp35.000</span>
                        <span><i class="fas fa-star"></i> Rating 4.8</span>
                    </div>
                </div>
            </article>

            <article class="event-card">
                <img src="bdg2.jpg" alt="Pameran Bandung Tempo Dulu">
                <div class="event-body">
                    <span class="event-date-tag">18 April 2026</span>
                    <h3>Pameran Bandung Tempo Dulu</h3>
                    <p>Arsip foto, cerita warga, dan instalasi sejarah kota untuk edukasi budaya.</p>
                    <div class="event-meta">
                        <span><i class="fas fa-map-marker-alt"></i> Museum Kota Bandung</span>
                        <span><i class="fas fa-ticket-alt"></i> Rp10.000</span>
                        <span><i class="fas fa-heart"></i> 1.240 disimpan</span>
                    </div>
                </div>
            </article>
        </div>
    </div>
<script src="assets/js/webandoo-layout.js"></script>
<script src="assets/js/webandoo-app.js"></script>
</body>
</html>


