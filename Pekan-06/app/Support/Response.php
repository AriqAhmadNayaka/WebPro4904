<?php

declare(strict_types=1);

namespace App\Support;

// Helper sederhana untuk kebutuhan response HTTP.
final class Response
{
    // Mengarahkan user ke halaman lain lalu menghentikan proses script.
    public static function redirect(string $location): never
    {
        header("Location: " . $location);
        exit();
    }
}
