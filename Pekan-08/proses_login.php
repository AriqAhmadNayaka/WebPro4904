<?php
session_start();

require_once 'koneksi.php';
require_once 'profil_helper.php';

$profileRepository = new ProfileRepository($conn);
$fileManager = new ProfileFileManager(__DIR__);
$sessionManager = new ProfileSessionManager();
$authService = new UserAuthService($conn, $profileRepository, $sessionManager, $fileManager);

$profileRepository->ensureSchema();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim((string) ($_POST['email'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');

    if ($authService->login($email, $password)) {
        header('Location: dashboard.php');
        exit;
    }

    header('Location: login.php?status=login-failed');
    exit;
}
?>
