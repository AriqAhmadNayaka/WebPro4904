<?php

function ensureProfileSchema($conn)
{
    $requiredColumns = [
        "nama_lengkap" => "ALTER TABLE users ADD COLUMN nama_lengkap VARCHAR(100) NULL AFTER username",
        "no_hp" => "ALTER TABLE users ADD COLUMN no_hp VARCHAR(20) NULL AFTER nama_lengkap",
        "tanggal_lahir" => "ALTER TABLE users ADD COLUMN tanggal_lahir DATE NULL AFTER no_hp",
        "jenis_kelamin" => "ALTER TABLE users ADD COLUMN jenis_kelamin VARCHAR(20) NULL AFTER tanggal_lahir",
        "alamat" => "ALTER TABLE users ADD COLUMN alamat TEXT NULL AFTER jenis_kelamin",
        "kota" => "ALTER TABLE users ADD COLUMN kota VARCHAR(100) NULL AFTER alamat",
        "pekerjaan" => "ALTER TABLE users ADD COLUMN pekerjaan VARCHAR(100) NULL AFTER kota",
        "bio" => "ALTER TABLE users ADD COLUMN bio TEXT NULL AFTER pekerjaan",
        "foto_profil" => "ALTER TABLE users ADD COLUMN foto_profil VARCHAR(255) NULL AFTER bio"
    ];

    foreach ($requiredColumns as $column => $sql) {
        $escapedColumn = mysqli_real_escape_string($conn, $column);
        $checkColumn = mysqli_query($conn, "SHOW COLUMNS FROM users LIKE '$escapedColumn'");

        if ($checkColumn && mysqli_num_rows($checkColumn) === 0) {
            mysqli_query($conn, $sql);
        }
    }
}

function getDefaultProfilePhoto()
{
    return 'FOTO/profil.jpeg';
}

function getProfilePhotoPath($photoPath)
{
    $defaultPhoto = getDefaultProfilePhoto();

    if (!empty($photoPath) && file_exists(__DIR__ . DIRECTORY_SEPARATOR . $photoPath)) {
        return $photoPath;
    }

    return $defaultPhoto;
}

function getCurrentUserProfile($conn, $email)
{
    $email = mysqli_real_escape_string($conn, $email);
    $query = "SELECT * FROM users WHERE email = '$email' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) === 1) {
        return mysqli_fetch_assoc($result);
    }

    return null;
}

function syncUserSessionFromProfile($profile)
{
    if (!$profile) {
        return;
    }

    $_SESSION['email'] = $profile['email'];
    $_SESSION['username'] = !empty($profile['username']) ? $profile['username'] : $profile['email'];
    $_SESSION['nama_lengkap'] = !empty($profile['nama_lengkap']) ? $profile['nama_lengkap'] : $_SESSION['username'];
    $_SESSION['foto_profil'] = getProfilePhotoPath(isset($profile['foto_profil']) ? $profile['foto_profil'] : '');
}
?>
