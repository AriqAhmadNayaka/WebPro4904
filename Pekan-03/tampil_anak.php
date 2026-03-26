<?php include 'header.php'; ?>
<section class="section__container">
    <h2>Daftar Peserta Pelatihan</h2>
    <p>Data berikut adalah data yang baru saja Anda masukkan:</p>
    
    <table>
        <thead>
            <tr>
                <th>Nama Anak</th>
                <th>Kategori Pelatihan</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?php echo isset($_POST['nama_anak']) ? htmlspecialchars($_POST['nama_anak']) : "Belum ada data"; ?></td>
                <td><?php echo isset($_POST['kategori']) ? htmlspecialchars($_POST['kategori']) : "Belum ada data"; ?></td>
            </tr>
        </tbody>
    </table>
    <br>
    <a href="tambah_anak.php" class="btn" style="text-decoration:none; display:inline-block; width:auto; padding:10px 20px;">Tambah Lagi</a>
</section>
<?php include 'footer.php'; ?>