<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard WeBandoo - Warisan Bandung (V2)</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-gesture-handling/dist/leaflet-gesture-handling.min.css">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/dashboard.css'); ?>">
</head>
<body>
    <nav class="navbar">
        <a href="<?php echo site_url('dashboard'); ?>" class="logo">WeBandoo+</a>
        <ul class="nav-links">
            <li><a href="<?php echo site_url('dashboard'); ?>" class="nav-link active"><i class="fas fa-chart-line"></i> Dashboard</a></li>
            <li><a href="<?php echo site_url('warisan'); ?>" class="nav-link"><i class="fas fa-landmark"></i> Warisan & Cagar Budaya</a></li>
            <li><a href="<?php echo site_url('peta'); ?>" class="nav-link"><i class="fas fa-map-pin"></i> Peta Lokasi</a></li>
            <li><a href="<?php echo site_url('event'); ?>" class="nav-link"><i class="fas fa-calendar-day"></i> Event & Jadwal</a></li>
            <li><a href="<?php echo site_url('belajar'); ?>" class="nav-link"><i class="fas fa-book"></i> Belajar</a></li>
        </ul>
        <div class="navbar-right">
            <a href="<?php echo site_url('profil'); ?>" class="avatar" title="Buka Profil Saya">
                <img src="<?php echo base_url(htmlspecialchars($foto_profil)); ?>" alt="Avatar">
            </a>
            <a href="<?php echo site_url('profil'); ?>" class="toggle-btn-leave"><i class="fas fa-user"></i> Profil</a>
            <a href="<?php echo site_url('logout'); ?>" class="toggle-btn-set"><i class="fas fa-sign-out-alt"></i> Keluar</a>
        </div>
    </nav>

    <div class="main-content">
        <div class="header-bar">
            <div>
                <h1>Halo <?php echo htmlspecialchars($nama_user); ?>, Selamat Datang di WeBandoo!</h1>
                <p class="header-subtitle">Eksplorasi 124 lokasi bersejarah hari ini.</p>
            </div>
            <div id="weather-widget" class="weather-widget">
                <span id="current-time" class="current-time">12:00 PM</span>
                <span class="weather-status"><i class="fas fa-cloud-sun"></i> Bandung, 28°C</span>
            </div>
        </div>

        <div class="hero-slider">
            <div class="slider-container">
                <div class="hero-item active">
                    <img src="<?php echo base_url('FOTO/bdg2.jpg'); ?>" alt="Gedung Sate" class="hero-img">
                    <div class="hero-overlay"></div>
                    <div class="hero-content">
                        <span class="badge badge-location"><i class="fas fa-map-marker-alt"></i> Destinasi Ikonik</span>
                        <h1>Gedung Sate</h1>
                        <p>Jelajahi keindahan arsitektur klasik Bandung. Buka setiap hari untuk kunjungan edukasi.</p>
                        <div class="hero-buttons">
                            <a href="<?php echo site_url('warisan'); ?>" class="btn-play">Detail Lokasi</a>
                        </div>
                    </div>
                </div>

                <div class="hero-item">
                    <img src="<?php echo base_url('FOTO/KOPI1.jpg'); ?>" alt="Festival Angklung" class="hero-img">
                    <div class="hero-overlay"></div>
                    <div class="hero-content">
                        <span class="badge badge-event"><i class="fas fa-calendar-check"></i> Event Mendatang</span>
                        <div class="event-date event-date-spaced">
                            <span class="day">20</span>
                            <span class="month">DESEMBER</span>
                        </div>
                        <h1>Festival Angklung 2025</h1>
                        <p>Akan diadakan di Kopi Mandja Progo pada tanggal 20 Desember 2025.</p>
                        <div class="hero-buttons">
                            <a href="<?php echo site_url('event'); ?>" class="btn-play">
                                <i class="fas fa-paper-plane"></i> Daftar Sekarang
                            </a>

                            <button type="button" class="btn-list js-save-calendar">
                                <i class="fas fa-calendar-plus"></i> Simpan Kalender
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <button class="slider-btn prev js-slide-trigger" data-step="-1" type="button"><i class="fas fa-chevron-left"></i></button>
            <button class="slider-btn next js-slide-trigger" data-step="1" type="button"><i class="fas fa-chevron-right"></i></button>

            <div class="slider-dots">
                <span class="dot active js-dot-trigger" data-slide-index="0"></span>
                <span class="dot js-dot-trigger" data-slide-index="1"></span>
            </div>
        </div>
    </div>

    <div class="main-content">
        <div class="dashboard-grid">
            <a href="<?php echo site_url('peta'); ?>" class="link-card-wrapper">
                <div class="info-card">
                    <div class="card-icon"><i class="fas fa-chess-rook"></i></div>
                    <h4>Total Lokasi Wisata</h4>
                    <p class="data">124</p>
                    <small class="stat-note stat-note-green">+5 Objek Baru dalam 6 bulan terakhir</small>
                </div>
            </a>

            <a href="<?php echo site_url('warisan'); ?>" class="link-card-wrapper">
                <div class="info-card">
                    <div class="card-icon"><i class="fas fa-star"></i></div>
                    <h4>Rekomendasi Populer</h4>
                    <p class="data data-featured">Gedung Sate</p>
                    <small class="stat-note stat-note-green">Waktu Kunjungan Terbaik: Sore Hari</small>
                </div>
            </a>

            <a href="<?php echo site_url('event'); ?>" class="link-card-wrapper">
                <div class="info-card">
                    <div class="card-icon brown"><i class="fas fa-ticket-alt"></i></div>
                    <h4>Event Warisan Mendatang</h4>
                    <p class="data">7</p>
                    <small class="stat-note stat-note-brown">2 Event skala besar minggu ini</small>
                </div>
            </a>
        </div>
    </div>

    <div class="main-content main-content-spaced">
        <div class="section-header section-header-inline section-header-gap-sm">
            <h3><i class="fas fa-map-marked-alt"></i> Eksplorasi Sekitarmu</h3>
            <a href="<?php echo site_url('peta'); ?>" class="view-full-map">Lihat Peta Full Screen <i class="fas fa-external-link-alt"></i></a>
        </div>

        <div class="dashboard-map-wrapper">
            <div id="map" class="map-widget"></div>

            <div class="map-sidebar">
                <div class="sidebar-info">
                    <h5>Lokasi Terpopuler</h5>
                    <p>Berdasarkan kunjungan minggu ini</p>
                </div>

                <div class="nearby-list">
                    <div class="nearby-card js-focus-map" data-lat="-6.9175" data-lng="107.6191" tabindex="0" role="button">
                        <img src="<?php echo base_url('FOTO/bdg5.jpg'); ?>" alt="Gedung Sate">
                        <div class="details">
                            <strong>Gedung Sate</strong>
                            <span><i class="fas fa-map-marker-alt"></i> 1.2 km dari Anda</span>
                        </div>
                    </div>

                    <div class="nearby-card js-focus-map" data-lat="-6.9211" data-lng="107.6110" tabindex="0" role="button">
                        <img src="<?php echo base_url('FOTO/bdg6.jpg'); ?>" alt="Jalan Braga">
                        <div class="details">
                            <strong>Jalan Braga</strong>
                            <span><i class="fas fa-map-marker-alt"></i> 0.5 km dari Anda</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="main-content">
        <div class="dashboard-bottom-section dashboard-bottom-section-spaced">
            <div class="section-wrapper section-wrapper-spaced">
                <div class="section-header section-header-inline section-header-gap-lg">
                    <h3 style="font-size: 1.2rem; color: #2d3436;"><i class="fas fa-calendar-day" style="color: #27ae60; margin-right: 10px;"></i>Agenda Budaya Terdekat</h3>
                    <a href="<?php echo site_url('event'); ?>" class="view-all-btn">Lihat Semua</a>
                </div>

                <div class="horizontal-scroll-wrapper">
                    <div class="event-card-mini">
                        <div class="card-image">
                            <img src="<?php echo base_url('FOTO/event1.jpg'); ?>" alt="Event">
                            <div class="date-tag">24 <br> <span>MAR</span></div>
                        </div>
                        <div class="card-details">
                            <h4>Bandung Lautan Api</h4>
                            <p><i class="fas fa-map-marker-alt"></i> Lapangan Tegallega</p>
                        </div>
                    </div>

                    <div class="event-card-mini">
                        <div class="card-image">
                            <img src="<?php echo base_url('FOTO/event2.jpg'); ?>" alt="Event">
                            <div class="date-tag">10 <br> <span>FEB</span></div>
                        </div>
                        <div class="card-details">
                            <h4>Workshop Angklung</h4>
                            <p><i class="fas fa-map-marker-alt"></i> Saung Angklung Udjo</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="section-wrapper">
                <div class="section-header section-header-inline section-header-gap-lg">
                    <h3 style="font-size: 1.2rem; color: #2d3436;"><i class="fas fa-graduation-cap" style="color: #3498db; margin-right: 10px;"></i>Materi Pembelajaran Baru</h3>
                    <a href="<?php echo site_url('belajar'); ?>" class="view-all-btn">Mulai Belajar</a>
                </div>

                <div class="horizontal-scroll-wrapper">
                    <div class="learning-card">
                        <div class="learning-icon" style="background: #e1f5fe;"><i class="fas fa-book-open" style="color: #03a9f4;"></i></div>
                        <div class="learning-info">
                            <h5>Arsitektur Art Deco</h5>
                            <p>Mengenal gaya bangunan tua di Bandung.</p>
                            <div class="progress-container">
                                <div class="progress-bar" style="width: 70%;"></div>
                            </div>
                            <small>70% Selesai</small>
                        </div>
                    </div>

                    <div class="learning-card">
                        <div class="learning-icon" style="background: #fff3e0;"><i class="fas fa-palette" style="color: #ff9800;"></i></div>
                        <div class="learning-info">
                            <h5>Sejarah Wayang Golek</h5>
                            <p>Tokoh dan filosofi di balik layar.</p>
                            <div class="progress-container">
                                <div class="progress-bar" style="width: 20%;"></div>
                            </div>
                            <small>20% Selesai</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="main-content">
        <div class="contribution-banner" style="background: linear-gradient(135deg, #27ae60, #2ecc71); border-radius: 20px; padding: 30px; margin-top: 30px; color: white; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <h2 style="margin: 0;">Bantu Lestarikan Bandung!</h2>
                <p style="opacity: 0.9; margin-top: 5px;">Punya foto Bandung atau info gedung bersejarah di Bandung? Bagikan di sini.</p>
            </div>
            <button style="background: white; color: #27ae60; border: none; padding: 12px 25px; border-radius: 10px; font-weight: bold; cursor: pointer; transition: 0.3s;">
                <i class="fas fa-upload"></i> Unggah Cerita
            </button>
        </div>
    </div>

    <footer>
        <div class="footer-container">
            <div class="footer-col">
                <h3>WeBandoo+</h3>
                <p>Platform digital pelestarian cagar budaya dan warisan sejarah Kota Bandung. Menghubungkan generasi muda dengan akar budaya tanah Pasundan.</p>
                <div class="social-links">
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-youtube"></i></a>
                </div>
            </div>

            <div class="footer-col">
                <h4>Navigasi</h4>
                <ul>
                    <li><a href="<?php echo site_url('dashboard'); ?>">Dashboard Utama</a></li>
                    <li><a href="<?php echo site_url('warisan'); ?>">Daftar Warisan</a></li>
                    <li><a href="<?php echo site_url('peta'); ?>">Eksplorasi Peta</a></li>
                    <li><a href="<?php echo site_url('event'); ?>">Agenda Budaya</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h4>Hubungi Kami</h4>
                <ul>
                    <li><a href="#"><i class="fas fa-map-marker-alt"></i> Jl. Telekomunikasi No.1</a></li>
                    <li><a href="#"><i class="fas fa-phone"></i> (022) 123-4567</a></li>
                    <li><a href="#"><i class="fas fa-envelope"></i> info@webandoo.id</a></li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; 2025 WeBandoo+ Warisan Bandung. All Rights Reserved.</p>
        </div>
    </footer>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-gesture-handling"></script>
    <script src="<?php echo base_url('assets/js/dashboard.js'); ?>"></script>
</body>
</html>
