<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// URL dasar project CI3 — harus diakhiri dengan '/'
// Digunakan oleh base_url() di semua view untuk path aset, form action, dan redirect.
// WAJIB disesuaikan dengan path lokal masing-masing jika berpindah komputer/server.
$config['base_url'] = 'http://localhost/Pemrograman_Web/WebPro4904/Pekan-08/';

/*
Jika kamu menggunakan mod_rewrite untuk menghilangkan halaman ini, kosongkan variabel ini.
*/
$config['index_page'] = '';

/*
Item ini menentukan variabel global server mana yang digunakan untuk
mengambil string URI. Pengaturan default 'REQUEST_URI' bekerja untuk
sebagian besar server. Jika link tidak berfungsi, coba salah satu opsi lain:

'REQUEST_URI'    Menggunakan $_SERVER['REQUEST_URI']
'QUERY_STRING'   Menggunakan $_SERVER['QUERY_STRING']
'PATH_INFO'      Menggunakan $_SERVER['PATH_INFO']
*/
$config['uri_protocol']	= 'REQUEST_URI';

/*
Opsi ini memungkinkan kamu menambahkan suffix ke semua URL yang dihasilkan oleh CodeIgniter.
Catatan: Opsi ini diabaikan untuk request CLI.
*/
$config['url_suffix'] = '';

/*

Ini menentukan set file bahasa mana yang digunakan.
*/
$config['language']	= 'english';

/*
Ini menentukan set karakter apa yang digunakan secara default pada
berbagai method yang membutuhkan set karakter.
*/
$config['charset'] = 'UTF-8';

/*
Jika ingin menggunakan fitur 'hooks', aktifkan dengan mengeset variabel
ini ke TRUE (boolean).
*/
$config['enable_hooks'] = FALSE;

/*
Item ini memungkinkan kita mengatur prefix nama file/class saat
meng-extend library bawaan.
*/
$config['subclass_prefix'] = 'MY_';

/*
Mengaktifkan pengaturan ini akan memberi tahu CodeIgniter untuk mencari
script auto-loader paket Composer di application/vendor/autoload.php.
$config['composer_autoload'] = TRUE;

Atau jika direktori vendor/ berada di lokasi lain, kamu bisa menentukan
path spesifik:
$config['composer_autoload'] = '/path/to/vendor/autoload.php';

*/
$config['composer_autoload'] = FALSE;

/*
Ini memungkinkan kamu menentukan karakter apa saja yang diperbolehkan
dalam URL. Jika seseorang mencoba mengirim URL dengan karakter yang
tidak diizinkan, mereka akan mendapat pesan peringatan.

Sebagai langkah keamanan, sangat disarankan membatasi URL hanya
dengan karakter sesedikit mungkin. Secara default hanya ini yang diizinkan:
a-z 0-9~%.:_-
Biarkan kosong untuk mengizinkan semua karakter — tapi hanya jika
kamu benar-benar yakin.
Nilai yang dikonfigurasi sebenarnya adalah grup karakter regex
dan akan dieksekusi sebagai: ! preg_match('/^[<permitted_uri_chars>]+$/i
*/
$config['permitted_uri_chars'] = 'a-z 0-9~%.:_\-';

$config['enable_query_strings'] = FALSE;
$config['controller_trigger'] = 'c';
$config['function_trigger'] = 'm';
$config['directory_trigger'] = 'd';

/*
Secara default CodeIgniter mengaktifkan akses ke array $_GET. Jika
karena suatu alasan ingin menonaktifkannya, set 'allow_get_array' ke FALSE.
*/
$config['allow_get_array'] = TRUE;

/*
Kita dapat mengaktifkan logging error dengan mengatur ambang batas
di atas nol. Ambang batas menentukan apa yang dicatat. Opsi ambang:
0 = Menonaktifkan logging, logging error DIMATIKAN
1 = Pesan Error (termasuk error PHP)
2 = Pesan Debug
3 = Pesan Informasi
4 = Semua Pesan
*/
$config['log_threshold'] = 0;

/*
Biarkan KOSONG kecuali ingin menggunakan direktori selain default
application/logs/. Gunakan path server lengkap dengan trailing slash.
*/
$config['log_path'] = '';

/*
Ekstensi nama file default untuk file log. Default 'php' memungkinkan
proteksi file log via scripting dasar, saat disimpan di bawah direktori
yang dapat diakses publik.
Catatan: Membiarkan kosong akan menggunakan default 'php'.
*/
$config['log_file_extension'] = '';

/*
Permission sistem file yang diterapkan pada file log yang baru dibuat.
*/
$config['log_file_permissions'] = 0644;

/*
Setiap item yang dicatat memiliki tanggal terkait. Kita bisa menggunakan
kode tanggal PHP untuk mengatur format tanggalmu sendiri.
*/
$config['log_date_format'] = 'Y-m-d H:i:s';

/*
Biarkan KOSONG kecuali ingin menggunakan direktori selain default
application/views/errors/. Gunakan path server lengkap dengan trailing slash.
|
*/
$config['error_views_path'] = '';

/*
Biarkan KOSONG kecuali ingin menggunakan direktori selain default
application/cache/. Gunakan path server lengkap dengan trailing slash.
*/
$config['cache_path'] = '';

/*
Apakah akan mempertimbangkan query string URL saat menghasilkan file
cache output. Opsi yang valid:
FALSE      = Dinonaktifkan
TRUE       = Diaktifkan, pertimbangkan semua parameter query.
             Perlu diketahui bahwa ini bisa menghasilkan banyak file
             cache yang dibuat berulang kali untuk halaman yang sama.
array('q') = Diaktifkan, tapi hanya pertimbangkan daftar parameter
             query yang ditentukan.
*/
$config['cache_query_string'] = FALSE;

/*
Jika menggunakan class Encryption, kamu harus mengatur kunci enkripsi.
Lihat panduan pengguna untuk info lebih lanjut.
*/
// Kunci enkripsi untuk library Encryption dan keamanan session CI3.
// Harus unik per project dan dijaga kerahasiaannya (jangan di-push ke repositori publik).
// Panjang minimal: 32 karakter untuk AES-256.
$config['encryption_key'] = 'inkluskill2026secretkey12345678901234';

$config['sess_driver'] = 'files';
$config['sess_cookie_name'] = 'ci_session';
$config['sess_samesite'] = 'Lax';
$config['sess_expiration'] = 7200;
$config['sess_save_path'] = NULL;
$config['sess_match_ip'] = FALSE;
$config['sess_time_to_update'] = 300;
$config['sess_regenerate_destroy'] = FALSE;

$config['cookie_prefix']	= '';
$config['cookie_domain']	= '';
$config['cookie_path']		= '/';
$config['cookie_secure']	= FALSE;
$config['cookie_httponly'] 	= FALSE;
$config['cookie_samesite'] 	= 'Lax';

$config['standardize_newlines'] = FALSE;

$config['global_xss_filtering'] = FALSE;

$config['csrf_protection'] = FALSE;
$config['csrf_token_name'] = 'csrf_test_name';
$config['csrf_cookie_name'] = 'csrf_cookie_name';
$config['csrf_expire'] = 7200;
$config['csrf_regenerate'] = TRUE;
$config['csrf_exclude_uris'] = array();

$config['compress_output'] = FALSE;

$config['time_reference'] = 'local';

$config['rewrite_short_tags'] = FALSE;

$config['proxy_ips'] = '';
