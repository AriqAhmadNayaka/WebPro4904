<?php
include 'koneksi.php'; //koneksi ke database

if(isset($_POST['submit'])) { //cek tombol submit sudah ditekan atau belum
    $username = $_POST['username']; //ambil data username, email, dan password dari form
    $email = $_POST['email']; 
    $password = $_POST['password'];
    $query = "INSERT INTO pengguna VALUES('', '$username', '$email', '$password')"; //membuat query untuk memasukan data ke tabel  pengguna
    mysqli_query($conn, $query); //menjalankan query dan koneksi
    header ("location:login.php"); //mengalihkan ke halaman login setelah berhasil register
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="register.css">
    <title>Register</title>
</head>
<body>
    <div class="register">
        <section class="box">
            <h1>Register</h1>
            <form method="post" action="register.php"> <!--form untuk mengirim data ke file register.php dengan method post-->
                <div class="input1">
                    <input type="text" name="username" id="username" placeholder="Masukan username" required>
                </div>
                <div class="input2">
                    <span></span>
                    <input type="email" name="email" id="email" placeholder="Masukan email" required>
                </div>
                <div class="input3">
                    <span></span>
                    <input type="password" name="password" id="password" placeholder="Masukan password" required>
                </div>
                <center><button type="submit" name="submit" id="button"><b>Register</b></button></center>
            </form>
            <p>Sudah punya akun?<a href="login.html">Log In</a></p>
        </section>
    </div>
</body>
</html>