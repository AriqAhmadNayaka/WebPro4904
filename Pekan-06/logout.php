<?php

declare(strict_types=1);

// Memuat autoloader class OOP aplikasi.
require_once __DIR__ . "/app/bootstrap.php";

use App\Config\Database;
use App\Repositories\UserRepository;
use App\Services\AuthService;
use App\Services\RememberMeService;
use App\Support\Response;
use App\Support\SessionManager;

// Menyiapkan session manager.
$session = new SessionManager();
// Memastikan session aktif.
$session->start();
// Menyiapkan repository user.
$userRepository = new UserRepository(Database::getConnection());
// Menyiapkan service auth untuk menghapus session login.
$authService = new AuthService($session, $userRepository, new RememberMeService($userRepository));

// Menjalankan proses logout.
$authService->logout();

// Mengarahkan user kembali ke halaman login.
Response::redirect("auth.php");
