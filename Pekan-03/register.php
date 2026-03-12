<?php

// ===============================
// INISIALISASI VARIABEL
// ===============================

// variabel untuk menyimpan input user
$nama = "";
$email = "";
$password = "";
$role = "";

// variabel untuk menyimpan pesan error validasi
$namaErr = "";
$emailErr = "";
$passwordErr = "";
$roleErr = "";

// variabel untuk menampilkan output pesan
$output = "";


// ===============================
// CEK APAKAH FORM DIKIRIM
// ===============================

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // fungsi untuk membersihkan input user
    function test_input($data) {

        // trim() = menghapus spasi di awal dan akhir
        // stripslashes() = menghapus karakter \
        // htmlspecialchars() = mencegah HTML injection
        return htmlspecialchars(stripslashes(trim($data)));
    }


    // ===============================
    // VALIDASI NAMA
    // ===============================

    // cek apakah nama kosong
    if (empty($_POST["nama"])) {

        $namaErr = "Nama harus diisi";

    } else {

        // membersihkan input nama
        $nama = test_input($_POST["nama"]);

        // cek panjang nama minimal 3 karakter
        if (strlen($nama) < 3) {

            $namaErr = "Nama minimal 3 karakter";
        }
    }


    // ===============================
    // VALIDASI EMAIL
    // ===============================

    if (empty($_POST["email"])) {

        $emailErr = "Email harus diisi";

    } else {

        // membersihkan input email
        $email = test_input($_POST["email"]);

        // cek panjang email minimal 6 karakter
        if (strlen($email) < 6) {

            $emailErr = "Email minimal 6 karakter";

        }
        // cek format email valid atau tidak
        elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

            $emailErr = "Format email tidak valid";
        }
    }


    // ===============================
    // VALIDASI PASSWORD
    // ===============================

    if (empty($_POST["password"])) {

        $passwordErr = "Password harus diisi";

    } else {

        // membersihkan input password
        $password = test_input($_POST["password"]);

        // cek panjang password minimal 6 karakter
        if (strlen($password) < 6) {

            $passwordErr = "Password minimal 6 karakter";
        }
    }


    // ===============================
    // VALIDASI ROLE
    // ===============================

    if (empty($_POST["role"])) {

        $roleErr = "Role harus dipilih";

    } else {

        // membersihkan input role
        $role = test_input($_POST["role"]);
    }


    // ===============================
    // JIKA SEMUA VALIDASI BERHASIL
    // ===============================

    if ($namaErr == "" && $emailErr == "" && $passwordErr == "" && $roleErr == "") {

        // menampilkan pesan registrasi berhasil
        $output = "
        <div style='margin-top:20px;text-align:center;color:green'>
        <h4>Registrasi Berhasil</h4>
        Nama : $nama <br>
        Email : $email <br>
        Role : $role
        </div>
        ";
    }

    // ===============================
    // JIKA ADA ERROR VALIDASI
    // ===============================

    else {

        // menampilkan pesan error
        $output = "
        <div style='margin-top:20px;text-align:center;color:red'>
        <h4>Validasi Data</h4>
        $namaErr <br>
        $emailErr <br>
        $passwordErr <br>
        $roleErr
        </div>
        ";
    }


    // ===============================
    // REDIRECT KE HALAMAN LAIN
    // ===============================

    // jika email, password, dan role tidak error
    if ($emailErr == "" && $passwordErr == "" && $roleErr == "") {

        // pindah ke halaman kelolapeserta.php
        header("Location: kelolapeserta.php");

        // menghentikan eksekusi script
        exit();
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

<div class="auth-container">

<!-- Judul halaman register -->
<h2 class="auth-title">Buat Akun Baru</h2>


<!-- FORM REGISTER -->
<form method="POST" class="auth-form">


<!-- INPUT NAMA -->
<div class="form-group">

<label>Nama Lengkap</label>

<input type="text" name="nama" placeholder="Masukkan nama">

</div>


<!-- INPUT EMAIL -->
<div class="form-group">

<label>Email</label>

<input type="email" name="email" placeholder="Masukkan email">

</div>


<!-- INPUT PASSWORD -->
<div class="form-group">

<label>Password</label>

<input type="password" name="password" placeholder="Buat password">

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


<!-- MENAMPILKAN PESAN VALIDASI ATAU SUKSES -->
<?php echo $output; ?>


<!-- LINK KE HALAMAN LOGIN -->
<p class="auth-switch">

Sudah punya akun?

<a href="auth.php">Masuk</a>

</p>

</div>

</body>
</html>
