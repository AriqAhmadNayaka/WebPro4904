<?php
session_start();
include 'koneksi.php';
include 'profil_helper.php';

if (!isset($_SESSION['email'])) {
    header("location:login.php");
    exit();
}

ensureProfileSchema($conn);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("location:profil.php");
    exit();
}

$emailSession = mysqli_real_escape_string($conn, $_SESSION['email']);
$user = getCurrentUserProfile($conn, $_SESSION['email']);

if (!$user) {
    $_SESSION['profil_error'] = 'Data akun tidak ditemukan.';
    header("location:profil.php");
    exit();
}

$username = mysqli_real_escape_string($conn, trim($_POST['username']));
$namaLengkap = mysqli_real_escape_string($conn, trim($_POST['nama_lengkap']));
$noHp = mysqli_real_escape_string($conn, trim($_POST['no_hp']));
$tanggalLahir = !empty($_POST['tanggal_lahir']) ? mysqli_real_escape_string($conn, $_POST['tanggal_lahir']) : null;
$jenisKelamin = mysqli_real_escape_string($conn, trim($_POST['jenis_kelamin']));
$alamat = mysqli_real_escape_string($conn, trim($_POST['alamat']));
$kota = mysqli_real_escape_string($conn, trim($_POST['kota']));
$pekerjaan = mysqli_real_escape_string($conn, trim($_POST['pekerjaan']));
$bio = mysqli_real_escape_string($conn, trim($_POST['bio']));
$fotoProfil = isset($user['foto_profil']) ? $user['foto_profil'] : '';

if ($username === '') {
    $_SESSION['profil_error'] = 'Username wajib diisi.';
    header("location:profil.php");
    exit();
}

$checkUsername = mysqli_query($conn, "SELECT email FROM users WHERE username = '$username' AND email != '$emailSession' LIMIT 1");
if ($checkUsername && mysqli_num_rows($checkUsername) > 0) {
    $_SESSION['profil_error'] = 'Username sudah digunakan akun lain.';
    header("location:profil.php");
    exit();
}

if (isset($_FILES['foto_profil']) && $_FILES['foto_profil']['error'] !== UPLOAD_ERR_NO_FILE) {
    if ($_FILES['foto_profil']['error'] !== UPLOAD_ERR_OK) {
        $_SESSION['profil_error'] = 'Upload foto profil gagal.';
        header("location:profil.php");
        exit();
    }

    if ($_FILES['foto_profil']['size'] > 2 * 1024 * 1024) {
        $_SESSION['profil_error'] = 'Ukuran foto profil maksimal 2 MB.';
        header("location:profil.php");
        exit();
    }

    $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
    $fileExtension = strtolower(pathinfo($_FILES['foto_profil']['name'], PATHINFO_EXTENSION));

    if (!in_array($fileExtension, $allowedExtensions, true)) {
        $_SESSION['profil_error'] = 'Format foto profil harus JPG, JPEG, PNG, atau WEBP.';
        header("location:profil.php");
        exit();
    }

    $uploadDirectory = __DIR__ . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'profile';
    if (!is_dir($uploadDirectory)) {
        mkdir($uploadDirectory, 0777, true);
    }

    $safeFileName = 'profile_' . preg_replace('/[^a-zA-Z0-9]/', '_', $_SESSION['email']) . '_' . time() . '.' . $fileExtension;
    $targetFile = $uploadDirectory . DIRECTORY_SEPARATOR . $safeFileName;

    if (!move_uploaded_file($_FILES['foto_profil']['tmp_name'], $targetFile)) {
        $_SESSION['profil_error'] = 'Foto profil tidak bisa disimpan ke server.';
        header("location:profil.php");
        exit();
    }

    $fotoProfil = 'uploads/profile/' . $safeFileName;
}

$tanggalLahirValue = $tanggalLahir ? "'$tanggalLahir'" : "NULL";
$queryUpdate = "
    UPDATE users
    SET
        username = '$username',
        nama_lengkap = '$namaLengkap',
        no_hp = '$noHp',
        tanggal_lahir = $tanggalLahirValue,
        jenis_kelamin = '$jenisKelamin',
        alamat = '$alamat',
        kota = '$kota',
        pekerjaan = '$pekerjaan',
        bio = '$bio',
        foto_profil = '" . mysqli_real_escape_string($conn, $fotoProfil) . "'
    WHERE email = '$emailSession'
    LIMIT 1
";

if (mysqli_query($conn, $queryUpdate)) {
    $updatedUser = getCurrentUserProfile($conn, $_SESSION['email']);
    syncUserSessionFromProfile($updatedUser);
    $_SESSION['profil_success'] = 'Profil berhasil diperbarui.';
} else {
    $_SESSION['profil_error'] = 'Profil gagal diperbarui: ' . mysqli_error($conn);
}

header("location:profil.php");
exit();
?>
