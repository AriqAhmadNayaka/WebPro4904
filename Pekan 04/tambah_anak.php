<!DOCTYPE html>
<html>
<head>
    <title>Tambah Anak</title> <!--judul halaman tambah anak-->
    <link rel="stylesheet" href="style.css"> <!--link untuk menghubungkan file style.css untuk styling halaman tambah anak-->
</head>

<body>

<div class="auth-container"> <!--  container untuk form tambah anak -->
    <h2 class="auth-title">Tambah Data Anak</h2> <!--judul halaman tambah anak-->

    <form action="proses_tambah.php" method="POST"> <!--untuk mengirim data ke proses_tambah.php untuk diproses dan disimpan ke database-->

        <div class="form-group">
            <label>Nama Anak</label> <!--untuk memasukkan nama anak-->
            <input type="text" name="nama_anak" required> <!--tipe input text untuk memasukkan nama anak dan wajib diisi-->
        </div>

        <div class="form-group">
            <label>Kategori Pelatihan</label> <!--untuk memilih kategori pelatihan-->
            <select name="kategori" required>
                <option value="">-- Pilih Kategori --</option>
                <option value="Tata Boga">Tata Boga</option>
                <option value="Pertanian">Pertanian</option>
                <option value="Teknologi">Teknologi</option>
            </select>
        </div>

        <button type="submit" class="btn">Simpan</button>

    </form>
</div>

</body>
</html>