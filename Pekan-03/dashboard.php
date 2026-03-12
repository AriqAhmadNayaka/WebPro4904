<?php
$nama_dokter = "Dr. Andi"; // Variabel untuk menyimpan nama dokter yang akan ditampilkan di sidebar
$profile_pic = "calm pfp.jpg"; // Variabel untuk menyimpan path foto profil dokter
$current_page = basename($_SERVER['PHP_SELF']); // Variabel untuk menyimpan nama file halaman saat ini, digunakan untuk menandai menu aktif
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LifeTrack - Beranda</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: #f5f7fa;
            color: #333;
        }

        .layout {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 260px;
            background: white;
            border-right: 1px solid #e5e8ea;
            padding: 20px;
            position: sticky;
            top: 0;
            height: 100vh;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 25px;
        }

        .bulb {
            width: 16px;
            height: 16px;
            background: #f7c948;
            border-radius: 50%;
        }

        .brand h2 {
            font-size: 20px;
            color: #444;
        }

        .profile {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px;
            border-radius: 12px;
            border: 1px solid #e5e8ea;
            background: #fff;
            margin-bottom: 20px;
        }

        .profile img {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
            background: #e5e8ea;
        }

        .profile span {
            font-weight: 600;
            color: #444;
        }

        .menu {
            list-style: none;
            display: grid;
            gap: 10px;
        }

        .menu a {
            display: block;
            text-decoration: none;
            color: #333;
            font-weight: 500;
            padding: 12px 12px;
            border-radius: 12px;
            transition: 0.3s ease;
        }

        .menu a:hover {
            color: #52a8a0;
            background: #f3fbfa;
        }

        .menu a.active {
            background: #eaf6f4;
            color: #448b84;
            border: 1px solid #cfe9e5;
        }

        .main {
            flex: 1;
            padding: 22px;
        }

        .container {
            width: 95%;
            margin: 0 auto;
        }

        .hero {
            padding: 10px 0;
        }

        .hero h1 {
            font-size: 40px;
            margin-bottom: 5px;
            color: #444;
        }

        .hero p {
            margin-top: 4px;
            color: #4b5752;
        }

        .section-info {
            margin-top: 20px;
            padding: 24px;
            border-radius: 14px;
            border: 1px solid #e5e8ea;
            background: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        .left {
            flex: 1;
        }

        .left h2 {
            margin-bottom: 8px;
            color: #444;
            font-size: 26px;
        }

        .left p {
            color: #4b5752;
            line-height: 1.5;
        }

        .right {
            flex: 1;
            display: flex;
            justify-content: center;
        }

        .right img {
            width: 420px;
            max-width: 100%;
        }

        .boxes {
            display: flex;
            gap: 20px;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        .box {
            flex: 1;
            min-width: 220px;
            padding: 15px;
            border-radius: 14px;
            border: 1px solid #e5e8ea;
            background: white;
        }

        .box p {
            color: #4b5752;
        }

        .box h2 {
            margin: 0 0 6px 0;
            font-size: 26px;
            margin-top: 14px;
            margin-bottom: 10px;
            color: #444;
        }

        .btn {
            margin-top: 10px;
            padding: 10px 16px;
            background: #448b84;
            border: none;
            color: white;
            border-radius: 10px;
            cursor: pointer;
            font-size: 15px;
            transition: 0.5s ease;
        }

        .btn:hover {
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
            transform: translateY(-1px);
        }

        a {
            text-decoration: none;
        }

        @media (max-width: 900px) {
            .layout {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
                border-right: none;
                border-bottom: 1px solid #e5e8ea;
            }

            .container {
                width: 100%;
            }

            .section-info {
                flex-direction: column;
                align-items: flex-start;
            }
        }

        footer {
            background: #0f766e;
            color: white;
            text-align: center;
            padding: 24px;
            margin-top: 40px;
        }
    </style>
</head>

<body>
    <div class="layout">
        <aside class="sidebar">
            <div class="brand">
                <span class="bulb"></span>
                <h2>LifeTrack</h2>
            </div>

            <div class="profile">
                <img src="<?php echo $profile_pic; ?>" alt="User">
                <!-- Menampilkan foto profil menggunakan variabel $profile_pic -->
                <span><?php echo $nama_dokter; ?></span>
            </div>

            <ul class="menu">
                <li><a class="<?php echo ($current_page == 'index.php' || $current_page == 'dashboard.php') ? 'active' : ''; ?>" href="dashboard.php">Beranda</a></li>
                <li><a href="datapasien.php">Data Pasien</a></li>
                <li><a href="jadwalkontrol.php">Jadwal Kontrol</a></li>
                <li><a href="rekomendasi_dokter.php">Rekomendasi Kegiatan</a></li>
                <li><a href="nutrisidokter.php">Nutrisi dan Gizi</a></li>
                <li><a href="layanan_tenagamedis.php">Layanan Informasi</a></li>
                <li><a href="kontak.php">Kontak</a></li>
            </ul>
        </aside>

        <main class="main">
            <div class="container">
                <section class="hero">
                    <h1>Halo, <?php echo $nama_dokter; ?>!</h1>
                    <p>Selamat datang kembali di panel kontrol Anda.</p>

                    <section class="section-info">
                        <div class="left">
                            <h2>Pemantauan Pasca Perawatan</h2>
                            <p>Jaga kualitas hidup pasien dengan menjadwalkan kontrol berkala dan memantau perkembangan harian secara real-time.</p>
                        </div>

                        <div class="right">
                            <img src="doctorilustration.png" alt="Ilustrasi Dokter">
                        </div>
                    </section>

                    <div class="boxes">
                        <div class="box">
                            <h2>Input Data Pasien</h2>
                            <p>Manajemen dan tambah data rekam medis pasien.</p>
                            <a href="datapasien.php"><button class="btn">Lihat Detail</button></a>
                        </div>

                        <div class="box">
                            <h2>Jadwal</h2>
                            <p><?php echo date("d F Y"); // Menampilkan tanggal hari ini secara otomatis ?></p>
                            <a href="jadwalkontrol.php"><button class="btn">Lihat Detail</button></a>
                        </div>

                        <div class="box">
                            <h2>Rekomendasi</h2>
                            <p>Berikan panduan aktivitas fisik bagi pasien.</p>
                            <a href="rekomendasi_dokter.php"><button class="btn">Lihat Detail</button></a>
                        </div>

                        <div class="box">
                            <h2>Nutrisi & Gizi</h2>
                            <p>Atur pola makan sehat untuk pemulihan pasien.</p>
                            <a href="nutrisidokter.php"><button class="btn">Lihat Detail</button></a>
                        </div>
                    </div>
                </section>
            </div>
        </main>
    </div>

    <footer>
        LifeTrack © 2025 — Membantu Anda hidup lebih sehat.
    </footer>
</body>
</html>