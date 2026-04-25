<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
AUTO-LOADER — application/config/autoload.php
File ini menentukan komponen apa saja yang otomatis dimuat oleh CI3
di setiap request, tanpa perlu memanggil $this->load->... di controller.
Hanya muat yang benar-benar dibutuhkan di hampir semua halaman,
karena setiap komponen yang di-autoload menambah beban setiap request.
*/

/*
| Auto-load Packages
| Package memungkinkan pengelompokan library, model, dan helper dalam satu folder.
| Tidak digunakan dalam project ini — dikosongkan.
*/
$autoload['packages'] = array();

/*
Auto-load Libraries
Library adalah kelas bawaan CI3 yang ada di folder system/libraries/.

'database' : Mengaktifkan koneksi database secara otomatis di setiap request.
            Tanpa ini, $this->db tidak akan tersedia di controller/model.
            Konfigurasi koneksi diambil dari application/config/database.php.

'session'  : Mengaktifkan library session CI3 yang menggantikan $_SESSION PHP native.
            Memungkinkan penggunaan $this->session->set_userdata(),
            $this->session->userdata(), $this->session->set_flashdata(), dll.
            Konfigurasi session (driver, cookie name, expiration) ada di config.php.
*/
$autoload['libraries'] = array('database', 'session');

/*
Auto-load Drivers
Driver mirip library tapi mendukung multiple backend/adapter (contoh: cache driver).
Tidak digunakan dalam project ini - dikosongkan.
*/
$autoload['drivers'] = array();

/*
Auto-load Helper Files
Helper adalah kumpulan fungsi global (bukan class) dari CI3.

'url'  : Menyediakan fungsi-fungsi URL seperti:
        - base_url()   : Menghasilkan URL lengkap dari root project (digunakan di semua view untuk link CSS, gambar, form action)
        - redirect()   : Melakukan HTTP redirect ke URL/controller tertentu (digunakan di semua controller)
        - site_url()   : Menghasilkan URL dengan index.php jika diperlukan

'form' : Menyediakan fungsi-fungsi untuk membuat elemen form HTML:
        - form_open() : Membuat tag <form> dengan action URL yang benar
        - form_input(), form_password(), dll.
        (Dalam project ini form HTML ditulis manual, tapi helper ini tetap berguna)
|
'file' : Menyediakan fungsi-fungsi untuk operasi file:
        - read_file(), write_file(), get_filenames(), dll.
        Digunakan untuk operasi file di server.
*/
$autoload['helper'] = array('url', 'form', 'file');

/*
Auto-load Config files
Untuk file konfigurasi kustom yang dibuat sendiri (bukan config.php bawaan).
Tidak ada file config tambahan dalam project ini — dikosongkan.
*/
$autoload['config'] = array();

/*
Auto-load Language files
Untuk file bahasa (internasionalisasi/i18n).
Tidak digunakan — dikosongkan.
*/
$autoload['language'] = array();

/*
Auto-load Models
Model juga bisa di-autoload jika digunakan di semua controller.
Namun dalam project ini, setiap controller memuat model-nya masing-masing
secara eksplisit di __construct() dengan $this->load->model(). Jadi dikosongkan.
*/
$autoload['model'] = array();
