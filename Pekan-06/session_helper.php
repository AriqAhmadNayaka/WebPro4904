<?php

declare(strict_types=1);

// Memuat autoloader class OOP aplikasi.
require_once __DIR__ . "/app/bootstrap.php";

use App\Config\Database;
use App\Repositories\UserRepository;
use App\Services\AuthService;
use App\Services\RememberMeService;
use App\Support\SessionManager;

// Helper untuk membuat object AuthService sekali panggil.
function buildAuthService(): AuthService
{
    // Menyiapkan session manager.
    $session = new SessionManager();
    // Memastikan session aktif.
    $session->start();
    // Menyiapkan repository user untuk kebutuhan autentikasi.
    $repository = new UserRepository(Database::getConnection());

    // Mengembalikan object AuthService yang siap digunakan.
    return new AuthService($session, $repository, new RememberMeService($repository));
}

// Helper untuk mencoba memulihkan session user dari cookie remember me.
function restoreRememberedUser(mysqli $koneksi): bool
{
    // Parameter $koneksi tidak dipakai karena koneksi diambil langsung dari class Database.
    unset($koneksi);

    return buildAuthService()->restoreUserFromCookie();
}

// Helper untuk proses logout user.
function logoutUser(): void
{
    buildAuthService()->logout();
}
?>
