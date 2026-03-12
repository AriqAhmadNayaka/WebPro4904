<!DOCTYPE html>
<html>
<head>
<title>Registrasi</title>

<style>
:root {
  --primary: #00d4ff;
  --primary-dark: #0099cc;
  --card: rgba(15, 15, 35, 0.65);
  --text: #e0e6ed;
  --text-muted: #8892b0;
  --shadow: 0 15px 35px rgba(0,0,0,0.6);
  --radius: 20px;
}

*{
  margin:0;
  padding:0;
  box-sizing:border-box;
  font-family:Poppins, sans-serif;
}

body{
  min-height:100vh;
  display:flex;
  justify-content:center;
  align-items:center;
  background:linear-gradient(135deg,#0a0a0a,#16213e);
  color:var(--text);
}

.form-box{
  width:380px;
  background:var(--card);
  padding:40px;
  border-radius:var(--radius);
  box-shadow:var(--shadow);
  backdrop-filter:blur(10px);
}

.form-box h2{
  text-align:center;
  margin-bottom:25px;
  font-size:28px;
  color:var(--primary);
}

.input{
  width:100%;
  padding:14px 18px;
  border-radius:30px;
  border:1px solid rgba(0,212,255,0.4);
  background:rgba(255,255,255,0.05);
  color:white;
  font-size:14px;
  margin-bottom:15px;
}

.input:focus{
  outline:none;
  border-color:var(--primary);
}

.btn-login{
  width:100%;
  padding:14px;
  border:none;
  border-radius:30px;
  background:linear-gradient(45deg,var(--primary),var(--primary-dark));
  color:white;
  font-size:16px;
  font-weight:600;
  cursor:pointer;
}

.result{
  margin-top:20px;
  font-size:14px;
  text-align:center;
  color:var(--text-muted);
}
</style>
</head>

<body>

<div class="form-box">

<h2>Registrasi</h2>

<!--
Form ini digunakan untuk mengambil data dari user.
Data dikirim menggunakan method POST agar bisa diproses oleh PHP.
-->
<form method="POST">
<!-- input username -->
<input type="text" name="username" class="input" placeholder="Masukkan username" required>
<!-- input email -->
<input type="email" name="email" class="input" placeholder="Masukkan Email" required>
<!-- input password -->
<input type="password" name="password" class="input" placeholder="Masukkan Password" required>
<!-- tombol untuk mengirim data -->
<button type="submit" class="btn-login">Registrasi</button>
</form>

<div class="result">

<?php

/*
Bagian ini mengecek apakah form sudah dikirim
Jika semua input sudah ada, maka data akan diproses
*/
if(
isset($_POST["email"]) &&
isset($_POST["username"]) &&
isset($_POST["password"])
){

// mengambil data dari form
$username = $_POST["username"];
$email = $_POST["email"];
$password = $_POST["password"];

echo "Registrasi berhasil! <br>";
echo "Username : " . $username . "<br>";
echo "Email : " . $email . "<br>";
echo "Password : " . $password . "<br>";

}
?>

</div>

</div>

</body>
</html>