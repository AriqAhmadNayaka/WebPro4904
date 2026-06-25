<?php require_once __DIR__ . '/partials/auth_guard.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Profile - WeBandoo</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link rel="stylesheet" href="assets/css/webandoo-enhancements.css">
<link rel="stylesheet" href="assets/css/webandoo-layout.css">
<script src="https://mcp.figma.com/mcp/html-to-design/capture.js" async></script>

<style>
/* Variabel Warna Tema Bandung Heritage (Dioptimalkan) */
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

.settings-content {
    background-color: var(--color-white);
    padding: 30px;
    border-radius: 12px;
    box-shadow: var(--shadow-elevation);
}

.settings-content h2 {
    font-size: 24px;
    font-weight: 600;
    margin-bottom: 5px;
    color: var(--color-primary-green);
}

.settings-content .subtitle {
    color: var(--color-text-grey);
    margin-bottom: 30px;
    border-bottom: 1px solid #eee;
    padding-bottom: 15px;
}

.form-group {
    margin-bottom: 25px;
}

.form-group label {
    display: block;
    font-weight: 600;
    margin-bottom: 8px;
    color: var(--color-text-dark);
    font-size: 15px;
}

.form-group input[type="text"],
.form-group input[type="email"],
.form-group input[type="password"] {
    width: 100%;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 16px;
    transition: border-color 0.3s;
}

.form-group input:focus {
    border-color: var(--color-primary-green);
    outline: none;
    box-shadow: 0 0 5px rgba(74, 134, 69, 0.2);
}

.btn-save {
    padding: 12px 30px;
    background-color: var(--color-primary-green);
    color: var(--color-white);
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: background-color 0.3s;
    float: right;
}

.btn-save:hover {
    background-color: #3e7039;
}

.profile-feature-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 22px;
    margin-top: 26px;
}

.profile-panel {
    background: white;
    border-radius: 12px;
    box-shadow: var(--shadow-elevation);
    padding: 24px;
}

.profile-panel h2 {
    color: var(--color-primary-green);
    margin-bottom: 14px;
}

.saved-place, .review-item {
    padding: 14px 0;
    border-bottom: 1px solid #edf0ed;
}

.saved-place:last-child, .review-item:last-child {
    border-bottom: none;
}

.saved-place h3, .review-item h3 {
    font-size: 16px;
    margin-bottom: 6px;
}

.saved-place p, .review-item p {
    color: var(--color-text-grey);
    font-size: 13px;
    line-height: 1.6;
}

.rating {
    color: #f1c40f;
    font-weight: 700;
    margin-bottom: 6px;
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
            <h1>Pengaturan Akun</h1>
        </div>

        <div class="settings-content">
            <h2><i class="fas fa-user-edit"></i> Edit Profil</h2>
            <p class="subtitle">Perbarui foto dan detail pribadi akun Anda.</p>
            
            <form data-profile-form>
                
                <div class="form-group" style="display: flex; align-items: center; margin-bottom: 30px;">
                    <div class="avatar" style="width: 80px; height: 80px; border-width: 4px; margin-right: 20px;">
                        <img src="Raihan.jpg" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover;"> 
                    </div>
                    <div>
                        <label style="margin-bottom: 5px;">Foto Profil</label>
                        <input type="file" style="border: none; padding: 0;">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="nama">Nama Lengkap</label>
                    <input type="text" id="nama" name="nama_lengkap">
                </div>
                
                <div class="form-group">
                    <label for="email">Alamat Email</label>
                    <input type="email" id="email" name="email">
                </div>
                
                <div class="form-group">
                    <label for="kota">Kota/Domisili</label>
                    <input type="text" id="kota" name="kota">
                </div>

                <div style="overflow: auto;">
                    <button type="submit" class="btn-save">Simpan</button>
                </div>
            </form>
        </div>

        <div class="profile-feature-grid">
            <section class="profile-panel">
                <h2><i class="fas fa-heart"></i> Tempat Disimpan</h2>
                <div class="saved-place">
                    <h3>Jalan Braga</h3>
                    <p>Wishlist akhir pekan, cocok untuk spot foto malam dan kuliner ringan.</p>
                </div>
                <div class="saved-place">
                    <h3>Kopi Mandja Progo</h3>
                    <p>Tempat nongkrong dengan WiFi, outdoor area, dan estimasi biaya Rp25.000 - Rp75.000.</p>
                </div>
                <div class="saved-place">
                    <h3>Festival Asia Afrika</h3>
                    <p>Disimpan ke daftar event budaya bulan ini.</p>
                </div>
            </section>

            <section class="profile-panel">
                <h2><i class="fas fa-star"></i> Review Saya</h2>
                <div data-my-review-list>
                    <p class="subtitle">Memuat review dari database...</p>
                </div>
            </section>
        </div>
    </div>

<script src="assets/js/webandoo-layout.js"></script>
<script src="assets/js/webandoo-app.js"></script>
<script src="assets/js/auth.js"></script>
</body>
</html>


