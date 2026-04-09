<?php

declare(strict_types=1);

namespace App\Config;

use mysqli;
use RuntimeException;

// Class ini bertugas menyediakan satu koneksi database yang bisa dipakai bersama.
final class Database
{
    // Menyimpan instance koneksi agar tidak dibuat berulang-ulang.
    private static ?mysqli $connection = null;

    // Method statis untuk mengambil koneksi database aktif.
    public static function getConnection(): mysqli
    {
        // Jika koneksi sudah pernah dibuat, langsung gunakan kembali.
        if (self::$connection instanceof mysqli) {
            return self::$connection;
        }

        // Membuat koneksi ke database MySQL.
        $connection = mysqli_connect("localhost", "root", "", "webpro");

        // Hentikan proses jika koneksi gagal.
        if (!$connection instanceof mysqli) {
            throw new RuntimeException("database tidak terhubung");
        }

        // Mengatur charset agar mendukung karakter UTF-8.
        mysqli_set_charset($connection, "utf8mb4");
        // Menyimpan koneksi ke property statis.
        self::$connection = $connection;

        // Mengembalikan koneksi yang sudah siap dipakai.
        return self::$connection;
    }
}
