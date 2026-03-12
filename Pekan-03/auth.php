<?php

// memulai session agar bisa menyimpan data login user
session_start();


// variabel untuk menyimpan input user
$email = "";
$password = "";
$role = "";

// variabel untuk menyimpan pesan error validasi
$emailErr = "";
$passwordErr = "";
$roleErr = "";

// variabel untuk menampilkan output pesan ke halaman
$output = "";


// mengecek apakah form dikirim menggunakan method POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // fungsi untuk membersihkan input dari user
    function test_input($data) {

        // trim() = menghapus spasi di awal dan akhir
        // stripslashes() = menghapus backslash
        // htmlspecialchars() = mencegah script HTML berbahaya
        return htmlspecialchars(stripslashes(trim($data)));
    }

    // ===============================
    // VALIDASI EMAIL
    // ===============================

    // mengecek apakah email kosong
    if (empty($_POST["email"])) {

        $emailErr = "Email harus diisi";

    } else {

        // membersihkan input email
        $email = test_input($_POST["email"]);

        // mengecek panjang email minimal 6 karakter
        if (strlen($email) < 6) {

            $emailErr = "Email minimal 6 karakter";

        }
        // mengecek apakah format email valid
        elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

            $emailErr = "Format email tidak valid";
        }
    }


    // ===============================
    // VALIDASI PASSWORD
    // ===============================

    // mengecek apakah password kosong
    if (empty($_POST["password"])) {

        $passwordErr = "Password harus diisi";

    } else {

        // membersihkan input password
        $password = test_input($_POST["password"]);

        // mengecek apakah panjang password minimal 6 karakter
        if (strlen($password) < 6) {

            $passwordErr = "Password minimal 6 karakter";
        }
    }


    // ===============================
    // VALIDASI ROLE
    // ===============================

    // mengecek apakah role dipilih
    if (empty($_POST["role"])) {

        $roleErr = "Role harus dipilih";

    } else {

        // membersihkan input role
        $role = test_input($_POST["role"]);
    }


    // ===============================
    // JIKA VALIDASI BERHASIL
    // ===============================

    // jika tidak ada error
    if ($emailErr == "" && $passwordErr == "" && $roleErr == "") {

        // menyimpan status login ke session
        $_SESSION['login'] = true;

        // menyimpan email user ke session
        $_SESSION['email'] = $email;

        // redirect ke halaman kelolapeserta.php
        header("Location: kelolapeserta.php");

        // menghentikan eksekusi script
        exit();
    }

} else {

    // jika ada error validasi maka tampilkan pesan error
    $output = "
        <div style='margin-top:20px;text-align:center;color:red'>
        <h4>Validasi Data</h4>
        $emailErr <br>
        $passwordErr <br>
        $roleErr
        </div>
        ";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<!-- membuat halaman responsive di mobile -->
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Login | InkluSkill</title>

<!-- memanggil file CSS -->
<link rel="stylesheet" href="styles.css">

</head>

<body class="auth-body">

<div class="auth-container">

<!-- judul halaman login -->
<h2 class="auth-title">Masuk ke InkluSkill</h2>


<!-- FORM LOGIN -->
<form method="POST" action="" class="auth-form">

<!-- INPUT EMAIL -->
<div class="form-group">
<label>Email</label>
<input type="email" name="email" placeholder="Masukkan email">
</div>


<!-- INPUT PASSWORD -->
<div class="form-group">
<label>Password</label>
<input type="password" name="password" placeholder="Masukkan password">
</div>


<!-- PILIH ROLE -->
<div class="form-group">
<label>Pilih Role</label>

<select name="role">

<option value="">-- Pilih Role --</option>

<option value="sekolah">Sekolah</option>

</select>

</div>


<!-- TOMBOL LOGIN -->
<button class="btn auth-btn" type="submit">
Masuk
</button>

</form>


<!-- MENAMPILKAN PESAN VALIDASI -->
<?php echo $output; ?>


<!-- LINK KE HALAMAN REGISTER -->
<p class="auth-switch">

Belum punya akun?

<a href="register.php">Daftar</a>

</p>

</div>

</body>
</html>
