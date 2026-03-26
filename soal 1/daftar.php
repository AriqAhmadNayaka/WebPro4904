<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Bergabung dengan Bandung Heritage</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* Definisi Warna Brand WeBandoo+ */
        :root {
            --primary: #4A8645;       /* Hijau Utama */
            --primary-light: #6ba366; /* Hijau Terang saat Hover */
            --accent: #E6B325;        /* Warna Emas/Aksen */
            --dark: #1a1a1a;          /* Warna Teks Gelap */
            --glass: rgba(255, 255, 255, 0.85); /* Efek Transparansi Kaca */
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }

        body {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #0f1710; /* Latar gelap agar blob warna lebih menyala */
            overflow: hidden;
            position: relative;
        }

        /* Blobs: Dekorasi lingkaran warna di latar belakang */
        .bg-blobs {
            position: absolute;
            width: 100%;
            height: 100%;
            z-index: -1;
            filter: blur(60px); /* Membuat lingkaran jadi blur/halus */
        }

        .blob {
            position: absolute;
            border-radius: 50%;
            opacity: 0.5;
            animation: move 20s infinite alternate; /* Animasi gerak lambat */
        }

        @keyframes move {
            from { transform: translate(0, 0) scale(1); }
            to { transform: translate(100px, 100px) scale(1.2); }
        }

        /* Glass Card: Kontainer utama dengan efek kaca (blur backdrop) */
        .glass-card {
            display: flex;
            width: 1050px;
            height: 680px;
            background: var(--glass);
            backdrop-filter: blur(20px); /* Efek blur tembus pandang */
            border-radius: 40px;
            border: 1px solid rgba(255, 255, 255, 0.4);
            box-shadow: 0 40px 100px rgba(0,0,0,0.4);
            overflow: hidden;
            animation: fadeIn 1s ease-out; /* Muncul perlahan saat load */
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Panel Kiri: Gambar dan Text Promosi */
        .visual-panel {
            width: 42%;
            background: linear-gradient(rgba(74, 134, 69, 0.6), rgba(26, 26, 26, 0.8)), url('gedung.png') center/cover;
            padding: 50px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            color: white;
            position: relative;
        }

        .visual-panel h2 { font-size: 42px; font-weight: 800; line-height: 1.1; }
        
        /* Badge status di pojok atas panel kiri */
        .badge {
            background: rgba(255, 255, 255, 0.2);
            padding: 8px 15px;
            border-radius: 50px;
            font-size: 12px;
            backdrop-filter: blur(5px);
            width: fit-content;
        }

        /* Panel Kanan: Area Formulir */
        .form-panel {
            width: 58%;
            padding: 60px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .form-header { margin-bottom: 35px; }
        .form-header h1 { font-size: 36px; color: var(--dark); margin-bottom: 10px; }

        /* Wrapper Input: Untuk mengatur posisi ikon di dalam input */
        .input-wrapper {
            position: relative;
            margin-bottom: 20px;
        }

        .input-wrapper i {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
            transition: 0.3s;
        }

        .input-wrapper input {
            width: 100%;
            padding: 16px 16px 16px 50px;
            border: 2px solid #eef2ef;
            border-radius: 16px;
            outline: none;
            font-size: 15px;
            background: #f8faf9;
            transition: 0.3s all ease;
        }

        /* Efek saat input diketik/fokus */
        .input-wrapper input:focus {
            border-color: var(--primary);
            background: #fff;
            box-shadow: 0 10px 20px rgba(74, 134, 69, 0.05);
        }

        .input-wrapper input:focus + i { color: var(--primary); }

        /* Strength Meter: Indikator kekuatan password */
        .strength-meter {
            height: 4px;
            width: 100%;
            background: #eef2ef;
            margin-top: -15px;
            margin-bottom: 20px;
            border-radius: 10px;
            overflow: hidden;
        }

        .strength-bar {
            height: 100%;
            width: 0%;
            transition: 0.5s;
        }

        /* Tombol Submit Premium */
        .btn-register {
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

        .btn-register:hover {
            transform: scale(1.02);
            background: var(--primary-light);
        }

        /* Responsive: Tampilan untuk HP */
        @media (max-width: 950px) {
            .glass-card { width: 95%; flex-direction: column; height: auto; border-radius: 25px; }
            .visual-panel { width: 100%; height: 200px; padding: 30px; }
            .form-panel { width: 100%; padding: 30px; }
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
            <div class="badge">#1 Bandung Heritage Platform</div>
            <div>
                <h2>Temukan <br><span style="color: var(--accent);">Nostalgia</span> Anda.</h2>
                <p style="margin-top: 20px;">Daftar hari ini untuk mendapatkan panduan eksklusif bangunan bersejarah di Bandung.</p>
            </div>
            <div style="font-size: 12px; opacity: 0.7;">© 2024 WeBandoo+ Technology</div>
        </div>

        <div class="form-panel">
            <div class="form-header">
                <h1>Daftar Akun</h1>
                <p>Silakan lengkapi formulir untuk memulai perjalanan.</p>
            </div>

            <form id="regForm" method="POST" action="proses_simpan.php">
                <div class="input-wrapper">
                    <i class="fa-solid fa-user-tag"></i>
                    <input type="text" name="nama" placeholder="Nama Lengkap" required>
                </div>
                
                <div class="input-wrapper">
                    <i class="fa-solid fa-envelope"></i>
                    <input type="email" name="email" placeholder="Alamat Email" required>
                </div>
                
                <div class="input-wrapper">
                    <i class="fa-solid fa-key"></i>
                    <input type="password" name="password" id="regPass" placeholder="Buat Password" required oninput="checkStrength(this.value)">
                </div>

                <div class="strength-meter">
                    <div id="strengthBar" class="strength-bar"></div>
                </div>

                <button type="submit" class="btn-register">
                    Buat Akun Sekarang <i class="fa-solid fa-arrow-right" style="margin-left: 10px;"></i>
                </button>
            </form>

            <div class="login-link">
                Sudah memiliki akun? <a href="index.php">Masuk Sekarang</a>
            </div>
        </div>
    </div>

    <script>
        /**
         * Fungsi untuk mengecek seberapa kuat password yang diketik.
         * Logic: Panjang karakter, adanya huruf besar, dan angka.
         */
        function checkStrength(password) {
            let strength = 0;
            const bar = document.getElementById('strengthBar');
            
            if (password.length >= 6) strength += 33;       // Syarat 1: Panjang min 6
            if (password.match(/[A-Z]/)) strength += 33;    // Syarat 2: Ada Huruf Besar
            if (password.match(/[0-9]/)) strength += 34;    // Syarat 3: Ada Angka

            bar.style.width = strength + "%";
            
            // Perubahan warna bar berdasarkan kekuatan
            if (strength <= 33) bar.style.background = "#ff4d4d";      // Lemah (Merah)
            else if (strength <= 66) bar.style.background = "#ffd633"; // Sedang (Kuning)
            else bar.style.background = "#4A8645";                     // Kuat (Hijau)
        }

        /**
         * Logika validasi dan animasi loading saat form dikirim
         */
        document.getElementById('regForm').addEventListener('submit', function(e) {
            // Kita tidak memanggil e.preventDefault() agar data tetap terkirim ke PHP
            const btn = document.querySelector('.btn-register');
            btn.style.pointerEvents = "none"; // Mencegah klik ganda
            btn.innerHTML = "Memproses... <i class='fa-solid fa-spinner fa-spin'></i>";
        });
    </script>
</body>
</html>
