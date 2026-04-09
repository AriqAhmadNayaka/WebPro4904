<?php

declare(strict_types=1);

// Autoloader ini otomatis memuat class sesuai namespace App\.
spl_autoload_register(static function (string $class): void {
    // Prefix namespace yang dipakai dalam aplikasi.
    $prefix = "App\\";
    // Folder dasar tempat seluruh class disimpan.
    $baseDir = __DIR__ . DIRECTORY_SEPARATOR;

    // Abaikan class yang bukan bagian dari namespace App\.
    if (strncmp($class, $prefix, strlen($prefix)) !== 0) {
        return;
    }

    // Ambil nama class relatif tanpa prefix App\.
    $relativeClass = substr($class, strlen($prefix));
    // Bentuk lokasi file class berdasarkan namespace.
    $file = $baseDir . str_replace("\\", DIRECTORY_SEPARATOR, $relativeClass) . ".php";

    // Jika file class ditemukan, muat file tersebut.
    if (is_file($file)) {
        require_once $file;
    }
});

// Menetapkan timezone aplikasi ke Asia/Jakarta.
date_default_timezone_set("Asia/Jakarta");
