<?php require_once __DIR__ . '/partials/auth_guard.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>WeBandoo+ - Home</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<link rel="stylesheet" href="assets/css/webandoo-enhancements.css">
<link rel="stylesheet" href="assets/css/webandoo-layout.css">
<link rel="stylesheet" href="assets/css/home.css">
</head>
<body>
<div class="figma-frame">
    <nav class="navbar">
        <a href="home.php" class="logo">WeBandoo+</a>
        <ul class="nav-links">
            <li><a class="nav-link active" href="home.php"><i class="fas fa-chart-line"></i> Dashboard</a></li>
            <li><a class="nav-link" href="warisan.php"><i class="fas fa-landmark"></i> Warisan & Cagar Budaya</a></li>
            <li><a class="nav-link" href="peta.php"><i class="fas fa-map-pin"></i> Peta Lokasi</a></li>
            <li><a class="nav-link" href="kuliner.php"><i class="fas fa-utensils"></i> Kuliner</a></li>
            <li><a class="nav-link" href="kafe.php"><i class="fas fa-coffee"></i> Kafe</a></li>
            <li><a class="nav-link" href="event.php"><i class="fas fa-calendar-day"></i> Event & Jadwal</a></li>
            <li><a class="nav-link" href="eduction.php"><i class="fas fa-book"></i> Belajar</a></li>
        </ul>
        <div class="top-actions">
            <a href="pengaturan.php" aria-label="Pengaturan"><i class="fas fa-gear"></i></a>
            <a href="profil.php" class="avatar" aria-label="Profil"></a>
            <a href="keluar.php" aria-label="Keluar"><i class="fas fa-sign-out-alt"></i> Keluar</a>
        </div>
    </nav>

    <main class="page">
        <section class="promo-banner" aria-label="Banner promo WeBandoo+">
            <article class="promo-slide promo-slide-heritage active">
                <div class="promo-content">
                    <span class="promo-kicker"><i class="fas fa-bullhorn"></i> Promo Pilihan WeBandoo+</span>
                    <h1>Temukan promo dan rekomendasi terbaik di Bandung</h1>
                    <p>Lihat penawaran kafe, kuliner, event, penginapan, dan paket wisata yang sedang populer agar rencana jalan-jalanmu lebih hemat, praktis, dan menyenangkan.</p>
                    <div class="promo-actions">
                        <a class="promo-btn" href="kuliner.php"><i class="fas fa-tags"></i> Lihat Promo</a>
                        <a class="promo-btn secondary" href="peta.php"><i class="fas fa-map-location-dot"></i> Cari Terdekat</a>
                    </div>
                    <div class="promo-stats">
                        <div class="promo-stat">
                            <strong>Promo Hemat</strong>
                            <span>Rekomendasi tempat makan dan kafe ramah budget</span>
                        </div>
                        <div class="promo-stat">
                            <strong>Event Terdekat</strong>
                            <span>Agenda budaya dan hiburan yang bisa kamu kunjungi</span>
                        </div>
                        <div class="promo-stat">
                            <strong>Lokasi Pilihan</strong>
                            <span>Tempat populer yang mudah dijangkau dari posisimu</span>
                        </div>
                    </div>
                </div>
            </article>

            <article class="promo-slide promo-slide-event">
                <div class="promo-content">
                    <span class="promo-kicker"><i class="fas fa-calendar-day"></i> Event Minggu Ini</span>
                    <h1>Agenda budaya pilihan untuk akhir pekanmu</h1>
                    <p>Ikuti festival, pertunjukan, dan acara komunitas Bandung yang sedang berlangsung. Cocok untuk keluarga, mahasiswa, dan wisatawan yang ingin pengalaman lokal.</p>
                    <div class="promo-actions">
                        <a class="promo-btn" href="event.php"><i class="fas fa-ticket-alt"></i> Lihat Event</a>
                        <a class="promo-btn secondary" href="peta.php"><i class="fas fa-route"></i> Cek Rute</a>
                    </div>
                    <div class="promo-stats">
                        <div class="promo-stat">
                            <strong>Live Event</strong>
                            <span>Agenda terkurasi dari komunitas dan budaya lokal</span>
                        </div>
                        <div class="promo-stat">
                            <strong>Jam & Lokasi</strong>
                            <span>Informasi kunjungan dibuat cepat dibaca</span>
                        </div>
                        <div class="promo-stat">
                            <strong>Rute Mudah</strong>
                            <span>Langsung lanjut ke peta lokasi acara</span>
                        </div>
                    </div>
                </div>
            </article>

            <article class="promo-slide promo-slide-cafe">
                <div class="promo-content">
                    <span class="promo-kicker"><i class="fas fa-mug-hot"></i> Kafe Rekomendasi</span>
                    <h1>Tempat nongkrong nyaman untuk kerja dan santai</h1>
                    <p>Cari kafe dengan WiFi, colokan, suasana nyaman, spot foto, dan harga yang sesuai budget sebelum kamu berangkat.</p>
                    <div class="promo-actions">
                        <a class="promo-btn" href="kafe.php"><i class="fas fa-coffee"></i> Jelajahi Kafe</a>
                        <a class="promo-btn secondary" href="kuliner.php"><i class="fas fa-utensils"></i> Kuliner Sekitar</a>
                    </div>
                    <div class="promo-stats">
                        <div class="promo-stat">
                            <strong>WiFi & Colokan</strong>
                            <span>Filter kebutuhan kerja atau belajar</span>
                        </div>
                        <div class="promo-stat">
                            <strong>Harga Jelas</strong>
                            <span>Perkiraan budget sebelum datang</span>
                        </div>
                        <div class="promo-stat">
                            <strong>Spot Foto</strong>
                            <span>Pilihan tempat dengan visual menarik</span>
                        </div>
                    </div>
                </div>
            </article>

            <div class="promo-controls" aria-label="Kontrol banner promo">
                <button class="promo-arrow" type="button" data-promo-prev aria-label="Iklan sebelumnya"><i class="fas fa-chevron-left"></i></button>
                <div class="promo-dots" aria-label="Pilih iklan">
                    <button class="promo-dot active" type="button" data-promo-dot="0" aria-label="Iklan 1"></button>
                    <button class="promo-dot" type="button" data-promo-dot="1" aria-label="Iklan 2"></button>
                    <button class="promo-dot" type="button" data-promo-dot="2" aria-label="Iklan 3"></button>
                </div>
                <button class="promo-arrow" type="button" data-promo-next aria-label="Iklan berikutnya"><i class="fas fa-chevron-right"></i></button>
            </div>
        </section>

        <section id="vueSystemPanel" class="vue-system-panel" v-cloak>
            <div class="vue-system-head">
                <div>
                    <span class="vue-kicker"><i class="fas fa-layer-group"></i> Vue Component</span>
                    <h2>Ringkasan Sistem</h2>
                    <p>Data akun dan review dimuat asynchronous dari REST API WeBandoo+.</p>
                </div>
                <span class="vue-role" :class="{ 'is-admin': user.role === 'admin' }">{{ user.role || 'user' }}</span>
            </div>
            <div class="vue-system-grid">
                <article>
                    <small>Pengguna login</small>
                    <strong>{{ displayName }}</strong>
                    <span>{{ user.email || 'Memuat akun...' }}</span>
                </article>
                <article>
                    <small>Total review</small>
                    <strong>{{ totalReviews }}</strong>
                    <span>Review tersimpan di database</span>
                </article>
                <article>
                    <small>Rating rata-rata</small>
                    <strong>{{ averageRating }}</strong>
                    <span>Diambil dari endpoint review</span>
                </article>
                <article>
                    <small>Mode akses</small>
                    <strong>{{ accessLabel }}</strong>
                    <span>{{ accessDescription }}</span>
                </article>
            </div>
        </section>

        <section class="section-card">
            <div class="section-head">
                <div class="section-title">
                    <h2>Destinasi Terpopuler</h2>
                    <p>Lokasi warisan yang sering dikunjungi pekan ini</p>
                </div>
                <a class="view-all" href="warisan.php">Lihat Semua</a>
            </div>

            <div class="destination-grid">
                <a href="warisan.php" class="destination-large">
                    <img src="assets/images/bdg1.jpg" alt="Jalan Braga Bandung">
                    <span class="destination-arrow"><i class="fas fa-arrow-right"></i></span>
                    <div class="destination-info">
                        <span class="destination-tag"><i class="fas fa-fire"></i> Paling ramai</span>
                        <h3>Jalan Braga Heritage Walk</h3>
                        <p>Koridor ikonik untuk foto malam, kuliner ringan, bangunan kolonial, dan jalan kaki santai di pusat Bandung.</p>
                        <div class="destination-meta">
                            <span><i class="fas fa-star"></i> 4.8</span>
                            <span><i class="fas fa-location-dot"></i> 0.8 km</span>
                            <span><i class="fas fa-ticket-alt"></i> Gratis</span>
                        </div>
                    </div>
                </a>
                <div class="destination-mosaic">
                    <a href="warisan.php" class="destination-small">
                        <img src="assets/images/bdg3.jpg" alt="Gedung Sate Bandung">
                        <span class="destination-arrow"><i class="fas fa-arrow-right"></i></span>
                        <div class="destination-info">
                            <span class="destination-tag"><i class="fas fa-landmark"></i> Sejarah</span>
                            <h3>Gedung Sate</h3>
                            <p>Ikon arsitektur Jawa Barat dengan museum, taman, dan spot foto favorit.</p>
                            <div class="destination-meta">
                                <span><i class="fas fa-star"></i> 4.7</span>
                                <span><i class="fas fa-clock"></i> 08.00</span>
                            </div>
                        </div>
                    </a>
                    <a href="warisan.php" class="destination-small">
                        <img src="assets/images/bdg4.jpg" alt="Panorama Bandung">
                        <span class="destination-arrow"><i class="fas fa-arrow-right"></i></span>
                        <div class="destination-info">
                            <span class="destination-tag"><i class="fas fa-camera"></i> Spot foto</span>
                            <h3>Panorama Kota Bandung</h3>
                            <p>Rekomendasi pemandangan kota untuk sore hari dan foto skyline.</p>
                            <div class="destination-meta">
                                <span><i class="fas fa-star"></i> 4.6</span>
                                <span><i class="fas fa-route"></i> Mudah</span>
                            </div>
                        </div>
                    </a>
                    <a href="warisan.php" class="destination-wide">
                        <img src="assets/images/bdg5.jpg" alt="Museum dan bangunan heritage Bandung">
                        <span class="destination-arrow"><i class="fas fa-arrow-right"></i></span>
                        <div class="destination-info">
                            <span class="destination-tag"><i class="fas fa-map-location-dot"></i> Rute pilihan</span>
                            <h3>Rute Asia Afrika - Museum Kota</h3>
                            <p>Jalur pendek untuk eksplorasi sejarah, foto bangunan lama, dan kuliner sekitar pusat kota.</p>
                            <div class="destination-meta">
                                <span><i class="fas fa-star"></i> 4.9</span>
                                <span><i class="fas fa-person-walking"></i> Walking tour</span>
                                <span><i class="fas fa-wallet"></i> Rp20k+</span>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </section>

        <section class="nearby-card">
            <div class="section-head">
                <div class="nearby-heading">
                    <span class="nearby-heading-icon"><i class="fas fa-location-crosshairs"></i></span>
                    <div class="section-title">
                        <h2>Dekat Dengan Kamu</h2>
                        <p>Preview lokasi sekitar yang tersambung dengan halaman peta</p>
                    </div>
                </div>
                <a class="view-all" href="peta.php">Lihat Semua</a>
            </div>
            <div class="nearby-layout">
                <div class="nearby-list">
                    <button type="button" class="nearby-item active" data-map-lat="-6.9216" data-map-lng="107.6078" data-map-zoom="16">
                        <span class="nearby-thumb"><img src="assets/images/bdg2.jpg" alt="Gedung Merdeka"></span>
                        <span>
                            <strong>Gedung Merdeka</strong>
                            <span>Jl. Asia Afrika - bangunan konferensi bersejarah</span>
                            <span class="nearby-meta">
                                <em><i class="fas fa-star"></i> 4.8</em>
                                <em><i class="fas fa-person-walking"></i> 8 menit</em>
                            </span>
                        </span>
                    </button>
                    <button type="button" class="nearby-item" data-map-lat="-6.9015" data-map-lng="107.6187" data-map-zoom="16">
                        <span class="nearby-thumb"><img src="assets/images/bdg6.jpg" alt="Museum Pos Indonesia"></span>
                        <span>
                            <strong>Museum Pos Indonesia</strong>
                            <span>Jl. Cilaki - museum komunikasi dan filateli</span>
                            <span class="nearby-meta">
                                <em><i class="fas fa-ticket-alt"></i> Murah</em>
                                <em><i class="fas fa-camera"></i> Foto</em>
                            </span>
                        </span>
                    </button>
                    <button type="button" class="nearby-item" data-map-lat="-6.9025" data-map-lng="107.6188" data-map-zoom="16">
                        <span class="nearby-thumb"><img src="assets/images/bdg5.jpg" alt="Gedung Sate"></span>
                        <span>
                            <strong>Gedung Sate</strong>
                            <span>Ikon pemerintahan bersejarah Kota Bandung</span>
                            <span class="nearby-meta">
                                <em><i class="fas fa-landmark"></i> Heritage</em>
                                <em><i class="fas fa-route"></i> Rute mudah</em>
                            </span>
                        </span>
                    </button>
                </div>

                <div class="map-panel" aria-label="Preview peta lokasi terdekat">
                    <div id="homeMiniMap"></div>
                    <div class="map-overlay-card">
                        <div>
                            <strong>Eksplorasi via Peta WeBandoo+</strong>
                            <span>Klik lokasi di kiri untuk melihat marker, atau buka peta penuh untuk filter lengkap.</span>
                        </div>
                        <a class="map-open-btn" href="peta.php"><i class="fas fa-up-right-from-square"></i> Buka Peta</a>
                    </div>
                </div>
            </div>
        </section>

        <section class="stories">
            <h2>Cerita Budaya Terbaru</h2>
            <div class="story-grid">
                <article class="story-card">
                    <img src="assets/images/bdg6.jpg" alt="Arsip Bandung tempo dulu">
                    <div class="story-body">
                        <span class="category">Sejarah Kota</span>
                        <h3>Menelusuri Kembali Julukan 'Paris van Java'</h3>
                        <p>Catatan singkat tentang suasana kota lama, arsitektur kolonial, dan jejak ruang publik Bandung.</p>
                        <div class="story-meta"><span class="story-dot"></span> 5 min baca - 2 hari yang lalu</div>
                    </div>
                </article>

                <article class="story-card">
                    <img src="assets/images/KOPI2.png" alt="Motif batik khas Priangan">
                    <div class="story-body">
                        <span class="category">Kriya & Motif</span>
                        <h3>Eksplorasi Motif Batik Khas Priangan</h3>
                        <p>Mengenal pola visual, warna alam, dan filosofi yang sering muncul pada tekstil tradisional.</p>
                        <div class="story-meta"><span class="story-dot"></span> 4 min baca - kemarin</div>
                    </div>
                </article>

                <article class="story-card">
                    <img src="assets/images/event1.jpg" alt="Helaran budaya Bandung">
                    <div class="story-body">
                        <span class="category">Budaya Populer</span>
                        <h3>Melihat Pentas Bandung Merah Pengibaran Warisan Budaya</h3>
                        <p>Agenda komunitas, pawai kostum, dan kolaborasi warga untuk menjaga ingatan budaya kota.</p>
                        <div class="story-meta"><span class="story-dot"></span> 6 min baca - minggu ini</div>
                    </div>
                </article>
            </div>
        </section>
    </main>
</div>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/vue@3/dist/vue.global.prod.js"></script>
<script src="assets/js/home.js"></script>
<script src="assets/js/vue-system-panel.js"></script>
<script src="assets/js/webandoo-layout.js"></script>
<script src="assets/js/webandoo-app.js"></script>
</body>
</html>



