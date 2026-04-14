<section id="dataGizi" class="page-section">
    <div class="acrylic-card">
        <form action="proses_edukasi.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" id="form-id">
            
            <input type="text" name="judul" id="form-judul" placeholder="Judul Artikel" class="custom-input" required>
            <select name="kategori" id="form-kategori" class="custom-input">
                <option value="Nutrisi">Nutrisi</option>
                <option value="Kesehatan">Kesehatan</option>
            </select>
            <input type="file" name="gambar" class="custom-input">
            
            <button type="submit" name="simpan" class="btn-primary">Simpan Data & Unggah</button>
        </form>
    </div>

    <table class="custom-table">
        <thead>
            <tr>
                <th>Gambar</th>
                <th>Judul</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            include 'koneksi.php';
            $sql = mysqli_query($conn, "SELECT * FROM konten_edukasi");
            while ($row = mysqli_fetch_array($sql)) { ?>
                <tr>
                    <td><img src="uploads/<?= $row['gambar'] ?>" width="50"></td>
                    <td><?= $row['judul'] ?></td>
                    <td>
                        <button class="btn-secondary" onclick="isiForm('<?= $row['id'] ?>', '<?= $row['judul'] ?>', '<?= $row['kategori'] ?>')">Edit</button>
                        <a href="proses_edukasi.php?hapus=<?= $row['id'] ?>" class="btn-secondary" style="color:red" onclick="return confirm('Hapus?')">Hapus</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</section>

<script>
// Fungsi agar saat tombol Edit ditekan, data masuk kembali ke form di atas
function isiForm(id, judul, kategori) {
    document.getElementById('form-id').value = id;
    document.getElementById('form-judul').value = judul;
    document.getElementById('form-kategori').value = kategori;
}
</script>