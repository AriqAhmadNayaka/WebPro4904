<?php include 'header.php'; ?>
<div class="auth-container">
    <h2>Tambah Data Peserta ABK</h2>
    <form action="tampil_anak.php" method="POST" class="auth-form">
        <div class="form-group">
            <label>Nama Anak</label>
            <input type="text" name="nama_anak" required placeholder="Nama lengkap anak">
        </div>
        <div class="form-group">
            <label>Kategori Pelatihan</label>
            <select name="kategori" required>
                <option value="">-- Pilih Kategori --</option>
                <option value="Tata Boga">Tata Boga</option>
                <option value="Pertanian">Pertanian</option>
                <option value="Teknologi">Teknologi</option>
            </select>
        </div>
        <button class="btn auth-btn" type="submit">Simpan Data</button>
    </form>
</div>
<?php include 'footer.php'; ?>