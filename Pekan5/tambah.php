<?php
include 'koneksi.php'; // Pastikan koneksi ke database jalan

if (isset($_POST['submit'])) {
    $nama = $_POST['nama_kuliner'];
    $deskripsi = $_POST['deskripsi'];
    $rating = $_POST['rating'];
    

    $icon = "fas fa-utensils"; 

    // Query SQL untuk (Insert) data
    $query = "INSERT INTO beranda (nama, deskripsi, rating, icon) VALUES ('$nama', '$deskripsi', '$rating', '$icon')";
    
    if (mysqli_query($conn, $query)) {
        // Jika berhasil, balik ke halaman utama
        echo "<script>alert('Menu Berhasil Ditambahkan!'); window.location='homepage.php';</script>";
    } else {
        echo "Gagal: " . mysqli_error($conn);// Jika gagal, tampilkan error
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Menu EcoTaste</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body { font-family: 'Poppins', sans-serif; background: #F4F9F4; display: flex; justify-content: center; padding-top: 50px; }
        .form-container { background: white; padding: 30px; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); width: 400px; }
        h2 { color: #4CAF50; text-align: center; }
        input, textarea { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 8px; box-sizing: border-box; }
        button { width: 100%; padding: 12px; background: #4CAF50; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: bold; }
        button:hover { background: #388E3C; }
        .back-link { display: block; text-align: center; margin-top: 15px; color: #888; text-decoration: none; }
    </style>
</head>
<body>

<div class="form-container">
    <h2><i class="fas fa-plus-circle"></i> Tambah Menu Baru</h2>
    <form action="" method="POST">
        <label>Nama Kuliner:</label>
        <input type="text" name="nama_kuliner" placeholder="Contoh: Batagor Bandung" required>
        
        <label>Deskripsi:</label>
        <textarea name="deskripsi" placeholder="Ceritakan sedikit tentang makanannya..." rows="3"></textarea>
        
        <label>Rating (1-5):</label>
        <input type="number" step="0.1" max="5" name="rating" placeholder="Contoh: 4.5" required>
        
        <button type="submit" name="submit">Simpan ke Beranda</button>
    </form>
    <a href="homepage.php" class="back-link">Batal & Kembali</a>
</div>

</body>
</html>