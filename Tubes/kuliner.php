<?php require_once __DIR__ . '/partials/auth_guard.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Kuliner Bandung - WeBandoo+</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link rel="stylesheet" href="assets/css/webandoo-enhancements.css">
<link rel="stylesheet" href="assets/css/webandoo-layout.css">
<link rel="stylesheet" href="assets/css/kuliner.css">
</head>
<body>
<nav class="navbar">
    <a href="home.php" class="logo">WeBandoo+</a>
    <ul class="nav-links">
        <li><a href="home.php" class="nav-link"><i class="fas fa-chart-line"></i> Dashboard</a></li>
        <li><a href="warisan.php" class="nav-link"><i class="fas fa-landmark"></i> Warisan & Cagar Budaya</a></li>
        <li><a href="peta.php" class="nav-link"><i class="fas fa-map-pin"></i> Peta Lokasi</a></li>
        <li><a href="kuliner.php" class="nav-link active"><i class="fas fa-utensils"></i> Kuliner</a></li>
        <li><a href="kafe.php" class="nav-link"><i class="fas fa-coffee"></i> Kafe</a></li>
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
            <h1>Kuliner Bandung</h1>
            <p>Menu, harga, rating, jam buka, dan estimasi biaya sebelum berangkat.</p>
        </div>
    </div>

    <section class="hero">
        <h1>Informasi Kuliner Bandung</h1>
        <p>Dirancang dari kebutuhan pengguna yang ingin melihat harga, menu, jam buka, rating, foto, dan jarak sebelum datang. Cocok untuk keluarga, mahasiswa, wisatawan, dan pemburu makanan lokal.</p>
    </section>

    <section class="search-panel">
        <input type="text" placeholder="Cari: batagor murah, makan keluarga, kuliner malam...">
        <button class="btn"><i class="fas fa-search"></i> Cari</button>
        <div class="chip-row">
            <span class="chip">Harga di bawah Rp50.000</span>
            <span class="chip">Kuliner malam</span>
            <span class="chip">Menu keluarga</span>
            <span class="chip">Dekat Braga</span>
            <span class="chip">Rating 4.5+</span>
        </div>
    </section>

    <section class="grid">
        <article class="card">
            <img src="assets/images/bdg3.jpg" alt="Batagor Kingsley">
            <div class="card-body">
                <span class="tag"><i class="fas fa-bowl-food"></i> Legendaris</span>
                <h3>Batagor Kingsley</h3>
                <p>Batagor dan siomay legendaris Bandung. Cocok untuk wisata kuliner cepat setelah jalan-jalan di pusat kota.</p>
                <div class="meta">
                    <span><i class="fas fa-clock"></i> 09.00 - 20.00</span>
                    <span><i class="fas fa-wallet"></i> Rp35.000 - Rp70.000/orang</span>
                    <span class="rating">&#9733;&#9733;&#9733;&#9733;&#9733; 4.5</span>
                    <span><i class="fas fa-route"></i> 1.4 km dari Alun-Alun Bandung</span>
                </div>
            </div>
        </article>

        <article class="card">
            <img src="assets/images/bdg1.jpg" alt="Gang Cibadak Malam">
            <div class="card-body">
                <span class="tag"><i class="fas fa-gem"></i> Hidden Gem</span>
                <h3>Gang Cibadak Malam</h3>
                <p>Tempat lokal yang belum terlalu formal, banyak jajanan malam, cocok untuk eksplorasi kuliner murah.</p>
                <div class="meta">
                    <span><i class="fas fa-clock"></i> 18.00 - 23.30</span>
                    <span><i class="fas fa-wallet"></i> Rp20.000 - Rp65.000/orang</span>
                    <span class="rating">&#9733;&#9733;&#9733;&#9733;&#9734; 4.5</span>
                    <span><i class="fas fa-camera"></i> Spot foto street food</span>
                </div>
            </div>
        </article>

        <article class="card">
            <img src="assets/images/bdg4.jpg" alt="Sate Hadori">
            <div class="card-body">
                <span class="tag"><i class="fas fa-fire"></i> Favorit Warga</span>
                <h3>Sate Hadori</h3>
                <p>Pilihan kuliner malam dekat area stasiun, cocok untuk pengguna yang baru tiba di Bandung.</p>
                <div class="meta">
                    <span><i class="fas fa-clock"></i> 10.00 - 22.00</span>
                    <span><i class="fas fa-wallet"></i> Rp45.000 - Rp90.000/orang</span>
                    <span class="rating">&#9733;&#9733;&#9733;&#9733;&#9734; 4.4</span>
                    <span><i class="fas fa-heart"></i> 2.180 disimpan</span>
                </div>
            </div>
        </article>
    </section>

    <div data-split-bill
         data-title="Split Bill Kuliner"
         data-hint="Tambahkan beberapa menu di bawah nama tiap orang agar tagihan kuliner dihitung dari pesanan masing-masing.">
    </div>

    <section class="budget-box">
        <div class="budget-item">Contoh rencana Braga: makan + parkir<strong>Rp55.000</strong></div>
        <div class="budget-item">Kuliner malam hemat<strong>Rp35.000</strong></div>
        <div class="budget-item">Makan keluarga 4 orang<strong>Rp180.000</strong></div>
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
                <li><a href="kafe.php">Kafe & Nongkrong</a></li>
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


