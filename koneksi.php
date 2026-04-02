<?php
// Menonaktifkan laporan error mysqli otomatis agar bisa ditangani manual.
mysqli_report(MYSQLI_REPORT_OFF);

// Konfigurasi dasar koneksi MySQL.
$host = "127.0.0.1";
$user = "root";
$pass = "";
$db   = "db_budaya";
// Database cadangan dipakai jika database utama bermasalah pada struktur tabel.
$fallbackDb = "db_budaya_rebuild";
$port = 3306;

// Membuka koneksi ke server MySQL tanpa langsung memilih database.
$conn = mysqli_connect($host, $user, $pass, "", $port);

if (!$conn) {
    // Menghentikan proses jika koneksi ke MySQL gagal.
    die("Koneksi gagal ke MySQL. Pastikan MySQL XAMPP aktif di port 3306. Error: " . mysqli_connect_error());
}

// Membuat database utama bila belum tersedia.
if (!mysqli_query($conn, "CREATE DATABASE IF NOT EXISTS `$db`")) {
    die("Database gagal dibuat: " . mysqli_error($conn));
}

// Memilih database utama untuk dipakai aplikasi.
if (!mysqli_select_db($conn, $db)) {
    die("Database `$db` tidak bisa dipilih: " . mysqli_error($conn));
}

// Mengatur charset agar data Unicode tersimpan dengan baik.
mysqli_set_charset($conn, "utf8mb4");

// Fungsi untuk membuat lalu memilih database tertentu.
function pilihDatabase($conn, $database)
{
    // Pastikan database target tersedia.
    if (!mysqli_query($conn, "CREATE DATABASE IF NOT EXISTS `$database`")) {
        return false;
    }

    // Pilih database target untuk dipakai setelah berhasil dibuat.
    if (!mysqli_select_db($conn, $database)) {
        return false;
    }

    return true;
}

// Fungsi untuk menyiapkan tabel utama penyimpanan data budaya.
function buatTabelDataWebandoo($conn)
{
    $sql_tabel = "
        CREATE TABLE IF NOT EXISTS data_webandoo (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nama_item VARCHAR(255) NOT NULL,
            deskripsi TEXT,
            gambar VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ";

    // Menjalankan query pembuatan tabel jika tabel belum ada.
    if (!mysqli_query($conn, $sql_tabel)) {
        return false;
    }

    return true;
}

// Mencoba membuat tabel utama pada database aktif.
if (!buatTabelDataWebandoo($conn)) {
    // Menyimpan pesan error untuk dianalisis lebih lanjut.
    $errorTabel = mysqli_error($conn);

    // Jika ada masalah tablespace, aplikasi pindah ke database fallback.
    if (stripos($errorTabel, "Tablespace for table") !== false) {
        if (!pilihDatabase($conn, $fallbackDb)) {
            die("Database fallback `$fallbackDb` gagal dipilih: " . mysqli_error($conn));
        }

        // Membuat ulang tabel pada database fallback.
        if (!buatTabelDataWebandoo($conn)) {
            die("Gagal menyiapkan tabel di database fallback: " . mysqli_error($conn));
        }
    } else {
        die("Gagal menyiapkan tabel: " . $errorTabel);
    }
}

// Perbaiki kasus tabel terdaftar di phpMyAdmin tetapi file/engine tabelnya rusak (#1932).
// Mengecek apakah tabel benar-benar bisa diakses, bukan hanya terlihat di daftar database.
$cek_tabel = mysqli_query($conn, "SELECT 1 FROM data_webandoo LIMIT 1");
if ($cek_tabel === false && mysqli_errno($conn) == 1932) {
    // Menghapus tabel rusak agar bisa dibuat ulang dengan struktur sehat.
    mysqli_query($conn, "DROP TABLE IF EXISTS data_webandoo");

    // Membuat ulang tabel setelah tabel rusak dihapus.
    if (!buatTabelDataWebandoo($conn)) {
        $errorTabel = mysqli_error($conn);

        // Jika gagal lagi karena masalah tablespace, gunakan database fallback.
        if (stripos($errorTabel, "Tablespace for table") !== false) {
            if (!pilihDatabase($conn, $fallbackDb)) {
                die("Database fallback `$fallbackDb` gagal dipilih: " . mysqli_error($conn));
            }

            // Coba buat tabel lagi di database fallback.
            if (!buatTabelDataWebandoo($conn)) {
                die("Tabel data_webandoo rusak dan gagal dibuat ulang di database fallback: " . mysqli_error($conn));
            }
        } else {
            die("Tabel data_webandoo rusak dan gagal dibuat ulang: " . $errorTabel);
        }
    }

    $cek_tabel = mysqli_query($conn, "SELECT 1 FROM data_webandoo LIMIT 1");
}

// Jika tabel tetap tidak bisa diakses, hentikan aplikasi dan tampilkan error.
if ($cek_tabel === false) {
    die("Tabel data_webandoo tidak bisa diakses: " . mysqli_error($conn));
}

// Membersihkan hasil query pengecekan tabel dari memori.
if ($cek_tabel instanceof mysqli_result) {
    mysqli_free_result($cek_tabel);
}
?>
