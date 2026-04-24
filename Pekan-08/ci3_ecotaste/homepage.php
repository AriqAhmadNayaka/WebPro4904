<?php
session_start();
require 'database.php';

$db = new Database();

if (!isset($_SESSION['login'])) {
    header('Location: login.php');
    exit;
}

$queryKuliner = $db->getAllMenus();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>EcoTaste | Beranda</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        :root { --hijau-primer: #4CAF50; --hijau-latar: #F4F9F4; --hijau-gelap: #1B5E20; --putih: #FFFFFF; --kuning-emas: #FFC107; }
        body { font-family: 'Poppins', sans-serif; margin: 0; background-color: var(--hijau-latar); }
        
        .navbar { display: flex; justify-content: space-between; align-items: center; padding: 15px 5%; background: var(--putih); box-shadow: 0 2px 10px rgba(0,0,0,0.1); sticky; top: 0; z-index: 1000; }
        .logo { font-size: 24px; font-weight: 800; color: var(--hijau-primer); text-decoration: none; }
        
        /* Menu Navigasi Tengah */
        .nav-menu { display: flex; gap: 25px; }
        .nav-menu a { text-decoration: none; color: #555; font-weight: 600; transition: 0.3s; display: flex; align-items: center; gap: 8px; }
        .nav-menu a:hover { color: var(--hijau-primer); }

        .user-info { display: flex; align-items: center; gap: 15px; }
        .btn-logout { color: #ff5252; text-decoration: none; font-weight: bold; border: 1px solid #ff5252; padding: 5px 15px; border-radius: 20px; transition: 0.3s; }
        .btn-logout:hover { background: #ff5252; color: white; }

        .promo-section { background: linear-gradient(135deg, var(--hijau-primer), var(--hijau-gelap)); color: white; padding: 60px 20px; text-align: center; border-radius: 0 0 50px 50px; }
        
        .section-header { padding: 30px 5% 10px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; }
        .button-group { display: flex; gap: 10px; }

        .product-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 30px; padding: 20px 5%; }
        .product-card { background: white; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 20px rgba(0,0,0,0.05); text-align: center; padding-bottom: 20px; transition: 0.3s; }
        .product-card:hover { transform: translateY(-5px); }
        
        .card-image { height: 180px; width: 100%; background: #E8F5E9; display: flex; align-items: center; justify-content: center; overflow: hidden; }
        .card-image img { width: 100%; height: 100%; object-fit: cover; }
        
        .card-content { padding: 15px; }
        .card-rating-badge { color: var(--kuning-emas); font-weight: bold; margin: 10px 0; }
        
        .action-buttons { display: flex; justify-content: center; gap: 15px; margin-top: 15px; border-top: 1px solid #eee; padding-top: 15px; }
        .btn-edit { color: var(--hijau-primer); text-decoration: none; font-weight: bold; }
        .btn-hapus { color: #ff5252; text-decoration: none; font-weight: bold; }
        
        /* Tombol Spesial */
        .btn-tambah { background: var(--hijau-primer); color: white; text-decoration: none; padding: 10px 20px; border-radius: 12px; font-weight: bold; transition: 0.3s; }
        .btn-maps { background: #FF9800; color: white; text-decoration: none; padding: 10px 20px; border-radius: 12px; font-weight: bold; transition: 0.3s; display: flex; align-items: center; gap: 8px; }
        .btn-tambah:hover, .btn-maps:hover { opacity: 0.9; transform: scale(1.05); }
    </style>
</head>
<body>
    <nav class="navbar">
        <a href="homepage.php" class="logo"><i class="fas fa-leaf"></i> EcoTaste</a>
        
        <div class="nav-menu">
            <a href="homepage.php"><i class="fas fa-home"></i> Beranda</a>
            <a href="maps.php"><i class="fas fa-map-marked-alt"></i> Peta Lokasi</a>
        </div>

        <div class="user-info">
            <span>Halo, <b><?= htmlspecialchars($_SESSION['user'], ENT_QUOTES, 'UTF-8'); ?></b>!</span>
            <a href="logout.php" class="btn-logout">Keluar</a>
        </div>
    </nav>

    <header class="promo-section">
        <h1>Promo Hari Ini!</h1>
        <p>Kuliner ramah lingkungan terbaik di Bandung.</p>
    </header>

    <main>
        <div class="section-header">
            <h2>Rekomendasi Pilihan</h2>
            <div class="button-group">
                <a href="maps.php" class="btn-maps">
                    <i class="fas fa-map-marker-alt"></i> Lihat Peta
                </a>
                <a href="tambah_oop.php" class="btn-tambah">
                    <i class="fas fa-plus"></i> Tambah Menu
                </a>
            </div>
        </div>

        <div class="product-grid">
            <?php while ($row = mysqli_fetch_assoc($queryKuliner)) : ?>
                <article class="product-card">
                    <div class="card-image">
                        <?php if ($db->imageExists($row['gambar'])) : ?>
                            <img src="<?= htmlspecialchars($db->getImageWebPath($row['gambar']), ENT_QUOTES, 'UTF-8'); ?>" alt="<?= htmlspecialchars($row['nama'], ENT_QUOTES, 'UTF-8'); ?>">
                        <?php else : ?>
                            <i class="fas fa-utensils" style="font-size: 50px; color: var(--hijau-primer);"></i>
                        <?php endif; ?>
                    </div>
                    <div class="card-content">
                        <h3><?= htmlspecialchars($row['nama'], ENT_QUOTES, 'UTF-8'); ?></h3>
                        <p><?= htmlspecialchars($row['deskripsi'], ENT_QUOTES, 'UTF-8'); ?></p>
                        <div class="card-rating-badge">
                            <i class="fas fa-star"></i> <?= htmlspecialchars((string) $row['rating'], ENT_QUOTES, 'UTF-8'); ?>
                        </div>
                        <div class="action-buttons">
                            <a href="edit.php?id=<?= (int) $row['id']; ?>" class="btn-edit">Edit</a>
                            <a href="hapus.php?id=<?= (int) $row['id']; ?>" class="btn-hapus" onclick="return confirm('Apakah Anda yakin ingin menghapus menu ini?')">Hapus</a>
                        </div>
                    </div>
                </article>
            <?php endwhile; ?>
        </div>
    </main>
</body>
</html>