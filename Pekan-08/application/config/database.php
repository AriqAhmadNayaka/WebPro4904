<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
File ini berisi konfigurasi koneksi database yang digunakan CI3.
Di-load otomatis oleh CI3 ketika library 'database' di-autoload
(lihat autoload.php).
|
Perbedaan dari versi native PHP:
Versi native : new mysqli('localhost', 'root', '', 'inkluskill_db')
Versi CI3    : Konfigurasi dipusatkan di file ini, CI3 yang membuat koneksi.
*/

/*
$active_group
Menentukan group konfigurasi database mana yang aktif digunakan.
Nilai 'default' merujuk ke array $db['default'] di bawah.
CI3 mendukung multi-database (bisa punya 'default', 'backup', dll.)
*/
$active_group = 'default';

/*
$query_builder
Mengaktifkan fitur Query Builder (Active Record) CI3.
Jika TRUE, memungkinkan penggunaan method seperti:
  $this->db->where(), $this->db->get(), $this->db->insert(), dll.
Harus TRUE karena seluruh model dalam project ini menggunakan Query Builder.
*/
$query_builder = TRUE;

/*
Konfigurasi database utama (group 'default')
*/
$db['default'] = array(
    'dsn'      => '',                   // Data Source Name (kosong = gunakan parameter di bawah)
    'hostname' => 'localhost',          // Host server database (server lokal)
    'username' => 'root',              // Username database (default XAMPP)
    'password' => '',                  // Password database (default XAMPP kosong)
    'database' => 'inkluskill_db',     // Nama database yang digunakan oleh project ini
    'dbdriver' => 'mysqli',            // Driver database: 'mysqli' untuk MySQL modern (lebih baik dari 'mysql' yang sudah deprecated)
    'dbprefix' => '',                  // Prefix tabel (kosong = tidak ada prefix)
    'pconnect' => FALSE,               // Persistent connection: FALSE lebih aman untuk web app biasa
    'db_debug' => (ENVIRONMENT !== 'production'), // Tampilkan error DB di mode development, sembunyikan di production
    'cache_on'  => FALSE,              // Cache query: dinonaktifkan (tidak diperlukan untuk project ini)
    'cachedir'  => '',                 // Direktori cache query (kosong karena cache tidak aktif)
    'char_set'  => 'utf8',             // Character set untuk komunikasi dengan database (mendukung karakter Indonesia)
    'dbcollat'  => 'utf8_general_ci',  // Collation: pengurutan karakter tidak case-sensitive
    'swap_pre'  => '',                 // Prefix alternatif untuk swap (tidak digunakan)
    'encrypt'   => FALSE,              // Enkripsi koneksi SSL ke database (tidak diperlukan di localhost)
    'compress'  => FALSE,              // Kompresi data client-server (tidak diperlukan di localhost)
    'stricton'  => FALSE,              // MySQL Strict Mode (FALSE untuk kompatibilitas lebih luas)
    'failover'  => array(),            // Konfigurasi database cadangan jika koneksi utama gagal (tidak dikonfigurasi)
    'save_queries' => TRUE             // Simpan semua query yang dieksekusi (berguna untuk debugging dengan $this->db->last_query())
);
