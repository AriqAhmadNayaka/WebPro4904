<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Login - WeBandoo+</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary: #4A8645;
            --primary-light: #6ba366;
            --accent: #E6B325;
            --dark: #1a1a1a;
            --glass: rgba(255, 255, 255, 0.85);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #0f1710;
            overflow: hidden;
            position: relative;
        }

        /* --- BACKGROUND BLOBS --- */
        .bg-blobs {
            position: absolute;
            width: 100%;
            height: 100%;
            z-index: -1;
            filter: blur(60px);
        }

        .blob {
            position: absolute;
            border-radius: 50%;
            opacity: 0.4;
            animation: move 20s infinite alternate;
        }

        @keyframes move {
            from { transform: translate(0, 0) scale(1); }
            to { transform: translate(120px, 80px) scale(1.2); }
        }

        /* --- CONTAINER --- */
        .glass-card {
            display: flex;
            width: 1000px;
            height: 600px;
            background: var(--glass);
            backdrop-filter: blur(20px);
            border-radius: 40px;
            border: 1px solid rgba(255, 255, 255, 0.4);
            box-shadow: 0 40px 100px rgba(0,0,0,0.4);
            overflow: hidden;
            animation: fadeIn 1s ease-out;
            position: relative;
            z-index: 1;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* --- PANEL KIRI (SLIDESHOW) --- */
        .visual-panel {
            width: 42%;
            position: relative;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 50px;
            overflow: hidden;
        }

        #slideshow-bg {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background-size: cover;
            background-position: center;
            transition: opacity 1s ease-in-out;
            z-index: 0;
        }

        .visual-panel::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
            z-index: 1;
        }

        .image-content {
            position: relative;
            z-index: 2;
        }

        .image-content h3 { font-size: 28px; font-weight: 800; margin-bottom: 10px; }
        .image-content p { font-size: 15px; opacity: 0.9; line-height: 1.5; }

        /* --- PANEL KANAN (FORM) --- */
        .form-panel {
            width: 58%;
            padding: 60px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .logo-header { text-align: right; margin-bottom: 20px; }
        .logo-header span { font-size: 20px; font-weight: 800; color: var(--primary); }

        .form-header h1 { font-size: 36px; color: var(--dark); margin-bottom: 8px; }
        .form-header p { color: #666; font-size: 15px; margin-bottom: 35px; }

        /* --- INPUT STYLES --- */
        .input-wrapper {
            position: relative;
            margin-bottom: 20px;
        }

        .input-wrapper i:not(.toggle-pass) {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
            transition: 0.3s;
            z-index: 2;
        }

        .input-wrapper input {
            width: 100%;
            padding: 16px 50px 16px 50px; /* Padding kanan ditambah untuk ikon mata */
            border: 2px solid #eef2ef;
            border-radius: 16px;
            outline: none;
            font-size: 15px;
            background: #f8faf9;
            transition: 0.3s all ease;
            position: relative;
            z-index: 1;
        }

        .input-wrapper input:focus {
            border-color: var(--primary);
            background: #fff;
            box-shadow: 0 10px 20px rgba(74, 134, 69, 0.05);
        }

        /* PERBAIKAN DI SINI: Ikon mata bisa diklik */
        .toggle-pass {
            position: absolute;
            right: 18px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #999;
            z-index: 10; /* Menaruh ikon di atas input */
            padding: 5px; /* Memperluas area klik */
        }

        .toggle-pass:hover { color: var(--primary); }

        .forgot-link {
            text-align: right;
            margin-bottom: 25px;
        }

        .forgot-link a {
            font-size: 13px;
            color: #666;
            text-decoration: none;
            transition: 0.3s;
        }

        .forgot-link a:hover { color: var(--primary); }

        /* --- BUTTON --- */
        .btn-login {
            width: 100%;
            padding: 18px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 18px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 15px 30px rgba(74, 134, 69, 0.25);
        }

        .btn-login:hover {
            transform: scale(1.02);
            background: var(--primary-light);
            box-shadow: 0 20px 40px rgba(74, 134, 69, 0.35);
        }

        .register-footer {
            text-align: center;
            margin-top: 30px;
            font-size: 14px;
            color: #555;
        }

        .register-footer a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 700;
        }

        @media (max-width: 900px) {
            .glass-card { width: 95%; flex-direction: column; height: auto; margin: 20px 0; }
            .visual-panel { display: none; }
            .form-panel { width: 100%; padding: 40px 30px; }
        }
    </style>
</head>

<body>

    <div class="bg-blobs">
        <div class="blob" style="width: 500px; height: 500px; background: var(--primary); top: -100px; left: -100px;"></div>
        <div class="blob" style="width: 400px; height: 400px; background: var(--accent); bottom: -100px; right: -100px; animation-delay: -5s;"></div>
    </div>

    <div class="glass-card">
        <div class="visual-panel">
            <div id="slideshow-bg"></div>
            <div class="image-content">
                <h3 id="slide-title">Warisan Bandung</h3>
                <p id="slide-description">Temukan sejarah dan budaya yang kaya di Kota Kembang.</p>
            </div>
        </div>

        <div class="form-panel">
            <div class="logo-header">
                <span>WeBandoo+</span>
            </div>

            <div class="form-header">
                <h1>Selamat Datang</h1>
                <p>Masuk untuk menjelajahi keindahan Bandung.</p>
            </div>

            <form id="loginForm">
                <div class="input-wrapper">
                    <i class="fa-solid fa-envelope"></i>
                    <input type="email" id="email" placeholder="Email" required>
                </div>

                <div class="input-wrapper">
                    <i class="fa-solid fa-lock"></i>
                    <input type="password" id="password" placeholder="Password" required>
                    <i class="fa-regular fa-eye toggle-pass" id="togglePassword"></i>
                </div>

                <div class="forgot-link">
                    <a href="#">Lupa Kata Sandi?</a>
                </div>

                <button type="submit" class="btn-login">
                    Masuk Sekarang <i class="fa-solid fa-arrow-right-to-bracket" style="margin-left: 10px;"></i>
                </button>
            </form>

            <div class="register-footer">
                Belum memiliki akun? <a href="daftar.php">Daftar Sekarang</a>
            </div>
        </div>
    </div>

    <script>
        // --- SLIDESHOW LOGIC ---
        const slides = [
            { imageUrl: 'https://upload.wikimedia.org/wikipedia/commons/thumb/4/4c/Gedung_Sate_Oktober_2024_-_Rahmatdenas.jpg/1200px-Gedung_Sate_Oktober_2024_-_Rahmatdenas.jpg', title: "Jelajahi Warisan", description: "Arsitektur kolonial yang menyimpan ribuan cerita bersejarah." },
            { imageUrl: 'https://www.shutterstock.com/image-photo/bandung-indonesia-18-october-2023-600nw-2381176429.jpg', title: "Asia Afrika", description: "Saksi bisu konferensi bersejarah dunia ada di sini." },
            { imageUrl: 'https://cozzy.id/uploads/0000/630/2024/07/24/cozzyid-hotel-murah-hotel-terdekat-penginapan-murah-penginapan-terdekat-booking-hotel-braga-bandung-pesona-modern-di-jalan-legendaris-sumber-gambar.jpg', title: "Pesona Braga", description: "Suasana Paris Van Java yang tak lekang oleh waktu." }
        ];

        const slideshowElement = document.getElementById('slideshow-bg');
        const titleElement = document.getElementById('slide-title');
        const descriptionElement = document.getElementById('slide-description');
        let currentIndex = 0;

        function changeSlide() {
            const activeSlide = slides[currentIndex];
            slideshowElement.style.opacity = 0;
            setTimeout(() => {
                slideshowElement.style.backgroundImage = `url('${activeSlide.imageUrl}')`;
                titleElement.textContent = activeSlide.title;
                descriptionElement.textContent = activeSlide.description;
                slideshowElement.style.opacity = 1;
            }, 500);
            currentIndex = (currentIndex + 1) % slides.length;
        }
        setInterval(changeSlide, 4000);
        changeSlide();

        // --- PASSWORD TOGGLE LOGIC ---
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');

        togglePassword.addEventListener('click', function () {
            // Cek tipe input saat ini
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            // Ganti ikon mata
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });

        // --- LOGIN VALIDATION ---
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            // Mengambil data dari localStorage (asumsi sudah daftar sebelumnya)
            const storedEmail = localStorage.getItem('userEmail');
            const storedPass = localStorage.getItem('userPass');

            if (!storedEmail) {
                alert("Akun tidak ditemukan. Silakan daftar terlebih dahulu.");
            } else if (email === storedEmail && password === storedPass) {
                alert("Berhasil Masuk! Selamat datang kembali.");
                window.location.href = "home page.php";
            } else {
                alert("Email atau Password salah!");
            }
        });
    </script>
</body>

</html>