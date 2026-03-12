<?php
// Mengecek apakah parameter 'username' ada di URL menggunakan metode GET
if (!isset($_GET['username'])) {
    // Jika parameter 'username' tidak ada, maka pengguna akan diarahkan kembali ke halaman login
    header("Location: TugasPraktikum1login.php");
    // Menghentikan eksekusi script setelah melakukan redirect
    exit();
}

// Mengecek apakah parameter 'username' ada di URL menggunakan metode GET
$username = $_GET['username'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navibis</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: #f6f7fb;
            color: #333;
            min-height: 100vh;
            background-color: #ebfbfa;

            background-image:
                radial-gradient(circle at 10% 100%, rgba(5, 12, 87, 0.607), rgba(70, 79, 184, 0.281), transparent 40%),


                radial-gradient(circle at 100% 10%,
                    rgba(60, 252, 242, 0.42),
                    rgba(207, 244, 248, 0.756),
                    transparent 60%);

            background-repeat: no-repeat;
            background-attachment: fixed;
        }

        .top-navbar {
            position: fixed;
            top: 20px;
            left: 145px;
            right: 20px;
            height: 60px;
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border-radius: 30px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: var(--shadow);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 24px;
            z-index: 100;
            transition: var(--transition);
        }

        .nav-left {
            display: flex;
            align-items: center;
        }

        .logo-text {
            font-size: 24px;
            font-weight: 700;
            background: linear-gradient(135deg, #6b5cff, #8D6DFD);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .nav-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .nav-icon {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            border: none;
            background: rgba(255, 255, 255, 0.1);
            display: grid;
            place-items: center;
            cursor: pointer;
            transition: var(--transition);
            position: relative;
        }

        .nav-icon:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(141, 109, 253, 0.3);
        }

        .notif-badge {
            width: 20px;
            height: 20px;
            color: #6b5cff;
            stroke-width: 2;
        }

        .badge {
            position: absolute;
            top: 4px;
            right: 4px;
            background: #ff4757;
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 11px;
            font-weight: 600;
            display: grid;
            place-items: center;
        }

        .avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        .profile-dropdown {
            position: relative;
        }

        .dropdown-menu {
            position: absolute;
            top: 56px;
            right: 0;
            min-width: 220px;
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: var(--shadow);
            opacity: 0;
            visibility: hidden;
            transform: translateY(-8px);
            transition: var(--transition);
            z-index: 101;
        }

        .profile-dropdown:hover .dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .profile-info {
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .avatar-lg {
            width: 48px !important;
            height: 48px !important;
        }

        .name {
            margin: 0;
            font-weight: 600;
            color: #333;
        }

        .email {
            margin: 0;
            font-size: 14px;
            color: #666;
        }

        .dropdown-item {
            display: block;
            padding: 12px 20px;
            color: #333;
            text-decoration: none;
            font-size: 14px;
            transition: var(--transition);
        }

        .dropdown-item:hover {
            background: rgba(107, 92, 255, 0.1);
            color: #6b5cff;
        }

        .logout {
            color: #ff4757 !important;
        }

        .logout:hover {
            background: rgba(255, 71, 87, 0.1) !important;
        }

        .side-floating-nav {
            position: fixed;
            top: 100px;
            left: 25px;
            z-index: 99;
        }

        .side-rail {
            width: 50px;
            height: 460px;
            padding: 9px;
            border-radius: 40px;
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            box-shadow: var(--shadow);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 5px;
        }

        .side-top {
            margin-bottom: 20px;
            gap: 5px;
        }

        .side-bottom {
            margin-top: 20px;
            gap: 5px;
        }

        .icon-pill {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            display: grid;
            place-items: center;
            background: transparent;
            border: none;
            cursor: pointer;
            transition: var(--transition);
            font-size: 1.1rem;
        }

        .icon-pill:hover,
        .icon-pill.active {
            background: transparent;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(141, 109, 253, 0.3);
        }

        .icon-image {
            width: 20px;
            height: 20px;
            object-fit: contain;
            object-position: center;
            display: block;
            background: transparent;
            border: none;
            cursor: pointer;
            transition: var(--transition);
        }

        .login-options {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
            margin: 50px 0;
        }

        .login-row {
            display: flex;
            justify-content: center;
            gap: 30px;
            flex-wrap: wrap;

        }

        .login-option {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
            width: 150px;
        }

        .circle-video {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            border: 3px solid #956df3;
            object-fit: cover;
            background: #fff;
            cursor: pointer;
            transition: 0.3s ease;
            padding: 20px;
        }

        .circle-video:hover {
            border-color: #3a71ff;
            box-shadow: 0 4px 15px rgba(149, 109, 243, 0.5);
            transform: scale(1.05);
        }

        .login-option label {
            font-size: 14px;
            color: #956df3;
            text-align: center;
            font-weight: 500;
        }

        .chat-section {
            display: flex;
            justify-content: center;
            gap: 200px;
            margin-top: 50px;
            margin-bottom: 50px;
        }

        .chat-option {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 180px;
            gap: 15px;
        }

        .kotak-container {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            width: 90%;
            margin: 0 auto;
        }

        .kotak-option {
            width: 300px;
            height: 120px;
            border-radius: 16px;
            border: 2px solid #956df3;
            overflow: hidden;
            object-fit: contain;
            background: white;
            cursor: pointer;
            transition: 0.3s ease;
        }

        .kotak-option:hover {
            border-color: #3a71ff;
            box-shadow: 0 4px 15px rgba(149, 109, 243, 0.5);
            transform: scale(1.03);
        }

        .chat-option label {
            font-size: 15px;
            font-weight: 600;
            color: #956df3;
        }

        .slider {
            position: relative;
            width: 100%;
            max-width: 1050px;
            margin: 120px auto 40px;
            padding: 0 8px;
            overflow: hidden;
        }

        .slide {
            display: none;
            justify-content: center;
        }

        .slide.active {
            display: flex;
        }

        .pamflet-card {
            background: white;
            border-radius: 20px;
            padding: 20px;
            width: 100%;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .pamflet-card img {
            width: 100%;
            max-height: 600px;
            object-fit: contain;
            border-radius: 14px;
        }

        .prev,
        .next {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: #956df3;
            color: white;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
            border-radius: 50%;
        }

        @media (max-width: 768px) {
            .slider {
                margin: 100px auto 30px;
                padding: 0 10px;
            }

            .pamflet-card {
                padding: 14px;
            }

            .pamflet-card img {
                max-height: 260px;
            }

            .prev,
            .next {
                padding: 6px 10px;
            }
        }

        .prev {
            left: 10px;
        }

        .next {
            right: 10px;
        }

        .theme-toggle {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 1.2rem;
            padding: 5px;
            color: #f8f9fa;
        }
    </style>
</head>

<body class="background">
    <nav class="top-navbar">
        <div class="nav-left">
            <div class="logo">
                <span class="logo-text">NaviBiz</span>
            </div>
        </div>

        <div class="nav-right">
            <button class="nav-icon notif-icon" aria-label="Notifikasi">
                <svg viewBox="0 0 24 24" class="notif-badge">
                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9z" />
                    <path d="M13.73 21a2 2 0 0 1-3.46 0" />
                </svg>
                <span class="badge">3</span>
            </button>

            <div class="profile-dropdown">
                <button class="nav-icon profile-icon" aria-label="Profil">
                    <img src="profil.jpeg" alt="Profil" class="avatar">
                </button>
                <div class="dropdown-menu">
                    <div class="profile-info">
                        <img src="profil.jpeg" alt="Aila" class="avatar-lg">
                        <div>
                            <p class="name">Aila</p>
                            <p class="email">aila@navibiz.com</p>
                        </div>
                    </div>
                    <a href="profile.html" class="dropdown-item">Profil</a>
                    <a href="settings.html" class="dropdown-item">Pengaturan</a>
                    <hr>
                    <a href="login.html" class="dropdown-item logout">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <nav class="side-floating-nav" aria-label="Navigasi Utama">
        <div class="side-rail">
            <div class="side-top">
                <a href="konsultasiai.html">
                    <button class="icon-pill" aria-label="AI Assistant">
                        <img class="icon-image" src="chat-bot-removebg-preview (1).png" alt="">
                    </button>
                </a>
                <a href="komunitas.html">
                    <button class="icon-pill" aria-label="Komunitas">
                        <img class="icon-image" src="komunitas_icon-removebg-preview.png" alt="">
                    </button>
                </a>
            </div>

            <div class="side-icons">
                <a href="petaumkm.html">
                    <button class="icon-pill" aria-label="Peta UMKM">
                        <img class="icon-image" src="lokasi_icon-removebg-preview.png" alt="">
                    </button>
                </a>
                <a href="kategori.html">
                    <button class="icon-pill" aria-label="Jenis UMKM">
                        <img class="icon-image" src="shop_icon-removebg-preview.png" alt="">
                    </button>
                </a>
                <a href="delivery.html">
                    <button class="icon-pill" aria-label="Delivery">
                        <img class="icon-image" src="fast-delivery_icon-removebg-preview.png" alt="">
                    </button>
                </a>
                <a href="pesan.html">
                    <button class="icon-pill" aria-label="Pesan">
                        <img class="icon-image" src="chat_icon-removebg-preview.png" alt="">
                    </button>
                </a>
                <a href="pembukaan-investasi.html">
                    <button class="icon-pill" aria-label="Investasi">
                        <img class="icon-image" src="invest_icon-removebg-preview.png" alt="">
                    </button>
                </a>
            </div>

            <div class="side-bottom">
                <a href="pengaturan.html">
                    <button class="icon-pill" aria-label="Pengaturan">
                        <img class="icon-image" src="pengaturan_icon-removebg-preview.png" alt="">
                    </button>
                </a>
                <a href="logout.html">
                    <button class="icon-pill" aria-label="Logout">
                        <img class="icon-image" src="keluar_icon-removebg-preview.png" alt="">
                    </button>
                </a>
            </div>
        </div>
    </nav>

    <div class="slider" id="Dashboard">

        <div class="slide active">
            <div class="pamflet-card">
                <img src="3.png" alt="Pamflet 1">
                <div class="pamflet-info">
                    <h3>Pelatihan UMKM Lokal</h3>
                    <p>Dukung UMKM sekitar dengan fitur peta interaktif NaviBiz.</p>
                </div>
            </div>
        </div>

        <div class="slide">
            <div class="pamflet-card">
                <img src="2.png" alt="Pamflet 2">
                <div class="pamflet-info">
                    <h3>Investasi Mudah</h3>
                    <p>Pantau peluang investasi UMKM secara transparan.</p>
                </div>
            </div>
        </div>

        <div class="slide">
            <div class="pamflet-card">
                <img src="1.png" alt="Pamflet 3">
                <div class="pamflet-info">
                    <h3>Kelola Bisnis Digital</h3>
                    <p>Laporan keuangan dan analitik dalam satu aplikasi.</p>
                </div>
            </div>
        </div>

        <button class="prev">❮</button>
        <button class="next">❯</button>
    </div>

    <div class="login-options">
        <div class="login-row">
            <a href="petaumkm.html" style="text-decoration: none;">
                <div class="login-option">
                    <video class="circle-video" autoplay loop muted playsinline>
                        <source src="peta.mp4" type="video/mp4">
                    </video>
                    <label>Peta UMKM</label>
                </div>
            </a>

            <a href="investasi.html" style="text-decoration: none;">
                <div class="login-option">
                    <video class="circle-video" autoplay loop muted playsinline>
                        <source src="investasi saham.mp4" type="video/mp4">
                    </video>
                    <label>Investasi</label>
                </div>
            </a>

            <a href="kategori.html" style="text-decoration: none;">
                <div class="login-option">
                    <video class="circle-video" autoplay loop muted playsinline>
                        <source src="umkm.mp4" type="video/mp4">
                    </video>
                    <label>Jenis UMKM</label>
                </div>
            </a>

            <div class="login-row">
                <a href="delivery.html" style="text-decoration: none;">
                    <div class="login-option">
                        <video class="circle-video" autoplay loop muted playsinline>
                            <source src="delivery.mp4" type="video/mp4">
                        </video>
                        <label>Delivery</label>
                    </div>
                </a>

                <a href="dompet.php" style="text-decoration: none;">
                    <div class="login-option">
                        <video class="circle-video" autoplay loop muted playsinline>
                            <source src="dompet.mp4" type="video/mp4">
                        </video>
                        <label>Laporan Keuangan</label>
                    </div>
                </a>

                <a href="pesan.html" style="text-decoration: none;">
                    <div class="login-option">
                        <video class="circle-video" autoplay loop muted playsinline>
                            <source src="pesan.mp4" type="video/mp4">
                        </video>
                        <label>Pesan</label>
                    </div>
                </a>
            </div>
        </div>


        <a href="konsultasiai.html" style="text-decoration: none;">
            <div class="chat-section">
                <div class="chat-option">
                    <video class="kotak-option" autoplay loop muted playsinline>
                        <source src="ai asisten.mp4" type="video/mp4">
                    </video>
                    <label>AI Asisten</label>
                </div>
        </a>

        <a href="komunitas.html" style="text-decoration: none;">
            <div class="chat-option">
                <video class="kotak-option" autoplay loop muted playsinline>
                    <source src="komunitas.mp4" type="video/mp4">
                </video>
                <label>Komunitas</label>
            </div>
        </a>
    </div>

    <script>
        function toggleMenu() {
            const menu = document.getElementById('hamburgerMenu');
            const overlay = document.getElementById('overlay');
            menu.classList.toggle('open');
            overlay.classList.toggle('show');
        }

        let index = 0;
        const slides = document.querySelectorAll('.slide');

        function showSlide(i) {
            slides.forEach((slide) => slide.classList.remove('active'));
            slides[i].classList.add('active');
        }

        document.querySelector('.next').onclick = () => {
            index = (index + 1) % slides.length;
            showSlide(index);
        }

        document.querySelector('.prev').onclick = () => {
            index = (index - 1 + slides.length) % slides.length;
            showSlide(index);
        }

    </script>
</body>

</html>