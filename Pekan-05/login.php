<?php
$title = "Login - Bandung Heritage";
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title><?php echo $title; ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>

:root{
--color-primary-green:#4A8645;
--color-white:#ffffff;
--color-off-white:#f7f7f7;
--color-text-dark:#333;
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
background-image: radial-gradient(#9dc599,#4A8645);
}

.outer-container{
display:flex;
width:900px;
height:550px;
border-radius:25px;
overflow:hidden;
background:#fff;
box-shadow:0 20px 60px rgba(0,0,0,0.3);
}

.image-panel{
width:45%;
position:relative;
color:white;
}

#slideshow-bg{
position:absolute;
width:100%;
height:100%;
background-size:cover;
background-position:center;
}

.image-content{
position:absolute;
bottom:20px;
left:20px;
color:white;
}

.login-container{
width:55%;
padding:40px;
background:#f7f7f7;
display:flex;
flex-direction:column;
justify-content:center;
}

.greeting h1{
font-size:30px;
margin-bottom:10px;
}

.greeting p{
font-size:14px;
margin-bottom:25px;
}

.input-box input{
width:100%;
padding:12px;
margin-bottom:15px;
border-radius:8px;
border:1px solid #ccc;
}

.pass-container{
position:relative;
}

.toggle-pass{
position:absolute;
right:10px;
top:13px;
cursor:pointer;
}

.btn{
width:100%;
padding:12px;
border:none;
border-radius:8px;
background:#4A8645;
color:white;
font-weight:600;
cursor:pointer;
margin-top:10px;
}

.register{
text-align:center;
margin-top:15px;
}

.register a{
color:#4A8645;
font-weight:600;
text-decoration:none;
}

</style>
</head>

<body>

<div class="outer-container">

<div class="image-panel">
<div id="slideshow-bg"></div>

<div class="image-content">
<h3 id="slide-title">Jelajahi Warisan Bandung</h3>
<p id="slide-description">Temukan sejarah dan budaya kota Bandung</p>
</div>
</div>

<div class="login-container">

<div class="greeting">
<h1>Selamat Datang</h1>
<p>Masukkan email dan password untuk login</p>
</div>

<form action="proses_login.php" method="POST">

<div class="input-box">
<input type="email" name="email" placeholder="Email" required>
</div>

<div class="input-box pass-container">
<input type="password" name="password" id="password" placeholder="Password" required>
<i class="fa fa-eye toggle-pass" id="togglePassword"></i>
</div>

<button type="submit" class="btn">Login</button>

</form>

<div class="register">
Belum punya akun? <a href="registrasi.php">Daftar</a>
</div>

</div>
</div>

<script>

const slides=[
{
imageUrl:"bdg1.jpg",
title:"Jelajahi Warisan Bandung",
description:"Temukan sejarah dan budaya kota Bandung"
},
{
imageUrl:"bdg2.jpg",
title:"Asia Afrika",
description:"Kawasan sejarah yang terkenal di Bandung"
},
{
imageUrl:"bdg3.jpg",
title:"Jalan Braga",
description:"Tempat wisata klasik penuh sejarah"
}
];

let index=0;

function changeSlide(){
const slide=slides[index];

document.getElementById("slideshow-bg").style.backgroundImage=`url(${slide.imageUrl})`;
document.getElementById("slide-title").innerText=slide.title;
document.getElementById("slide-description").innerText=slide.description;

index=(index+1)%slides.length;
}

setInterval(changeSlide,3000);
changeSlide();

const togglePassword=document.getElementById("togglePassword");
const password=document.getElementById("password");

togglePassword.onclick=function(){
const type=password.type==="password"?"text":"password";
password.type=type;
}

</script>

</body>
</html>