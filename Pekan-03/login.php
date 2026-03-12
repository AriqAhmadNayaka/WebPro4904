<?php
$username = $password = ""; //variabel untuk menampung data inputan
$usernameErr = $passwordErr = ""; //variabel untuk menampung pesan error

if ($_SERVER["REQUEST_METHOD"] == "POST") { //mengecek apa si form nya itu udah di submit atau belum
    function test_input($data) { //fungsi bersihin data input dari karakter yang ga diinginkan
        return htmlspecialchars(stripcslashes(trim($data)));
    }

    if (isset($_POST["username"])) {  //mengecek datanya apakah udah ada atau belum
        $username = test_input($_POST["username"]);
        if (empty($username)) {
            $usernameErr = "Username harus di isi"; //menampilkan peringatan kalo username kosong
        } elseif(!preg_match("/^[a-zA-Z0-9]*$/", $username)) {
            $usernameErr = "Hanya hurup dan angka yang diperbolehkan"; //menampilkan peringatan karakterapa aja yang boleh dipake
        }
    }

    if (isset($_POST["password"])) { //cek data password nya udah ada atau belum
        $password = test_input($_POST["password"]);
        if (empty($password)) {
            $passwordErr = "Password harus di isi"; //peringatan password harus di isi
        }elseif(strlen($password) < 6) {
            $passwordErr = "Password harus lebih dari 6 karakter"; //peringatan password harus punya minimal 6 karakter aja
        } elseif(!preg_match("/[\W]/", $password)) {
            $passwordErr = "Password harus mengandung setidaknya satu karakter spesial (!@#$%^&*)"; // peringatan karakter mana aja yang harus ada
        }
    }

    if (!$usernameErr && !$passwordErr) { //cek apa ada error atau nggk
        header("Location: login.php"); //pindah ke halaman artikel_psikolog.php kalo data nya sudah benar semua
        exit();
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
            <form method="POST" action="">
                <div class="input1">
                    <span></span>
                    <input type="text" name="username" id="username" placeholder="Masukan username"  required>
                </div>
                <span> * <?php echo $usernameErr; ?></span> <!-- penempatan pesan error -->
                <div class="input2">
                    <span></span>
                    <input type="password" name="password" id="password" placeholder="Masukan password"  required>
                </div>
                <span> * <?php echo $passwordErr; ?></span> <!-- penempatan pesan error -->
                <a href="#" class="password">Forgot password?</a> <br>
                <input type="submit" value="Masuk">
            </form>
            <p>Belum punya akun?<a href="register.php">Register</a></p>
        </section>
    </div>
</body>
</html>