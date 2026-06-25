<?php require_once __DIR__ . '/partials/auth_guard.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Kafe & Tempat Nongkrong - WeBandoo+</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link rel="stylesheet" href="assets/css/webandoo-enhancements.css">
<link rel="stylesheet" href="assets/css/webandoo-layout.css">
<link rel="stylesheet" href="assets/css/kafe.css">
</head>
<body>
<nav class="navbar">
    <a href="home.php" class="logo">WeBandoo+</a>
    <ul class="nav-links">
        <li><a href="home.php" class="nav-link"><i class="fas fa-chart-line"></i> Dashboard</a></li>
        <li><a href="warisan.php" class="nav-link"><i class="fas fa-landmark"></i> Warisan & Cagar Budaya</a></li>
        <li><a href="peta.php" class="nav-link"><i class="fas fa-map-pin"></i> Peta Lokasi</a></li>
        <li><a href="kuliner.php" class="nav-link"><i class="fas fa-utensils"></i> Kuliner</a></li>
        <li><a href="kafe.php" class="nav-link active"><i class="fas fa-coffee"></i> Kafe</a></li>
        <li><a href="event.php" class="nav-link"><i class="fas fa-calendar-day"></i> Event & Jadwal</a></li>
        <li><a href="eduction.php" class="nav-link"><i class="fas fa-book"></i> Belajar</a></li>
    </ul>
    <div class="navbar-right">
        <div class="avatar"><a href="profil.php"><img src="assets/images/Raihan.jpg" alt="Avatar"></a></div>
        <a href="pengaturan.php" class="toggle-btn-leave"><i class="fas fa-cog"></i></a>
        <a href="keluar.php" class="toggle-btn-set"><i class="fas fa-sign-out-alt"></i> Keluar</a>
    </div>
</nav>

<main class="main-content page">
    <div class="header-bar">
        <div>
            <h1>Kafe & Tempat Nongkrong</h1>
            <p>Harga, suasana, WiFi, smoking area, colokan, fasilitas, dan spot foto.</p>
        </div>
    </div>

    <section class="hero">
        <h1>Kafe & Tempat Nongkrong Bandung</h1>
        <p>Pengguna sering butuh tahu suasana, harga, WiFi, smoking area, colokan, dan spot foto sebelum memilih tempat. Halaman ini dibuat untuk keputusan cepat dan praktis.</p>
    </section>

    <section class="need-grid">
        <div class="need"><i class="fas fa-wifi"></i> WiFi tersedia</div>
        <div class="need"><i class="fas fa-plug"></i> Banyak colokan</div>
        <div class="need"><i class="fas fa-smoking"></i> Smoking area</div>
        <div class="need"><i class="fas fa-camera"></i> Spot foto</div>
        <div class="need"><i class="fas fa-wallet"></i> Harga jelas</div>
    </section>

    <section class="grid">
        <article class="card">
            <img src="assets/images/KOPI1.jpg" alt="Kopi Mandja Progo">
            <div class="body">
                <span class="tag"><i class="fas fa-laptop"></i> Work Friendly</span>
                <h3>Kopi Mandja Progo</h3>
                <p>Suasana teduh, cocok untuk nongkrong santai atau kerja ringan.</p>
                <div class="facility"><span>WiFi</span><span>Outdoor</span><span>Colokan</span><span>Spot foto</span></div>
                <div class="meta">
                    <span><i class="fas fa-wallet"></i> Rp25.000 - Rp75.000</span>
                    <span><i class="fas fa-clock"></i> 08.00 - 22.00</span>
                    <span class="rating">4.6</span>
                </div>
            </div>
        </article>
        <article class="card">
            <img src="assets/images/KOPI2.png" alt="Kafe Braga">
            <div class="body">
                <span class="tag"><i class="fas fa-camera-retro"></i> Aesthetic</span>
                <h3>Braga Corner Cafe</h3>
                <p>Kafe dekat kawasan heritage, cocok untuk foto, ngobrol sore, dan eksplor Braga.</p>
                <div class="facility"><span>Spot foto</span><span>Non-smoking</span><span>Dekat wisata</span><span>Snack</span></div>
                <div class="meta">
                    <span><i class="fas fa-wallet"></i> Rp30.000 - Rp90.000</span>
                    <span><i class="fas fa-clock"></i> 10.00 - 23.00</span>
                    <span class="rating">4.4</span>
                </div>
            </div>
        </article>
        <article class="card">
            <img src="assets/images/bdg5.jpg" alt="Taman Kafe Dago">
            <div class="body">
                <span class="tag"><i class="fas fa-tree"></i> Outdoor</span>
                <h3>Taman Kafe Dago</h3>
                <p>Tempat terbuka untuk keluarga, suasana lebih tenang, cocok untuk sore hari.</p>
                <div class="facility"><span>Outdoor</span><span>Family friendly</span><span>Parkir</span><span>Smoking area</span></div>
                <div class="meta">
                    <span><i class="fas fa-wallet"></i> Rp20.000 - Rp65.000</span>
                    <span><i class="fas fa-clock"></i> 09.00 - 21.00</span>
                    <span class="rating">4.3</span>
                </div>
            </div>
        </article>
    </section>

    <div data-split-bill
         data-title="Split Bill Kafe"
         data-hint="Tambahkan menu minuman atau makanan di bawah nama tiap orang, lalu sistem menghitung subtotal, pajak/service, tip, dan nominal transfer.">
    </div>

    <section class="comparison">
        <h2>Bandingkan Cepat</h2>
        <table>
            <tr><th>Tempat</th><th>Harga</th><th>Suasana</th><th>Fasilitas</th><th>Cocok Untuk</th></tr>
            <tr><td>Kopi Mandja Progo</td><td>Rp25k - Rp75k</td><td>Tenang</td><td>WiFi, colokan, outdoor</td><td>Kerja ringan</td></tr>
            <tr><td>Braga Corner Cafe</td><td>Rp30k - Rp90k</td><td>Ramai, fotogenik</td><td>Spot foto, snack</td><td>Nongkrong wisata</td></tr>
            <tr><td>Taman Kafe Dago</td><td>Rp20k - Rp65k</td><td>Santai</td><td>Outdoor, parkir</td><td>Keluarga</td></tr>
        </table>
    </section>
</main>
<footer>
    <div class="footer-container">
        <div class="footer-col">
            <h3>WeBandoo+</h3>
            <p>Platform digital terintegrasi untuk wisata, kuliner, kafe, budaya, event, dan rute Kota Bandung.</p>
        </div>
        <div class="footer-col">
            <h4>Navigasi</h4>
            <ul>
                <li><a href="home.php">Dashboard Utama</a></li>
                <li><a href="peta.php">Eksplorasi Peta</a></li>
                <li><a href="kuliner.php">Kuliner Bandung</a></li>
                <li><a href="event.php">Agenda Budaya</a></li>
            </ul>
        </div>
        <div class="footer-col">
            <h4>Fokus Pengguna</h4>
            <p>Harga jelas, jarak, rute, rating, foto, fasilitas, dan rekomendasi yang mudah dipahami semua umur.</p>
        </div>
    </div>
    <div class="footer-bottom">2026 WeBandoo+ Bandung</div>
</footer>
<script src="assets/js/webandoo-layout.js"></script>
<script src="assets/js/webandoo-app.js"></script>
<script src="assets/js/split-bill.js"></script>
</body>
</html>

