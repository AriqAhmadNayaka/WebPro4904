<?php
require_once 'koneksi.php';
require_once 'profil_helper.php';

$profileRepository = new ProfileRepository($conn);
$fileManager = new ProfileFileManager(__DIR__);
$sessionManager = new ProfileSessionManager();
$authService = new UserAuthService($conn, $profileRepository, $sessionManager, $fileManager);

$profileRepository->ensureSchema();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $name = trim((string) ($_POST['nama'] ?? ''));
        $email = trim((string) ($_POST['email'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');

        $authService->register($name, $email, $password);
        header('Location: login.php?status=register-success');
        exit;
    } catch (Throwable $exception) {
        header('Location: registrasi.php?status=register-failed');
        exit;
    }
}
?>
