<?php
session_start();
if(!isset($_SESSION['user_login'])){
    header("Location: login.php");
    exit;
}
include 'koneksi.php';

// ambil id dari URL (dipakai buat ambil data yang mau diedit)
$id = $_GET['id'];

// ambil data lama berdasarkan id
$query = mysqli_query($conn, "SELECT * FROM wishlist_wisata WHERE id='$id'");
$row = mysqli_fetch_assoc($query); 
if(isset($_POST['submit'])){

    // ambil data dari form + amankan
    $nama = mysqli_real_escape_string($conn,$_POST['nama']);
    $deskripsi = mysqli_real_escape_string($conn,$_POST['deskripsi']);
    $lokasi = mysqli_real_escape_string($conn,$_POST['lokasi']);
    $harga = mysqli_real_escape_string($conn,$_POST['harga']);

    // cek apakah user upload gambar baru atau tidak
    if($_FILES['gambar']['name'] != ''){
        // ambil data gambar baru
        $gambar_tmp = $_FILES['gambar']['tmp_name'];
        $gambar_name = $_FILES['gambar']['name'];
        $gambar_ext = pathinfo($gambar_name, PATHINFO_EXTENSION);// ambil ekstensi file
        $gambar_fix = time().'_'.rand(1000,9999).'.'.$gambar_ext;
        $folder = "upload/".$gambar_fix;
        move_uploaded_file($gambar_tmp,$folder);
        if(file_exists("upload/".$row['gambar'])){   
            unlink("upload/".$row['gambar']);// hapus gambar lama biar ga numpuk
        }
        // update data dan gambar baru
        $query_update = "UPDATE wishlist_wisata 
                         SET nama='$nama', deskripsi='$deskripsi', lokasi='$lokasi', harga='$harga', gambar='$gambar_fix' 
                         WHERE id='$id'";
    }else{
        // kalau tidak upload gambar baru, update data saja
        $query_update = "UPDATE wishlist_wisata 
                         SET nama='$nama', deskripsi='$deskripsi', lokasi='$lokasi', harga='$harga' 
                         WHERE id='$id'";
    }
    // jalankan query update
    mysqli_query($conn,$query_update);
    // setelah berhasil, kembali ke halaman wishlist
    header("Location: whislist.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Wishlist</title>
<link rel="stylesheet" href="style_dashboard.css">
<style>

.navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #0f4c3a; /* hijau gelap */
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
    max-width: 600px;
    margin: 30px auto;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

/* Judul */
h2 {
    color: #0f4c3a;
    text-align: center;
    margin-bottom: 20px;
}

/* Form */
form {
    display: flex;
    flex-direction: column;
}

label {
    margin: 10px 0 5px;
    font-weight: bold;
    color: #0f172a;
}

input[type="text"], textarea, input[type="file"] {
    padding: 8px 10px;
    border-radius: 6px;
    border: 1px solid #ccc;
    font-size: 14px;
}

textarea {
    resize: vertical;
}

input[type="submit"] {
    margin-top: 20px;
    padding: 10px;
    background-color: #00b894;
    color: white;
    border: none;
    border-radius: 8px;
    font-weight: bold;
    cursor: pointer;
    transition: 0.3s;
}

input[type="submit"]:hover {
    background-color: #019875;
}

/* Back button */
a.back-btn {
    display: inline-block;
    margin-top: 15px;
    text-decoration: none;
    color: #0f4c3a;
    font-weight: bold;
}

/* Thumbnail gambar lama */
.old-image {
    margin-top: 10px;
}
.old-image img {
    width: 120px;
    border-radius: 8px;
    border: 1px solid #ccc;
}
</style>
</head>
<body>
<nav class="navbar">
    <a href="dashboard.php" class="logo">WeBandoo+</a>
    <ul class="nav-links">
        <li><a href="dashboard.php" class="nav-link"><i class="fas fa-chart-line"></i> Dashboard</a></li>
        <li><a href="whislist.php" class="nav-link active"><i class="fas fa-star"></i> Wishlist</a></li>
    </ul>
    <div class="navbar-right">
        <div class="avatar">
            <a href="profil.php">
                <img id="navAvatar" src="Raihan.jpg" alt="Avatar">
            </a>
        </div>
        <a href="pengaturan.php" class="toggle-btn-leave"><i class="fas fa-cog"></i></a>
        <a href="keluar.php" class="toggle-btn-set"><i class="fas fa-sign-out-alt"></i> Keluar</a>
    </div>
</nav>

<div class="container">
<h2>Edit Wishlist</h2>
<form action="" method="POST" enctype="multipart/form-data">
    <label>Nama Tempat</label>
    <input type="text" name="nama" value="<?= $row['nama']; ?>" required>

    <label>Deskripsi</label>
    <textarea name="deskripsi" rows="4" required><?= $row['deskripsi']; ?></textarea>

    <label>Lokasi</label>
    <input type="text" name="lokasi" value="<?= $row['lokasi']; ?>" required>

    <label>Harga</label>
    <input type="text" name="harga" value="<?= $row['harga']; ?>" required>

    <label>Gambar</label>
    <input type="file" name="gambar" accept="image/*">
    <div class="old-image">
        <p>Gambar lama:</p>
        <img src="upload/<?= $row['gambar']; ?>" alt="Gambar Lama">
    </div>

    <input type="submit" name="submit" value="Simpan Perubahan">
</form>
<a href="whislist.php" class="back-btn">← Kembali ke Wishlist</a>
</div>
</body>
</html>