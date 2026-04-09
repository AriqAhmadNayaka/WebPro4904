<?php
// mulai session untuk cek login user
session_start();

// cek apakah user sudah login atau belum
if (!isset($_SESSION['user_login'])) {
    header("Location: login.php"); // kalau belum login, arahkan ke login
    exit; // hentikan program
}
include 'koneksi.php';

// cek apakah tombol submit ditekan (form dikirim)
if (isset($_POST['submit'])) {

    // ambil data dari form + amankan dari SQL injection
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $lokasi = mysqli_real_escape_string($conn, $_POST['lokasi']);
    $harga = mysqli_real_escape_string($conn, $_POST['harga']);

    // ambil data file gambar yang diupload
    $gambar_tmp = $_FILES['gambar']['tmp_name']; // lokasi sementara file
    $gambar_name = $_FILES['gambar']['name']; // nama asli file
    // ambil ekstensi file (jpg, png, dll)
    $gambar_ext = pathinfo($gambar_name, PATHINFO_EXTENSION);
    // buat nama file baru  (biar ga ketimpa)
    $gambar_fix = time().'_'.rand(1000,9999).'.'.$gambar_ext;
    // tentukan folder tujuan upload
    $folder = "upload/".$gambar_fix;
    // proses upload file ke folder upload
    if (move_uploaded_file($gambar_tmp, $folder)) {

        // kalau upload berhasil, simpan data ke database
        $query = "INSERT INTO wishlist_wisata (nama, deskripsi, lokasi, harga, gambar) 
                  VALUES ('$nama', '$deskripsi', '$lokasi', '$harga', '$gambar_fix')";
        mysqli_query($conn, $query);
        // setelah berhasil, kembali ke halaman wishlist
        header("Location: whislist.php");
        exit;
    } else {
        // kalau upload gagal
        echo "<script>alert('Gagal upload gambar!');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Wishlist</title>
    <link rel="stylesheet" href="style_dashboard.css">
    <style>
        .container {
            padding: 30px 40px;
            max-width: 600px;
            margin: auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        h2 {
            color: #0f4c3a;
            text-align: center;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin: 10px 0 5px;
            font-weight: bold;
        }

        input[type="text"], textarea, input[type="file"] {
            padding: 8px 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        textarea {
            resize: vertical;
        }

        input[type="submit"] {
            margin-top: 15px;
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

        a.back-btn {
            display: inline-block;
            margin-top: 10px;
            text-decoration: none;
            color: #0f4c3a;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Tambah Wishlist</h2>
    <form action="" method="POST" enctype="multipart/form-data">
        <label>Nama Tempat</label>
        <input type="text" name="nama" required>

        <label>Deskripsi</label>
        <textarea name="deskripsi" rows="3" required></textarea>

        <label>Lokasi</label>
        <input type="text" name="lokasi" required>

        <label>Harga</label>
        <input type="text" name="harga" required>

        <label>Gambar</label>
        <input type="file" name="gambar" accept="image/*" required>

        <input type="submit" name="submit" value="Tambah Data">
    </form>
    <a href="whislist.php" class="back-btn">← Kembali ke Wishlist</a>
</div>

</body>
</html>