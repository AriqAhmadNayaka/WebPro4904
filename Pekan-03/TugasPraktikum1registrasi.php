<!-- Tugas Praktikum 1 Register-Login menggunakan php -->

<?php
// Inisialisasi variabel untuk menyimpan input dan pesan error
$nama = $email = $username = $password = $telepon = $alamat = "";
// Inisialisasi variabel untuk menyimpan pesan error jika input kosong
$namaErr = $emailErr = $usernameErr = $passwordErr = $teleponErr = $alamatErr = "";

// Mengecek apakah form telah disubmit dengan metode POST
// POST digunakan untuk mengirim data dari form ke server, data yang dikirim tidak akan terlihat di URL
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Fungsi untuk membersihkan data input dari user
    // trim() -> menghapus spasi di awal dan akhir
    // stripslashes() -> menghapus backslash
    // htmlspecialchars() -> mencegah HTML injection
    function test_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    // Validasi input nama
    // Jika kosong maka tampilkan pesan error
    if (empty($_POST["nama"])) {
        $namaErr = "Nama harus diisi";
    } else {
    // Jika tidak kosong maka data dibersihkan menggunakan fungsi test_input dan disimpan ke variabel $nama
        $nama = test_input($_POST["nama"]);
    }

    // Validasi input email
    if (empty($_POST["email"])) {
        $emailErr = "Email harus diisi";
    } else {
        $email = test_input($_POST["email"]);
    }

    // Validasi input username
    if (empty($_POST["username"])) {
        $usernameErr = "Username harus diisi";
    } else {
        $username = test_input($_POST["username"]);
    }

    // Validasi input password
    if (empty($_POST["password"])) {
        $passwordErr = "Password harus diisi";
    } else {
        $password = test_input($_POST["password"]);
    }

    // Validasi input nomor telepon
    if (empty($_POST["telepon"])) {
        $teleponErr = "Nomor telepon harus diisi";
    } else {
        $telepon = test_input($_POST["telepon"]);
    }

    // Validasi input alamat
    if (empty($_POST["alamat"])) {
        $alamatErr = "Alamat harus diisi";
    } else {
        $alamat = test_input($_POST["alamat"]);
    }
}
?>

<!DOCTYPE html>
<html lang="in">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pendaftaran</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            min-height: 100vh;
            background-color: #ebfbfa;
            background-image:
                radial-gradient(circle at 10% 100%, rgba(5, 12, 87, 0.607), rgba(70, 79, 184, 0.281), transparent 40%),
                radial-gradient(circle at 100% 10%, rgba(60, 252, 242, 0.42), rgba(207, 244, 248, 0.756), transparent 60%);
            background-repeat: no-repeat;
            background-attachment: fixed;
        }

        .form-container {
            width: 100%;
            display: flex;
            justify-content: center;
            margin-top: 60px;
        }

        .form-box {
            width: 100%;
            max-width: 420px;
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
            padding: 24px;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .input-card {
            background: #fdfdfd;
            border: 1px solid #ddd;
            padding: 10px 12px;
            border-radius: 10px;
        }

        .input-card input {
            width: 100%;
            padding: 6px 0;
            border: none;
            outline: none;
            font-size: 15px;
            background: transparent;
        }

        button {
            padding: 12px;
            background: #956df3;
            color: #fff;
            border: none;
            border-radius: 999px;
            cursor: pointer;
            width: 100%;
            font-size: 15px;
        }

        button:hover {
            background: #7944f5;
        }

        .error {
            color: red;
            font-size: 13px;
        }

        header {
            margin-top: 30px;
            text-align: center;
        }
    </style>
</head>

<body>

    <header>
        <h1 style="color:#956df3;">NaviBiz</h1>
        <h2 style="color:#956df3;">Pendaftaran / Registration</h2>
    </header>

    <!-- Form pendaftaran dengan method POST untuk mengirim data ke server -->
    <div class="form-container">

        <form class="form-box" method="POST">

            <div class="input-card">
                <input type="text" name="nama" placeholder="Masukkan nama lengkap" value="<?php echo $nama; ?>"> <!-- Menampilkan kembali data yang sudah diinput pengguna
     agar ketika terjadi error form tidak kosong kembali -->
                <span class="error"><?php echo $namaErr; ?></span> <!-- menampilkan pesan error jika nama kosong -->
            </div>

            <div class="input-card">
                <input type="text" name="email" placeholder="Masukkan email (contoh@gmail.com)"
                    value="<?php echo $email; ?>"> <!-- Menampilkan kembali data yang sudah diinput pengguna
     agar ketika terjadi error form tidak kosong kembali -->
                <span class="error"><?php echo $emailErr; ?></span>
                <!-- menampilkan pesan error jika email kosong -->
            </div>

            <div class="input-card">
                <input type="text" name="username" placeholder="Buat Nama Pengguna" value="<?php echo $username; ?>"> <!-- Menampilkan kembali data yang sudah diinput pengguna
     agar ketika terjadi error form tidak kosong kembali -->
                <span class="error"><?php echo $usernameErr; ?></span>
                <!-- menampilkan pesan error jika username kosong -->
            </div>

            <div class="input-card">
                <input type="password" name="password" placeholder="Buat password">
                <span class="error"><?php echo $passwordErr; ?></span>
                <!-- menampilkan pesan error jika password kosong -->
            </div>

            <div class="input-card">
                <input type="text" name="telepon" placeholder="Masukkan Nomor Telepon (08XXXXXXXXXX)"
                    value="<?php echo $telepon; ?>"> <!-- Menampilkan kembali data yang sudah diinput pengguna
     agar ketika terjadi error form tidak kosong kembali -->
                <span class="error"><?php echo $teleponErr; ?></span>
                <!-- menampilkan pesan error jika nomor telepon kosong -->
            </div>

            <div class="input-card">
                <input type="text" name="alamat" placeholder="Masukkan alamat lengkap" value="<?php echo $alamat; ?>"> <!-- Menampilkan kembali data yang sudah diinput pengguna
     agar ketika terjadi error form tidak kosong kembali -->
                <span class="error"><?php echo $alamatErr; ?></span>
                <!-- menampilkan pesan error jika alamat kosong -->
            </div>

            <button type="submit">Daftar</button>

            <div class="login-link" style="text-align:center; margin-top:20px;">
            <a href="TugasPraktikum1login.php">
            Apakah Anda sudah memiliki akun? Login disini
            </a>
            </div>
        </form>

    </div>


    <?php
    // Mengecek apakah form sudah dikirim menggunakan POST
    // dan memastikan semua field tidak memiliki error
    if (
        $_SERVER["REQUEST_METHOD"] == "POST" &&
        $namaErr == "" && $emailErr == "" && $usernameErr == "" &&
        $passwordErr == "" && $teleponErr == "" && $alamatErr == ""
    ) {
    ?>
     <!-- Jika semua input valid maka sistem akan menampilkan -->
     <!-- data yang sudah dimasukkan oleh pengguna dalam bentuk tabel -->
    <div style="text-align:center; margin-top:30px;">
        <h2>Data Pendaftaran</h2>

        <table border="1" cellpadding="10" style="margin:auto; border-collapse:collapse;">
            <tr>
                <td>Nama</td>
                <td><?php echo $nama; ?></td>
            </tr>

            <tr>
                <td>Email</td>
                <td><?php echo $email; ?></td>
            </tr>

            <tr>
                <td>Username</td>
                <td><?php echo $username; ?></td>
            </tr>

            <tr>
                <td>Password</td>
                <td><?php echo $password; ?></td>
            </tr>

            <tr>
                <td>Telepon</td>
                <td><?php echo $telepon; ?></td>
            </tr>

            <tr>
                <td>Alamat</td>
                <td><?php echo $alamat; ?></td>
            </tr>
        </table>
    </div>
    <?php
    }
    ?>
    

    <?php
    // Mengecek apakah form sudah dikirim menggunakan POST
    if (
        $_SERVER["REQUEST_METHOD"] == "POST" &&
        $namaErr == "" && $emailErr == "" && $usernameErr == "" &&
        $passwordErr == "" && $teleponErr == "" && $alamatErr == ""
    ) {

    // Redirect otomatis ke halaman login setelah 3 detik
        echo "<script>
        window.location='TugasPraktikum1login.php';
        </script>";
    }
    ?>

</body>

</html>