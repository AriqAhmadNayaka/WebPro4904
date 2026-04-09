<?php

declare(strict_types=1);

// Memuat autoloader class OOP aplikasi.
require_once __DIR__ . "/app/bootstrap.php";

use App\Config\Database;

// Menyimpan koneksi database agar tetap bisa dipakai oleh file lama yang membutuhkan variabel $koneksi.
$koneksi = Database::getConnection();
