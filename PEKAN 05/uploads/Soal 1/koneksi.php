<?php
mysqli_report(MYSQLI_REPORT_OFF);

$host = '127.0.0.1';
$user = 'root';
$pass = '';
$db = 'db_login_simulasi';
$port = 3306;

$conn = mysqli_connect($host, $user, $pass, '', $port);

if (!$conn) {
    die('Koneksi ke MySQL gagal: ' . mysqli_connect_error());
}

if (!mysqli_query($conn, "CREATE DATABASE IF NOT EXISTS `$db`")) {
    die('Database gagal dibuat: ' . mysqli_error($conn));
}

if (!mysqli_select_db($conn, $db)) {
    die('Database gagal dipilih: ' . mysqli_error($conn));
}

mysqli_set_charset($conn, 'utf8mb4');

$sqlUsers = "
    CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(100) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
";

if (!mysqli_query($conn, $sqlUsers)) {
    die('Tabel users gagal dibuat: ' . mysqli_error($conn));
}

$usernameDefault = 'admin';
$passwordDefault = 'admin123';

$stmtCek = mysqli_prepare($conn, 'SELECT id FROM users WHERE username = ? LIMIT 1');

if (!$stmtCek) {
    die('Gagal menyiapkan pengecekan user: ' . mysqli_error($conn));
}

mysqli_stmt_bind_param($stmtCek, 's', $usernameDefault);
mysqli_stmt_execute($stmtCek);
$hasilCek = mysqli_stmt_get_result($stmtCek);
$userAda = $hasilCek ? mysqli_fetch_assoc($hasilCek) : null;
mysqli_stmt_close($stmtCek);

if (!$userAda) {
    $hashPassword = password_hash($passwordDefault, PASSWORD_DEFAULT);
    $stmtInsert = mysqli_prepare($conn, 'INSERT INTO users (username, password) VALUES (?, ?)');

    if (!$stmtInsert) {
        die('Gagal menyiapkan user default: ' . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($stmtInsert, 'ss', $usernameDefault, $hashPassword);

    if (!mysqli_stmt_execute($stmtInsert)) {
        die('Gagal menambahkan user default: ' . mysqli_stmt_error($stmtInsert));
    }

    mysqli_stmt_close($stmtInsert);
}
?>
