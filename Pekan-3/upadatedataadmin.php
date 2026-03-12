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
    <form action="dashboardadmin.php" method="POST">
    <input type="text" name="jumlahLaporan" placeholder="Jumlah Laporan">
    <input type="text" name="laporanPending" placeholder="Jumlah Laporan Pending">
    <input type="text" name="laporanDitinjau" placeholder="Jumlah Laporan Ditinjau">
    <input type="text" name="laporanSelesai" placeholder="Jumlah Laporan Selesai">
    <button type="submit">Submit</button>
    </form>
</body>
</html>