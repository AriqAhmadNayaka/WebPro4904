<?php
// Menghubungkan script inspeksi dengan koneksi database utama.
include 'koneksi.php';

// Menampilkan judul bagian struktur tabel.
echo "TABLE STRUCTURE" . PHP_EOL;
// Mengambil daftar seluruh tabel yang ada di database aktif.
$tables = mysqli_query($conn, 'SHOW TABLES');
while ($row = mysqli_fetch_row($tables)) {
    // Menampilkan nama tabel satu per satu.
    echo 'TABLE=' . $row[0] . PHP_EOL;
}

// Menampilkan bagian jumlah data pada tabel utama.
echo PHP_EOL . "ROW COUNT" . PHP_EOL;
// Menghitung total data yang tersimpan di tabel data_webandoo.
$count = mysqli_query($conn, 'SELECT COUNT(*) AS total FROM data_webandoo');
$total = mysqli_fetch_assoc($count);
echo $total['total'] . PHP_EOL;

// Menampilkan beberapa data terbaru untuk pengecekan cepat.
echo PHP_EOL . "LATEST DATA" . PHP_EOL;
// Mengambil maksimal 5 data terbaru dari tabel.
$data = mysqli_query($conn, 'SELECT id, nama_item, deskripsi, gambar FROM data_webandoo ORDER BY id DESC LIMIT 5');
while ($row = mysqli_fetch_assoc($data)) {
    // Menampilkan ringkasan data berupa id, nama, dan gambar.
    echo $row['id'] . ' | ' . $row['nama_item'] . ' | ' . $row['gambar'] . PHP_EOL;
}

// Jika parameter test_insert dikirim, script akan mencoba menambah data uji.
if (isset($_GET['test_insert'])) {
    // Membuat data dummy sederhana untuk menguji proses insert.
    $nama = 'tes_insert_' . time();
    $deskripsi = 'uji simpan';
    $gambar = 'https://example.com/test.jpg';
    // Menyiapkan query insert untuk pengujian.
    $stmt = mysqli_prepare($conn, "INSERT INTO data_webandoo (nama_item, deskripsi, gambar) VALUES (?, ?, ?)");
    // Mengikat data dummy ke prepared statement.
    mysqli_stmt_bind_param($stmt, "sss", $nama, $deskripsi, $gambar);
    // Menjalankan proses insert uji.
    $ok = mysqli_stmt_execute($stmt);
    echo PHP_EOL . "TEST INSERT" . PHP_EOL;
    // Menampilkan status hasil pengujian insert.
    echo $ok ? "BERHASIL" : "GAGAL: " . mysqli_error($conn);
    // Menutup statement setelah proses selesai.
    mysqli_stmt_close($stmt);
}
