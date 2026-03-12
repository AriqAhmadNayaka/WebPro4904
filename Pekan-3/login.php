<!DOCTYPE html>
<html>
<head>
<title>LifeTrack Login</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container vh-100 d-flex justify-content-center align-items-center">
<div class="card p-4" style="width:360px">

<h4 class="fw-bold text-success">Login LifeTrack</h4>

<?php if(isset($error)){ ?>
<div class="alert alert-danger"><?php echo $error; ?></div>
<?php } ?>

<form method="POST">

<div class="mb-3">
<label>Email</label>
<input type="email" name="email" class="form-control">
</div>

<div class="mb-3">
<label>Password</label>
<input type="password" name="password" class="form-control">
</div>

<button type="submit" name="login" class="btn btn-success w-100">
Login
</button>

</form>

<p class="mt-3 text-center">
Belum punya akun? <a href="regis.php">Daftar</a>
</p>

</div>
</div>

<?php
$email = $password = ""; //disini membuat variabel email dan password
$emailErr = $passwordErr = ""; // ini untuk memvalidasi email dan passwordnya

if ($_SERVER["REQUEST_METHOD"] == "POST") { //lalu disini menggunakan method post

    function test_input($data) { //function ini untuk menginput datanya
        return htmlspecialchars(stripslashes(trim($data)));
    }

    if (isset($_POST["email"])) { //function dibagian email
        $email = test_input($_POST["email"]); // disini user diminta untuk mengisi email
        if (empty($email)) { // jika kosong
            $emailErr = "Email harus diisi"; // emailErr untuk memvalidasi apakah email sudah di isi atau belum
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) { // filter var bawaan dari php untuk standar email
            $emailErr = "Format email tidak valid"; // jika tidak valid akan muncul ini
        }
    }

    if (isset($_POST["password"])) {
        $password = test_input($_POST["password"]);
        if (empty($password)) {
            $passwordErr = "Password harus diisi";  
        } elseif (strlen($password) < 6) { // strlen berguna untuk menghitung karakter
            $passwordErr = "Password harus minimal 6 karakter";
        } elseif (!preg_match("/[\W]/", $password)) { // ini untuk menambahkan spesial karakter
            $passwordErr = "Password harus mengandung minimal 1 karakter spesial (!@#$%^&*)";
        }
    }

    if (!$emailErr && !$passwordErr) { //maksud dari kode tanda seru adalah untuk memvalidasi

        if($email == "bilqisnabilah@gmail.com" && $password == "bilkids!") { // ini untuk menginput email dan password
                var_dump("Berhasil!"); // jika berhasil akan muncul berhasil
                header("Location: dashboard.php"); // dan diarahkan ke halaman selanjutnya
            }
            var_dump("Gagal!"); // jika gagal akan muncul gagal
    }
}
?>

</body>
</html>