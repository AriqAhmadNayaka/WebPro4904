<?php
session_start();

require_once 'koneksi.php';
require_once 'profil_helper.php';

$profileRepository = new ProfileRepository($conn);
$fileManager = new ProfileFileManager(__DIR__);
$sessionManager = new ProfileSessionManager();
$profileService = new ProfileService($profileRepository, $fileManager, $sessionManager);

try {
    $user = $profileService->boot();

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('location:profil.php');
        exit();
    }

    $aksi = isset($_POST['aksi']) ? (string) $_POST['aksi'] : 'simpan';
    $email = (string) $_SESSION['email'];

    if ($aksi === 'hapus') {
        $sessionManager->flash('profil_success', $profileService->delete($email, $user));
        header('location:profil.php');
        exit();
    }

    $sessionManager->flash(
        'profil_success',
        $profileService->save($email, $_POST, $_FILES['foto_profil'] ?? null, $user)
    );
} catch (Throwable $exception) {
    $sessionManager->flash('profil_error', $exception->getMessage());
}

header('location:profil.php');
exit();
?>
