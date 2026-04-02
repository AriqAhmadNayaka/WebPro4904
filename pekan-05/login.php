<?php
include 'koneksi.php'; //koneksi ke database
if(isset($_POST['username']) && isset ($_POST['password'])) { //mengecek form sudah di submit atau belum
    $username = $_POST['username']; //ambil data username dan password dari form
    $password = $_POST['password'];
    if (!empty($username) && !empty ($password)) { //mengecek data yang diambil dari form tidak kosong
        $sql = mysqli_query($koneksi, "SELECT * FROM pengguna WHERE
        username='$username' AND password='$password';"); //membuat query untuk mengecek apakah data yang diambil dari form ada di database atau tidak
        $ketemu = mysqli_num_rows($sql); //menghitung jumlah data yang ditemukan dari query diatas
        if ($ketemu == 1) {
            session_start(); //sesi dimulai jika data ditemukan dari database
            while ($user = mysqli_fetch_assoc($sql)) { //mengambil data dari database dan menyimpan ke dalam variabel session
                $username = $user[1] = $username;
                $password = $user[2] = $password;
                $_SESSION['username'] = $username;
                $_SESSION['password'] = $password;
                header("location:artikel_psikolog.php"); //mengalihkan ke halaman artikel_psikolog.php setelah berhasil login
            }
        }else {
            echo "<script>alert('Username atau password salah!'); </script>"; //menampilkan alert jika data yang diambil dari form tidak ditemukan
        }
    }else {
        echo "<script>alert('Daftar dulu!'); </script>"; //menampilkan alert jika data yang diambil dari form kosong
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="login.css">
    <title>Log In</title>
</head>
<body>
    <div class="login">
        <section class="box">
            <h1>Log In</h1>
            <form action="login.php" method="post"> <!--form untuk mengirim data ke file login.php dengan method post-->
                <div class="input1">
                    <span></span>
                    <input type="text" name="username" id="username" placeholder="Masukan username" required>
                </div>
                <div class="input2">
                    <span></span>
                    <input type="password" name="password" id="password" placeholder="Masukan password" required>
                </div>
                <a href="#" class="password">Forgot password?</a> <br>
                <center><button type="submit"><b>Masuk</b></button></center>
            </form>
            <p>Belum punya akun?<a href="register.php">Register</a></p>
        </section>
    </div>
</body>
</html>