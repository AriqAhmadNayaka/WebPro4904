<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Data Penduduk</title>
</head>
    <!-- Form untuk memasukkan data statistik desa -->
     <!-- menggunakan form HTML dengan action ke dashboard.php -->
      <!-- sehingga admin dapat memperbarui data statistik  -->
<body>
    <form action="dashboard.php" method="POST">
    <input type="text" name="jumlahASN" placeholder="Jumlah Aparatur Sipil Negara">
    <input type="text" name="jumlahPenduduk" placeholder="Jumlah Penduduk">
    <input type="text" name="jumlahUMKM" placeholder="Jumlah Pelaku UMKM">
    <button type="submit">Submit</button>
    </form>
</body>
</html>