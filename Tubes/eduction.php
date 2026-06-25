<?php require_once __DIR__ . '/partials/auth_guard.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Belajar - WeBandoo+</title>
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
        color: #e8f1e8;
        line-height: 1.7;
        max-width: 860px;
    }

    .learning-grid {
        display: grid;
        grid-template-columns: 1.2fr 0.8fr;
        gap: 24px;
    }

    .article-list, .quiz-panel, .future-panel {
        background: white;
        border-radius: 18px;
        padding: 24px;
        box-shadow: var(--shadow-elevation);
        border: 1px solid #eef1ee;
    }

    .article-card {
        display: grid;
        grid-template-columns: 82px 1fr;
        gap: 16px;
        padding: 16px 0;
        border-bottom: 1px solid #edf0ed;
    }

    .article-card:last-child {
        border-bottom: none;
    }

    .article-icon {
        width: 82px;
        height: 82px;
        border-radius: 16px;
        display: grid;
        place-items: center;
        background: #eef5ee;
        color: var(--color-primary-green);
        font-size: 28px;
    }

    .article-card h3, .quiz-panel h3, .future-panel h3 {
        margin-bottom: 8px;
        color: var(--color-text-dark);
    }

    .article-card p, .quiz-panel p, .future-panel li {
        color: var(--color-text-grey);
        font-size: 13px;
        line-height: 1.7;
    }

    .quiz-option {
        display: block;
        margin-top: 10px;
        padding: 12px 14px;
        border-radius: 12px;
        background: #f7faf7;
        color: var(--color-text-dark);
        font-weight: 600;
        font-size: 13px;
    }

    .future-panel {
        margin-top: 24px;
    }

    .future-panel ul {
        list-style: none;
        display: grid;
        gap: 10px;
        margin-top: 12px;
    }

    .future-panel li i {
        color: var(--color-primary-green);
        margin-right: 8px;
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
        .learning-grid {
            grid-template-columns: 1fr;
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
            <li><a href="eduction.php" class="nav-link active"><i class="fas fa-book"></i> Belajar</a></li>
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
            <h2>Edukasi Budaya Bandung</h2>
            <p>WeBandoo+ tidak hanya memberi rute dan rekomendasi, tetapi juga membantu pengguna memahami cerita, nilai budaya, dan fakta unik di balik tempat yang mereka kunjungi.</p>
        </section>

        <div class="learning-grid">
            <section class="article-list">
                <h2>Artikel Budaya Pilihan</h2>
                <article class="article-card">
                    <div class="article-icon"><i class="fas fa-building-columns"></i></div>
                    <div>
                        <h3>Kenapa Gedung Sate Jadi Ikon Bandung?</h3>
                        <p>Ringkasan sejarah pembangunan, gaya arsitektur, dan fungsi gedung dari masa kolonial sampai sekarang.</p>
                    </div>
                </article>
                <article class="article-card">
                    <div class="article-icon"><i class="fas fa-music"></i></div>
                    <div>
                        <h3>Angklung dan Identitas Sunda</h3>
                        <p>Mengenal alat musik tradisional, cara memainkannya, dan alasan angklung dikenal dunia.</p>
                    </div>
                </article>
                <article class="article-card">
                    <div class="article-icon"><i class="fas fa-camera-retro"></i></div>
                    <div>
                        <h3>Jejak Art Deco di Braga</h3>
                        <p>Fakta unik bangunan tua, spot foto, dan etika berwisata di kawasan heritage.</p>
                    </div>
                </article>
            </section>

            <aside>
                <section class="quiz-panel">
                    <h3><i class="fas fa-circle-question"></i> Kuis Budaya Cepat</h3>
                    <p>Bangunan Bandung apa yang terkenal dengan ornamen tusuk sate pada menara utamanya?</p>
                    <span class="quiz-option">A. Gedung Sate</span>
                    <span class="quiz-option">B. Museum Geologi</span>
                    <span class="quiz-option">C. Taman Film</span>
                </section>

                <section class="future-panel">
                    <h3><i class="fas fa-trophy"></i> Fitur Masa Depan</h3>
                    <ul>
                        <li><i class="fas fa-robot"></i> AI recommendation sesuai minat pengguna</li>
                        <li><i class="fas fa-route"></i> Itinerary otomatis untuk wisata harian</li>
                        <li><i class="fas fa-medal"></i> Badge eksplorasi Bandung</li>
                        <li><i class="fas fa-coins"></i> Reward point dari review dan kunjungan</li>
                    </ul>
                </section>
            </aside>
        </div>
    </div>
<script src="assets/js/webandoo-layout.js"></script>
<script src="assets/js/webandoo-app.js"></script>
</body>
</html>


