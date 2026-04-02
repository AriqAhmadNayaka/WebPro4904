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

$aksi = isset($_POST['aksi']) ? $_POST['aksi'] : 'simpan';

if ($aksi === 'hapus') {
    // Saat aksi hapus dipilih, data profil dan file upload lama dihapus sebagai fungsi Delete.
    removeUploadedProfilePhoto(isset($user['foto_profil']) ? $user['foto_profil'] : '');

    $queryDeleteProfile = "
        UPDATE users
        SET
            nama_lengkap = NULL,
            no_hp = NULL,
            tanggal_lahir = NULL,
            jenis_kelamin = NULL,
            alamat = NULL,
            kota = NULL,
            pekerjaan = NULL,
            bio = NULL,
            foto_profil = NULL
        WHERE email = '$emailSession'
        LIMIT 1
    ";

    if (mysqli_query($conn, $queryDeleteProfile)) {
        $updatedUser = getCurrentUserProfile($conn, $_SESSION['email']);
        syncUserSessionFromProfile($updatedUser);
        $_SESSION['profil_success'] = 'Data profil berhasil dihapus. Akun login Anda tetap aman.';
    } else {
        $_SESSION['profil_error'] = 'Data profil gagal dihapus: ' . mysqli_error($conn);
    }

    header("location:profil.php");
    exit();
}

// Pengecekan ini membedakan proses Create pertama kali dengan Update data yang sudah ada.
$sebelumnyaAdaProfil = hasSavedProfileData($user);

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
    // Upload file diproses dalam request yang sama dengan penyimpanan data form.
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
    $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/webp'];
    $fileExtension = strtolower(pathinfo($_FILES['foto_profil']['name'], PATHINFO_EXTENSION));
    $detectedMimeType = mime_content_type($_FILES['foto_profil']['tmp_name']);

    if (!in_array($fileExtension, $allowedExtensions, true) || !in_array($detectedMimeType, $allowedMimeTypes, true)) {
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

    removeUploadedProfilePhoto($fotoProfil);
    $fotoProfil = 'uploads/profile/' . $safeFileName;
}

$tanggalLahirValue = $tanggalLahir ? "'$tanggalLahir'" : "NULL";
$namaLengkapValue = $namaLengkap !== '' ? "'$namaLengkap'" : "NULL";
$noHpValue = $noHp !== '' ? "'$noHp'" : "NULL";
$jenisKelaminValue = $jenisKelamin !== '' ? "'$jenisKelamin'" : "NULL";
$alamatValue = $alamat !== '' ? "'$alamat'" : "NULL";
$kotaValue = $kota !== '' ? "'$kota'" : "NULL";
$pekerjaanValue = $pekerjaan !== '' ? "'$pekerjaan'" : "NULL";
$bioValue = $bio !== '' ? "'$bio'" : "NULL";
$fotoProfilValue = $fotoProfil !== '' ? "'" . mysqli_real_escape_string($conn, $fotoProfil) . "'" : "NULL";

$queryUpdate = "
    UPDATE users
    SET
        username = '$username',
        nama_lengkap = $namaLengkapValue,
        no_hp = $noHpValue,
        tanggal_lahir = $tanggalLahirValue,
        jenis_kelamin = $jenisKelaminValue,
        alamat = $alamatValue,
        kota = $kotaValue,
        pekerjaan = $pekerjaanValue,
        bio = $bioValue,
        foto_profil = $fotoProfilValue
    WHERE email = '$emailSession'
    LIMIT 1
";

if (mysqli_query($conn, $queryUpdate)) {
    // Query ini menangani Create/Update profil, lalu hasilnya langsung ditampilkan kembali pada halaman profil.
    $updatedUser = getCurrentUserProfile($conn, $_SESSION['email']);
    syncUserSessionFromProfile($updatedUser);
    $_SESSION['profil_success'] = $sebelumnyaAdaProfil
        ? 'Profil berhasil diperbarui beserta file pendukungnya.'
        : 'Data profil berhasil ditambahkan beserta file pendukungnya.';
} else {
    $_SESSION['profil_error'] = 'Profil gagal diperbarui: ' . mysqli_error($conn);
}

header("location:profil.php");
exit();
?>
