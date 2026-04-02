<?php
session_start();
require 'koneksi.php';

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

// Ambil data dari tabel waste_reports
$query = "SELECT * FROM waste_reports ORDER BY id DESC";
$result = mysqli_query($conn, $query);

// Proteksi agar line 15 tidak error jika tabel belum ada
if (!$result) {
    echo "<div style='color:red; padding:20px;'>Error Database: " . mysqli_error($conn) . "</div>";
    echo "<p>Pastikan tabel <b>waste_reports</b> sudah dibuat di phpMyAdmin.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoTaste | Beranda Kuliner Bandung</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --hijau-primer: #4CAF50;
            --hijau-latar: #F4F9F4;
            --hijau-gelap: #1B5E20;
            --putih: #FFFFFF;
            --kuning-emas: #FFC107;
        }

        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            background-color: var(--hijau-latar);
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 5%;
            background: var(--putih);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .logo { font-size: 24px; font-weight: 800; color: var(--hijau-primer); }

        .promo-section {
            background: linear-gradient(135deg, var(--hijau-primer), var(--hijau-gelap));
            color: white;
            padding: 60px 20px;
            text-align: center;
            border-radius: 0 0 50px 50px;
            margin-bottom: 30px;
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
            padding: 20px 5%;
        }

        .product-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 20px rgba(0,0,0,0.05);
            transition: 0.3s;
            text-align: center;
            padding-bottom: 20px;
        }

        .card-image-placeholder {
            height: 150px;
            background: #E8F5E9;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 50px;
            color: var(--hijau-primer);
        }

        .card-content { padding: 15px; }
        .card-rating-badge { color: var(--kuning-emas); font-weight: bold; margin-bottom: 10px; }
        
        .action-buttons {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 15px;
            border-top: 1px solid #eee;
            padding-top: 15px;
        }

        .btn-edit {
            color: var(--hijau-primer);
            text-decoration: none;
            font-weight: bold;
            font-size: 14px;
        }

        .btn-hapus {
            color: #ff5252;
            text-decoration: none;
            font-weight: bold;
            font-size: 14px;
        }

        .btn-tambah {
            background-color: var(--hijau-primer); 
            color: white; 
            text-decoration: none; 
            padding: 10px 20px; 
            border-radius: 10px; 
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 8px;
        }
    </style>
</head>

<body>
    <nav class="navbar">
        <div class="logo"><i class="fas fa-leaf"></i> EcoTaste</div>
    </nav>

    <header class="promo-section">
        <h1>Promo Hari Ini! 🍽️</h1>
        <p>Temukan kuliner ramah lingkungan terbaik di Bandung.</p>
    </header>

    <main>
        <div style="padding: 0 5%; display: flex; justify-content: space-between; align-items: center;">
            <h2>Rekomendasi Pilihan</h2>
            <a href="tambah.php" class="btn-tambah">
                <i class="fas fa-plus-circle"></i> Tambah Menu
            </a>
        </div>

        <div class="product-grid">
            <?php while($row = mysqli_fetch_assoc($query_kuliner)) { ?>// Looping untuk menampilkan setiap kuliner yang diambil dari database
                <article class="product-card">
                    <div class="card-image-placeholder">
                        <i class="<?php echo $row['icon']; ?>"></i>
                    </div>
                    <div class="card-content">
                        <h3><?php echo $row['nama']; ?></h3>// Menampilkan nama kuliner
                        <p><?php echo $row['deskripsi']; ?></p>// Menampilkan deskripsi kuliner
                        <div class="card-rating-badge">
                            <i class="fas fa-star"></i> <?php echo $row['rating']; ?>
                        </div>
                        
                        <div class="action-buttons">
                            <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn-edit">// Link ke halaman edit
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="hapus.php?id=<?php echo $row['id']; ?>" class="btn-hapus" onclick="return confirm('Yakin ingin menghapus menu ini?')">// Link ke halaman hapus dengan konfirmasi
                                <i class="fas fa-trash"></i> Hapus
                            </a>
                        </div>
                    </div>
                </article>
            <?php } ?>
        </div>
    </main>

    <footer style="text-align:center; padding: 50px; color: #888;">
        &copy; 2026 EcoTaste Bandung. All Rights Reserved.
    </footer>
</body>
</html>