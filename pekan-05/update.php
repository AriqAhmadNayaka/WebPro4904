<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Tambah Stok</title>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <?php
    include "koneksi.php"; //koneksi ke database
    $id = $_GET['id_artikel']; //mengambil id_artikel dari url yang dikirim dari file artikel_psikolog.php
    $query = mysqli_query($koneksi, "SELECT * FROM artikel WHERE id_artikel='$id'"); //membuat query untuk mengoneksikan ke database dan
    //  mengambil data dari database
    while($hasil = mysqli_fetch_array($query)){ //melakukan perulangan untuk mengambil data dari database dan menyimpan ke dalam variabel hasil
    ?>
    <div class="bg-white p-8 rounded-lg shadow-md w-96">
        <h1 class="text-2xl font-bold mb-6 text-center">Edit Artikel</h1>

        <form method="POST" class="space-y-4" action="proses_update.php">
            <input type="hidden" name="id_artikel" value="<?php echo $hasil['id_artikel']; ?>">
            <div>
                <label name="isi_artikel" class="block text-gray-700 mb-2">Isi Artikel</label>
                // Mengisi input dengan data yang diambil dari database berdasarkan id_artikel yang dikirim dari url
                <input type="text" name="isi_artikel" value="<?php echo $hasil['isi_artikel']; ?>" required placeholder="masukan isi artikel"
                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
            </div>

            <div>
                // Mengisi input dengan data yang diambil dari database berdasarkan id_artikel yang dikirim dari url
                <input type="file" name="foto" value="<?php echo $hasil['foto']; ?>" required placeholder="masukan foto"class="w-full px-4 
                py-2 border rounded-lg focus:outline-none focus:border-blue-500">
            </div>

            <input type="submit" name="submit" class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600 transition-colors" value="Edit">
        </form>
    </div>
    <?php } ?>
</body>
</html>