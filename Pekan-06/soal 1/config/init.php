<?php

/**
 * File Inisialisasi Global
 * Mengatur sesi dan sistem autoloading kelas.
 */

// Mulai sesi secara global
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Autoloader Kelas
 * Secara otomatis memuat file kelas dari folder 'src/' saat sebuah kelas dipanggil.
 * Contoh: Memanggil 'new User()' akan memuat 'src/User.php'.
 */
spl_autoload_register(function ($className) {
    $path = __DIR__ . '/../src/' . $className . '.php';
    if (file_exists($path)) {
        require_once $path;
    }
});
