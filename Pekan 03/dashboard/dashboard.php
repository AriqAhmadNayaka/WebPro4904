<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DesaKita</title>
    <link rel="stylesheet" href="styles_dashboard.css">
    <script src="https://unpkg.com/feather-icons"></script>
</head>
<body>

<header class="navbar">
    <a href="dashboard.html" class="desakita">
    <img src="../asetgambar/logofix.png" alt="DesaKita Logo" class="logo">
    </a>
    <div class="nav-menu">
        <a href="../DAP TW1/pengumuman.html">Tentang Kami</a>
        <a href="../DAP TW1/login.html">Tenant UMKM</a>
    </div>
    <a href="../profilepage/profile.html" class="profil-link">
    <img src="pp-1.jpg" class="avatar">
    </a>
</header>

<div class="search-box">
    <input type="text" placeholder="cari tau tentang desa">
</div>

<section class="hero">
    <img src="1.jpg" alt="Hero">
</section>
    <!-- Memasukkan data statistik -->
     <!-- dengan input melalui form ph, sehingga data dapat diperbarui -->
      <!-- data di panggil dari variabel POST -->
    <div class="stats">
        <div class="stat">
            <?php 
                if (isset($_POST['jumlahASN'])) {
                 $jumlahASN = $_POST['jumlahASN'];
                echo "<p>Jumlah ASN</p>";
                echo "<h3>$jumlahASN<span>Orang</span></h3>";
                } else {
                $jumlahASN = 0; 
                echo "Data belum diisi.";
                }
            ?>
        </div>
        <div class="divider"></div>
        <div class="stat">
            <?php 
                if (isset($_POST['jumlahPenduduk'])) {
                 $jumlahPenduduk = $_POST['jumlahPenduduk'];
                echo "<p>Jumlah Penduduk</p>";
                echo "<h3>$jumlahPenduduk<span>Keluarga</span></h3>";
                } else {
                $jumlahPenduduk = 0; 
                echo "Data belum diisi.";
                }
            ?>
        </div>
        <div class="divider"></div>
        <div class="stat">
            <?php 
                if (isset($_POST['jumlahUMKM'])) {
                    $jumlahU = $_POST['jumlahUMKM'];
                    echo "<p>Jumlah Pelaku UMKM</p>";
                    echo "<h3>$jumlahU <span>Tenant</span></h3>";
                } else {
                    echo "Data belum diisi.";
                }
            ?>
        </div>
    </div>

<section class="intro">
    <div class="intro-text">
        <h1>Mari bersama membangun Desa</h1>
        <p>
            mari kembangkan produk UMKM desa bersama melalui program
            Tenant UMKM, dapatkan pelatihan online untuk meningkatkan
            kualitas produk anda.
        </p>
    </div>
    <a href="../DAP TW1/login.html">
    <button class="btn-primary">Daftar UMKM</button>
    </a>
</section>

<section class="services">
    <h1>Layanan Web</h1>

    <div class="services-content">
        <div class="info-card">
            <h2>Informasi Desa</h2>
            <p>
                Desa tanjung Beringin adalah salah satu desa yang memiliki potensi yang besar
                khususnya di bidang pariwisata dan UMKM. Dengan dukungan teknologi informasi,
                desa ini berkomitmen untuk meningkatkan kesejahteraan masyarakatnya melalui platform 
                digital masyarakat DesaKita.
            </p>
        </div>

        <div class="service-buttons">
            <a href="../laporan/laporan.html">
            <button class="service laporan"><i data-feather="alert-octagon"></i>Laporan</button>
            </a>
            <a href="../laporandanadesa/laporandana.html">
            <button class="service dana"><i data-feather="pie-chart"></i>Data Dana</button>
            </a>
            <a href="../DAP TW1/user.html">
            <button class="service umkm"><i data-feather="shopping-bag"></i>Tenant UMKM</button>
            </a>
        </div>
    </div>

    <div class="gallery">
        <img src="Pedagang-1.jpg">
        <img src="Pedagang-2.jpg">
        <img src="../asetgambar/2.jpg">
    </div>
</section>

<footer>
    <div class="footer-left">
        <img src="../asetgambar/logofix.png" alt="DesaKita Logo" class="footer-logo">
        <p>© Copyright DesaKita</p>
    </div>

    <div class="footer-mid">
        <h4>Fitur</h4>
        <a href="../DAP TW1/user.html">Tenant UMKM</a>
        <a href="../laporan/laporan.html">Laporan</a>
        <a href="../laporandanadesa/laporandana.html">Laporan APBD</a>
        <a href="../DAP TW1/berita.html">Tentang Kami</a>
    </div>

    <div class="footer-right">
        <h4>Alamat</h4>
        <p>
            Jl. Setia Negara, Desa. Tanjung Beringin,<br>
            Kecamatan Beringin, Kabupaten. Tapin,<br>
            Provinsi Sumatra Utara.
        </p>
    </div>
</footer>

<script>
      feather.replace();
    </script>

</body>
</html>
