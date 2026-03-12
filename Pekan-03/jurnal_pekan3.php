<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - Nuids</title>
</head>

<style>
    *{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family: Arial, Helvetica, sans-serif;
}

body{
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    background:#f5f5f5;
    overflow:hidden;
}


.background .blob{
    position:absolute;
    width:400px;
    height:400px;
    border-radius:50%;
    filter:blur(120px);
}

.blob1{
    background:#7a7cff;
    left:-100px;
    top:200px;
}

.blob2{
    background:#ffb6c1;
    right:-120px;
    bottom:-120px;
}


.login-container{
    text-align:center;
    width:350px;
    z-index:2;
}

.logo{
    width:60px;
    margin-bottom:10px;
}

h2{
    color:#7aa;
    margin-bottom:25px;
}


input{
    width:100%;
    padding:15px;
    margin:10px 0;
    border-radius:40px;
    border:3px solid transparent;
    background:
        linear-gradient(white,white) padding-box,
        linear-gradient(45deg,#d9a7c7,#fffcdc,#89f7fe,#66a6ff) border-box;
    outline:none;
    font-size:16px;
}


button{
    width:100%;
    padding:15px;
    border:none;
    border-radius:40px;
    margin-top:10px;
    font-size:22px;
    font-weight:bold;
    cursor:pointer;

    background:linear-gradient(90deg,#7ec8e3,#eec0c6);
    box-shadow:0 8px 15px rgba(0,0,0,0.15);
}


.register{
    margin-top:15px;
    color:#6bb6d6;
    cursor:pointer;
}
</style>

<body>

<div class="background">
    <div class="blob blob1"></div>
    <div class="blob blob2"></div>
</div>

<div class="login-container">

    <img src="logo Nuids..png" class="logo">

    <h2>Hi! Selamat Datang di Nuids.</h2>

     <form method="post" action=""> <!-- disini saya menambahkan method post di dalam form -->
        <input name="email" type="email" placeholder="Email" required>
        <input name="password" type="password" placeholder="Password" required>

        <a href="inputdata.html">
    <button type="submit">LogIn</button>
</a>
    </form>

    <p class="register">Register</p>

</div>

<!-- <?php
// $email = $_POST["email"];
// $password = $_POST["password"];            
// if 
?> -->

<!-- get -->

<!-- <?php
// if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['name']) && isset($_GET['email'])) {
//     $name = htmlspecialchars($_GET['name']);
//     $email = htmlspecialchars($_GET['email']);
//     echo "<h3>Hasil Input:</h3>";                 
//     echo "Nama: " . $name . "<br>";
//     echo "Email: " . $email;
//}
?> -->

<!-- post -->

<!-- <?php
// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     $name  = htmlspecialchars($_POST["name"]);
//     $email = htmlspecialchars($_POST["email"]);
//     echo "<h3>Data yang Dikirim:</h3>";
//     echo "Nama: " . $name . "<br>";
//     echo "Email: " . $email . "<br>";
// }
?> -->

<!-- validasi -->

<?php
$name = $email = $password = ""; // saya pertama membuat variabel email sama password
$nameErr = $emailErr = $passwordErr = ""; // ini untuk memvalidasi email dan passwordnya

if ($_SERVER["REQUEST_METHOD"] == "POST") { // disini saya menggunakan method post

    function test_input($data) { // function ini untuk menginput datanya
        return htmlspecialchars(stripslashes(trim($data)));
    }

    if (isset($_POST["email"])) { // dibagian untuk bagian email
        $email = test_input($_POST["email"]); // disini user diminta untuk mengisi emal
        if (empty($email)) { // jika kosong
            $emailErr = "Email harus diisi"; // emailErr untuk memvalidasi apakah user sudah mengisi atau belum
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) { // filter validate bawaan dari php untuk standar email
            $emailErr = "Format email tidak valid"; // jika tidak valid akan muncul ini
        }
    }

    if (isset($_POST["password"])) {
        $password = test_input($_POST["password"]);
        if (empty($password)) {
            $passwordErr = "Password harus diisi";  
        } elseif (strlen($password) < 6) { // strlen berguna untuk menghitung karakter yang kita input
            $passwordErr = "Password harus minimal 6 karakter";
        } elseif (!preg_match("/[\W]/", $password)) { // ini untuk menambahkan spesial karakter
            $passwordErr = "Password harus mengandung minimal 1 karakter spesial (!@#$%^&*)";
        }
    }

    if (!$emailErr && !$passwordErr) { // tanda seru untuk memvalidasi

        if($email == "haikal@gmail.com" && $password == "qwerty!") { // ini untuk menginput email dan password
                var_dump("Berhasil!"); // jika berhasil akan muncul ini
                header("Location: inputdata.html"); // dan diarahkan ke home page
            }
            var_dump("Gagal!"); // jika gagal akan muncul ini
    }
}
?>

</body>
</html>