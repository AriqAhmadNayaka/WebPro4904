<?php

declare(strict_types=1);

namespace App\Support;

// Class helper untuk mengelola session aplikasi.
final class SessionManager
{
    // Memastikan session aktif sebelum digunakan.
    public function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Mengambil nilai session berdasarkan key tertentu.
    public function get(string $key, mixed $default = null): mixed
    {
        $this->start();

        return $_SESSION[$key] ?? $default;
    }

    // Menyimpan nilai baru ke dalam session.
    public function set(string $key, mixed $value): void
    {
        $this->start();
        $_SESSION[$key] = $value;
    }

    // Menghapus satu data session berdasarkan key.
    public function remove(string $key): void
    {
        $this->start();
        unset($_SESSION[$key]);
    }

    // Mengambil data session lalu langsung menghapusnya.
    public function pull(string $key, mixed $default = null): mixed
    {
        $value = $this->get($key, $default);
        $this->remove($key);

        return $value;
    }

    // Menghapus seluruh data session dan cookie session.
    public function destroy(): void
    {
        $this->start();
        $_SESSION = [];

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                "",
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        session_destroy();
    }
}
