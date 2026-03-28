<?php
// Mulai session untuk kebutuhan notifikasi/flow login.
session_start();
// Ambil koneksi database dari file terpisah.
include "koneksi.php";

// Menyimpan pesan error/sukses untuk ditampilkan ke user.
$pesan = "";

// Proses hanya dijalankan saat form disubmit (method POST).
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Ambil dan rapikan input.
    $username = trim($_POST["username"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";
    $role = trim($_POST["role"] ?? "");

    // Validasi wajib isi.
    if ($username === "" || $email === "" || $password === "") {
        $pesan = "Username, email, dan password wajib diisi.";
    } else {
        // Password disamakan dengan mekanisme login saat ini (md5).
        $passwordHash = md5($password);

        // Cek apakah tabel user punya kolom role (agar query fleksibel).
        $cekRole = mysqli_query($koneksi, "SHOW COLUMNS FROM user LIKE 'role'");
        $adaKolomRole = $cekRole && mysqli_num_rows($cekRole) > 0;

        // Siapkan query insert sesuai struktur kolom yang tersedia.
        if ($adaKolomRole) {
            $sql = "INSERT INTO user (username, email, password, role) VALUES (?, ?, ?, ?)";
            $stmt = mysqli_prepare($koneksi, $sql);
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "ssss", $username, $email, $passwordHash, $role);
            }
        } else {
            $sql = "INSERT INTO user (username, email, password) VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($koneksi, $sql);
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "sss", $username, $email, $passwordHash);
            }
        }

        // Eksekusi query dan tentukan respons.
        if (empty($stmt)) {
            $pesan = "Gagal menyiapkan query: " . mysqli_error($koneksi);
        } elseif (mysqli_stmt_execute($stmt)) {
            // Jika berhasil, arahkan ke halaman login.
            echo '<script>alert("Registrasi berhasil! Silakan login."); location.href="auth.php";</script>';
            exit;
        } else {
            $pesan = "Registrasi gagal: " . mysqli_stmt_error($stmt);
        }

        // Tutup statement agar resource rapi.
        if (!empty($stmt)) {
            mysqli_stmt_close($stmt);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<!-- agar halaman responsive di mobile -->
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Register | InkluSkill</title>

<!-- memanggil file CSS -->
<link rel="stylesheet" href="styles.css">

</head>

<body class="auth-body">
    <?php
// Tampilkan pesan error/sukses jika ada.
if ($pesan !== "") {
    echo '<script>alert("' . addslashes($pesan) . '");</script>';
}
?>


<div class="auth-container">

<!-- Judul halaman register -->
<h2 class="auth-title">Buat Akun Baru</h2>


<!-- FORM REGISTER -->
<form method="POST" class="auth-form">


<!-- INPUT NAMA -->
<div class="form-group">

<label>Nama Lengkap</label>

<input type="text" name="username" placeholder="Masukkan nama" required>

</div>


<!-- INPUT EMAIL -->
<div class="form-group">

<label>Email</label>

<input type="email" name="email" placeholder="Masukkan email" required>

</div>


<!-- INPUT PASSWORD -->
<div class="form-group">

<label>Password</label>

<input type="password" name="password" placeholder="Buat password" required>

</div>


<!-- PILIH ROLE -->
<div class="form-group">

<label>Pilih Role</label>

<select name="role">

<option value="">-- Pilih Role --</option>

<option value="sekolah">Sekolah</option>

</select>

</div>


<!-- TOMBOL DAFTAR -->
<button class="btn auth-btn" type="submit">

Daftar

</button>

</form>




<!-- LINK KE HALAMAN LOGIN -->
<p class="auth-switch">

Sudah punya akun?

<a href="auth.php">Masuk</a>

</p>

</div>

</body>
</html>
