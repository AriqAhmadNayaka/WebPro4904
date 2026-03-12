<?php

$name = $email = $password = ""; // Variabel ini  untuk menyimpan input dan error
$nameErr = $emailErr = $passwordErr = "";// Sedangkan variabel iniuntuk menyimpan pesan error
$success = "";// Variabel ini untuk menyimpan pesan sukses

function test_input($data){// Fungsi untuk membersihkan input
    return htmlspecialchars(stripslashes(trim($data)));// Fungsi ini akan menghapus spasi, backslash, dan mengubah karakter khusus menjadi entitas HTML
}

if($_SERVER["REQUEST_METHOD"] == "POST"){// Cek apakah form disubmit dengan metode POST

    if(empty($_POST["name"])){// Cek apakah namanya kosong
        $nameErr = "Nama harus diisi";// Jika kosong, set pesan error
    }else{
        $name = test_input($_POST["name"]);// Bersihkan input nama
        if(!preg_match("/^[a-zA-Z ]*$/",$name)){// Cek apakah nama hanya mengandung huruf dan spasi
            $nameErr = "Nama hanya boleh huruf dan spasi";// Jika tdk, set pesan error
        }
    }

   
    if(empty($_POST["email"])){
        $emailErr = "Email harus diisi";// Mengecek emailnya kosong gk
    }else{
        $email = test_input($_POST["email"]);// Bersihkan input email
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){// Cek apakah format email valid
            $emailErr = "Format email tidak valid";
        }
    }

    
    if(empty($_POST["password"])){
        $passwordErr = "Password harus diisi";// Mengecek passwordnya kosong gk
    }else{
        $password = test_input($_POST["password"]);//

        if(strlen($password) < 6){// Mengecek apakah password minimal 6 karakter
            $passwordErr = "Password minimal 6 karakter";
        }
    }


    if(empty($nameErr) && empty($emailErr) && empty($passwordErr)){// Jika tidak ada error, set pesan sukses
        $success = "Registrasi berhasil!";// Pesan sukses
    }
}
?>

    <!DOCTYPE html>
    <html>
    <head>
    <title>Register EcoTaste</title>

        <style>

        body{
        font-family: Arial;
        background: linear-gradient(135deg,#66BB6A,#8BC34A);
        display:flex;
        justify-content:center;
        align-items:center;
        height:100vh;
        margin:0;
        }

        .box{
        background:white;
        padding:40px;
        border-radius:20px;
        width:350px;
        box-shadow:0 10px 30px rgba(0,0,0,0.2);
        }

        h2{
        color:#4CAF50;
        text-align:center;
        }

        input{
        width:100%;
        padding:12px;
        margin-top:10px;
        border-radius:25px;
        border:1px solid #ccc;
        }

        button{
        width:100%;
        padding:12px;
        margin-top:15px;
        border:none;
        border-radius:25px;
        background:#4CAF50;
        color:white;
        font-size:16px;
        cursor:pointer;
        }

        .error{
        color:red;
        font-size:13px;
        }

        .success{
        color:green;
        text-align:center;
        margin-bottom:10px;
        }

        .login-link{
        text-align:center;
        margin-top:15px;
        }

        </style>

        </head>

        <body>

        <div class="box">

        <h2>Register EcoTaste</h2>

        <?php
        if($success){
        echo "<div class='success'>$success</div>";
        }
        ?>

        <form method="POST">

        <input type="text" name="name" placeholder="Nama">
        <div class="error"><?php echo $nameErr;?></div>

        <input type="text" name="email" placeholder="Email">
        <div class="error"><?php echo $emailErr;?></div>

        <input type="password" name="password" placeholder="Password">
        <div class="error"><?php echo $passwordErr;?></div>

        <button type="submit">Register</button>

        </form>

        <div class="login-link">
        Sudah punya akun? <a href="login.php">Login</a>
        </div>

        </div>

        </body>
        </html>