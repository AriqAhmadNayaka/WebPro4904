<?php
require_once 'koneksi.php';

echo "DATABASE LOGIN" . PHP_EOL;
echo "Nama database: db_login_simulasi" . PHP_EOL . PHP_EOL;

echo "DAFTAR USER" . PHP_EOL;
$query = mysqli_query($conn, 'SELECT id, username, created_at FROM users ORDER BY id ASC');

if (!$query) {
    exit('Gagal membaca tabel users: ' . mysqli_error($conn));
}

while ($row = mysqli_fetch_assoc($query)) {
    echo $row['id'] . ' | ' . $row['username'] . ' | ' . $row['created_at'] . PHP_EOL;
}
