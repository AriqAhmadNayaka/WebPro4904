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

/* CARD FORM */
.form-box{
  width:380px;
  background:var(--card);
  padding:40px;
  border-radius:var(--radius);
  box-shadow:var(--shadow);
  backdrop-filter:blur(10px);
}

/* TITLE */
.form-box h2{
  text-align:center;
  margin-bottom:25px;
  font-size:28px;
  color:var(--primary);
}

/* INPUT */
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

/* BUTTON */
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
  transition:0.3s;
}

.btn-login:hover{
  transform:translateY(-2px);
}

/* RESULT */
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

<h2>Login</h2>

<form method="POST">

<input type="email" name="email" class="input" placeholder="Masukkan Email" required>

<input type="password" name="password" class="input" placeholder="Masukkan Password" required>

<button type="submit" class="btn-login">Registrasi</button>

</form>

<div class="result">
<?php
if(isset($_POST["email"]) && isset($_POST["password"])){

$email = $_POST["email"];
$password = $_POST["password"];

echo "Registrasi berhasil! <br>";
echo "Email : " . $email . "<br>";
echo "Password : " . $password;
}
?>
</div>

</div>

</body>
</html>