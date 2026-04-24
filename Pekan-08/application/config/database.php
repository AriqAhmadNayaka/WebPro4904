<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS
| -------------------------------------------------------------------
| File ini adalah konfigurasi koneksi database untuk CodeIgniter 3.
| Menggantikan class Database.php dari versi native PHP OOP.
|
| Sebelumnya (native OOP):
|   class Database {
|       private $host = "localhost";
|       private $user = "root";
|       private $pass = "";
|       private $db   = "navibiz";
|   }
|
| Di CI3, semua konfigurasi dipindahkan ke file ini.
| CI3 akan otomatis mengelola koneksi saat $this->load->database() dipanggil.
*/

$active_group = 'default';
$query_builder = TRUE;

$db['default'] = array(
    'dsn'      => '',
    'hostname' => '127.0.0.1',
    'port'     => '3307',        // alamat server database biasanya localhost
    'username' => 'root',             // username default MySQL pada XAMPP
    'password' => '',                 // password MySQL default kosong XAMPP
    'database' => 'navibiz',         // nama database yang akan digunakan
    'dbdriver' => 'mysqli',
    'dbprefix' => '',
    'pconnect' => FALSE,
    'db_debug' => (ENVIRONMENT !== 'production'),
    'cache_on' => FALSE,
    'cachedir' => '',
    'char_set' => 'utf8',
    'dbcollat' => 'utf8_general_ci',
    'swap_pre' => '',
    'encrypt'  => FALSE,
    'compress' => FALSE,
    'stricton' => FALSE,
    'failover' => array(),
    'save_queries' => TRUE
);