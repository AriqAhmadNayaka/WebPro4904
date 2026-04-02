<?php
// Memanggil file koneksi database.
require_once 'koneksi.php';

// Menyimpan pesan notifikasi untuk ditampilkan ke pengguna.
$pesan = '';
$tipePesan = 'success';

// Proses simpan data barang baru.
if (isset($_POST['simpan'])) {
    // Mengambil input dari form lalu membersihkannya sesuai tipe data.
    $namaBarang = trim($_POST['nama_barang']);
    $qty = (int) $_POST['qty'];
    $harga = (float) $_POST['harga'];

    // Validasi agar nama barang tidak boleh kosong.
    if ($namaBarang === '') {
        $pesan = 'Nama barang wajib diisi.';
        $tipePesan = 'error';
    } else {
        // Menambahkan data barang ke tabel stok_barang.
        $stmt = mysqli_prepare($koneksi, "INSERT INTO stok_barang (`nama barang`, qty, harga) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "sid", $namaBarang, $qty, $harga);

        // Menentukan pesan berdasarkan hasil eksekusi query simpan.
        if (mysqli_stmt_execute($stmt)) {
            $pesan = 'Data barang berhasil ditambahkan.';
        } else {
            $pesan = 'Data barang gagal ditambahkan.';
            $tipePesan = 'error';
        }

        // Menutup statement agar resource database dibersihkan.
        mysqli_stmt_close($stmt);
    }
}

// Proses update data barang yang dipilih.
if (isset($_POST['update'])) {
    // Mengambil data dari form edit.
    $id = (int) $_POST['id'];
    $namaBarang = trim($_POST['nama_barang']);
    $qty = (int) $_POST['qty'];
    $harga = (float) $_POST['harga'];

    // Validasi agar nama barang tetap wajib diisi.
    if ($namaBarang === '') {
        $pesan = 'Nama barang wajib diisi.';
        $tipePesan = 'error';
    } else {
        // Memperbarui data barang berdasarkan id.
        $stmt = mysqli_prepare($koneksi, "UPDATE stok_barang SET `nama barang` = ?, qty = ?, harga = ? WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "sidi", $namaBarang, $qty, $harga, $id);

        // Menentukan notifikasi berhasil atau gagal saat update.
        if (mysqli_stmt_execute($stmt)) {
            $pesan = 'Data barang berhasil diperbarui.';
        } else {
            $pesan = 'Data barang gagal diperbarui.';
            $tipePesan = 'error';
        }

        // Menutup statement setelah proses update selesai.
        mysqli_stmt_close($stmt);
    }
}

// Proses hapus data barang berdasarkan id yang dipilih.
if (isset($_GET['hapus'])) {
    $idHapus = (int) $_GET['hapus'];
    $stmt = mysqli_prepare($koneksi, "DELETE FROM stok_barang WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $idHapus);

    // Menampilkan pesan sesuai hasil hapus data.
    if (mysqli_stmt_execute($stmt)) {
        $pesan = 'Data barang berhasil dihapus.';
    } else {
        $pesan = 'Data barang gagal dihapus.';
        $tipePesan = 'error';
    }

    // Menutup statement setelah query delete dijalankan.
    mysqli_stmt_close($stmt);
}

// Nilai default form saat tidak sedang mengedit data.
$dataEdit = [
    'id' => '',
    'nama_barang' => '',
    'qty' => '',
    'harga' => ''
];

// Mengambil data lama saat tombol edit dipilih.
if (isset($_GET['edit'])) {
    $idEdit = (int) $_GET['edit'];
    $stmt = mysqli_prepare($koneksi, "SELECT id, no, `nama barang` AS nama_barang, qty, harga FROM stok_barang WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $idEdit);
    mysqli_stmt_execute($stmt);
    $resultEdit = mysqli_stmt_get_result($stmt);

    // Jika data ditemukan, isi form dengan data tersebut.
    if ($rowEdit = mysqli_fetch_assoc($resultEdit)) {
        $dataEdit = $rowEdit;
    }

    // Menutup statement setelah proses edit selesai.
    mysqli_stmt_close($stmt);
}

// Mengambil seluruh data barang untuk ditampilkan di tabel.
$resultBarang = mysqli_query($koneksi, "SELECT id, `nama barang` AS nama_barang, qty, harga FROM stok_barang ORDER BY id ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Stok Barang</title>
    <link rel="stylesheet" href="stok_barang.css">
</head>
<body>
    <div class="container">
        <header class="hero">
            <div>
                <!-- Judul utama halaman aplikasi stok barang. -->
                <p class="eyebrow">Dryhus's stock of goods</p>
                <h1>Manajemen Stok Barang</h1>
                <p class="subtitle">Kelola data barang dengan fitur tambah, ubah, hapus, dan lihat data dalam satu halaman.</p>
            </div>
        </header>

        <!-- Menampilkan notifikasi jika ada pesan dari proses CRUD. -->
        <?php if ($pesan !== '') : ?>
            <div class="alert <?php echo $tipePesan; ?>">
                <?php echo htmlspecialchars($pesan); ?>
            </div>
        <?php endif; ?>

        <div class="content">
            <section class="card form-card">
                <!-- Judul form berubah otomatis saat mode tambah atau edit. -->
                <h2><?php echo isset($_GET['edit']) ? 'Edit Barang' : 'Tambah Barang'; ?></h2>
                <form method="post">
                    <!-- Menyimpan id tersembunyi untuk kebutuhan update data. -->
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars((string) $dataEdit['id']); ?>">

                    <!-- Input nama barang. -->
                    <label for="nama_barang">Nama Barang</label>
                    <input
                        type="text"
                        id="nama_barang"
                        name="nama_barang"
                        placeholder="Masukkan nama barang"
                        value="<?php echo htmlspecialchars((string) $dataEdit['nama_barang']); ?>"
                        required
                    >

                    <!-- Input jumlah stok barang. -->
                    <label for="qty">Qty</label>
                    <input
                        type="number"
                        id="qty"
                        name="qty"
                        min="0"
                        placeholder="Masukkan jumlah barang"
                        value="<?php echo htmlspecialchars((string) $dataEdit['qty']); ?>"
                        required
                    >

                    <!-- Input harga barang. -->
                    <label for="harga">Harga</label>
                    <input
                        type="number"
                        id="harga"
                        name="harga"
                        min="0"
                        step="0.01"
                        placeholder="Masukkan harga barang"
                        value="<?php echo htmlspecialchars((string) $dataEdit['harga']); ?>"
                        required
                    >

                    <div class="button-group">
                        <!-- Tombol berubah sesuai mode form: update atau simpan. -->
                        <?php if (isset($_GET['edit'])) : ?>
                            <button type="submit" name="update" class="btn primary">Update</button>
                            <a href="stok_barang.php" class="btn secondary">Batal</a>
                        <?php else : ?>
                            <button type="submit" name="simpan" class="btn primary">Simpan</button>
                        <?php endif; ?>
                    </div>
                </form>
            </section>

            <section class="card table-card">
                <div class="table-header">
                    <!-- Judul tabel data barang. -->
                    <h2>Data Stok Barang</h2>
                    <span class="badge">CRUD PHP MySQL</span>
                </div>

                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Barang</th>
                                <th>Qty</th>
                                <th>Harga</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Jika data ada, tampilkan semua barang dalam tabel. -->
                            <?php if ($resultBarang && mysqli_num_rows($resultBarang) > 0) : ?>
                                <!-- Variabel nomor urut otomatis untuk kolom No. -->
                                <?php $nomor = 1; ?>
                                <?php while ($row = mysqli_fetch_assoc($resultBarang)) : ?>
                                    <tr>
                                        <td><?php echo $nomor++; ?></td>
                                        <td><?php echo htmlspecialchars($row['nama_barang']); ?></td>
                                        <td><?php echo htmlspecialchars((string) $row['qty']); ?></td>
                                        <td>Rp <?php echo number_format((float) $row['harga'], 2, ',', '.'); ?></td>
                                        <td class="action-cell">
                                            <!-- Tombol untuk mengedit data berdasarkan id. -->
                                            <a href="stok_barang.php?edit=<?php echo $row['id']; ?>" class="btn small warning">Edit</a>
                                            <a
                                                href="stok_barang.php?hapus=<?php echo $row['id']; ?>"
                                                class="btn small danger"
                                                onclick="return confirm('Yakin ingin menghapus data ini?')"
                                            >
                                                <!-- Tombol untuk menghapus data berdasarkan id. -->
                                                Hapus
                                            </a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else : ?>
                                <!-- Ditampilkan jika tabel stok_barang masih kosong. -->
                                <tr>
                                    <td colspan="5" class="empty-state">Belum ada data barang.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>
</body>
</html>
