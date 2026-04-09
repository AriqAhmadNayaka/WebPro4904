<?php
session_start();
if (!isset($_SESSION['user_login'])) {// ngecek apakah user sudah login
    header("Location: login.php");//Jika belum maka akan kembali ke halaman login
    exit;
}
include 'koneksi.php';
$data = mysqli_query($conn, "SELECT * FROM wishlist_wisata");//Mengambil data dari database (Tabel whislist_wisata)
?>

<!DOCTYPE html>
<html>
<head>
    <title>Wishlist</title>
    <link rel="stylesheet" href="style_dashboard.css">
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f4f8;
        }

        /* NAVBAR */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #0f4c3a; 
            color: white;
            padding: 12px 20px;
        }

        .navbar a.logo {
            font-weight: bold;
            font-size: 1.5rem;
            color: white;
            text-decoration: none;
        }

        .nav-links {
            list-style: none;
            display: flex;
            margin: 0;
            padding: 0;
        }

        .nav-links li {
            margin-left: 20px;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            display: flex;
            align-items: center;
        }

        .nav-links a i {
            margin-right: 5px;
        }

        .nav-links a.active {
            border-bottom: 2px solid #00b894;
            padding-bottom: 2px;
        }

        .navbar-right {
            display: flex;
            align-items: center;
        }

        .navbar-right .avatar img {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            margin-right: 15px;
        }

        .navbar-right a {
            color: white;
            text-decoration: none;
            margin-left: 10px;
            display: flex;
            align-items: center;
        }

        /* Container */
        .container {
            padding: 30px 40px;
        }

        h2 {
            color: #0f4c3a;
            margin-bottom: 15px;
        }

        .tambah-btn {
            display: inline-block;
            margin-bottom: 25px;
            padding: 10px 18px;
            background-color: #00b894;
            color: white;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            transition: 0.3s;
        }

        .tambah-btn:hover {
            background-color: #019875;
        }

        /* Card */
        .card {
            display: flex;
            background-color: white;
            border-radius: 12px;
            padding: 18px;
            margin-bottom: 20px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            transition: transform 0.2s;
        }

        .card:hover {
            transform: translateY(-3px);
        }

        .card img {
            width: 180px;
            height: 120px;
            object-fit: cover;
            border-radius: 10px;
            margin-right: 20px;
            border: 1px solid #ddd;
        }

        .card-content h3 {
            margin: 0 0 8px 0;
            color: #0f4c3a;
        }

        .card-content p {
            margin: 4px 0;
            color: #333;
            font-size: 0.95rem;
        }

        .card-content p b {
            color: #00b894;
        }

        @media screen and (max-width: 768px) {
            .card {
                flex-direction: column;
                align-items: center;
            }
            .card img {
                margin-bottom: 10px;
            }
            .card-content {
                text-align: center;
            }
        }
    </style>
</head>
<body>

    
    <nav class="navbar">
        <a href="dashboard.php" class="logo">WeBandoo+</a>
        <ul class="nav-links">
            <li><a href="dasboard.php" class="nav-link"><i class="fas fa-chart-line"></i> Dashboard</a></li>
            <li><a href="whislist.php" class="nav-link active"><i class="fas fa-star"></i> Wishlist</a></li>
        </ul>
        <div class="navbar-right">
            <div class="avatar">
                <a href="profil.php">
                    <img id="navAvatar" src="Raihan.jpg" alt="Avatar">
                </a>
            </div>
            <a href="pengaturan.php"><i class="fas fa-cog"></i></a>
            <a href="keluar.php"><i class="fas fa-sign-out-alt"></i> Keluar</a>
        </div>
    </nav>


    <div class="container">
        <h2>Wishlist Tempat Wisata</h2>
        <a href="tambahdata.php" class="tambah-btn">+ Tambah Data</a>

        <?php while($row = mysqli_fetch_assoc($data)) { ?> // looping data dari database (ambil satu per satu)
            <div class="card">
                <img src="upload/<?= $row['gambar']; ?>" alt="Gambar"><!-- menampilkan gambar dari folder upload sesuai nama di database -->

                <div class="card-content">
                    <h3><?= $row['nama']; ?></h3>
                    <p><b>Lokasi:</b> <?= $row['lokasi']; ?></p>
                    <p><b>Deskripsi:</b> <?= $row['deskripsi']; ?></p><!--Menampilkan nama tempat,deskripsi tempat,harga,dan lokasi-->
                    <p><b>Harga:</b> <?= $row['harga']; ?></p>
                    <div style="margin-top:10px;">
                          <a href="edit.php?id=<?= $row['id']; ?>" style="margin-right:10px; color:#00b894;
                           font-weight:bold;">✏️ Edit</a><!--Menuju halaman edit-->
                          <a href="hapus.php?id=<?= $row['id']; ?>" style="color:#e74c3c; font-weight:bold;"
                           onclick="return confirm('Yakin ingin menghapus data ini?')">🗑️ Hapus</a><!--Konfirmasi buat hapus data tempat-->
                      </div>
                </div>
            </div>
        <?php } ?>
    </div>

</body>
</html>