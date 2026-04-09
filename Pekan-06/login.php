<style>
    :root {
    --main-color: #4a6fa5;
    --glass-bg: rgba(255, 255, 255, 0.4);
    --input-bg: rgba(255, 255, 255, 0.8);
}

body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: linear-gradient(135deg, #e3f2fd, #f8f9fa);
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

/* CONTAINER */
.login-container {
    width: 350px;
    padding: 40px;
    border-radius: 20px;
    background: var(--glass-bg);
    backdrop-filter: blur(10px);
    box-shadow: 0 8px 32px rgba(0,0,0,0.1);
    text-align: center;
}

/* TITLE */
.login-container h2 {
    margin-bottom: 30px;
    color: #333;
}

/* INPUT */
.login-container input {
    width: 100%;
    padding: 12px;
    margin-bottom: 15px;
    border-radius: 10px;
    border: none;
    background: var(--input-bg);
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.login-container input:focus {
    outline: none;
    background: white;
    box-shadow: 0 0 0 3px rgba(74,111,165,0.3);
}

/* BUTTON */
.login-container button {
    width: 100%;
    padding: 12px;
    border-radius: 12px;
    border: none;
    background: rgba(0,123,255,0.85);
    color: white;
    font-weight: bold;
    cursor: pointer;
    transition: 0.3s;
}

.login-container button:hover {
    background: rgba(0,123,255,1);
}
</style>

<?php
session_start(); //Memulai session untuk menyimpan status login user

if(isset($_POST['login'])){ //Mengecek apakah tombol login sudah ditekan
    $user = $_POST['username']; //Mengambil input username dari form
    $pass = $_POST['password']; //Mengambil input password dari form
 
    if($user == "admin" && $pass == "123"){ //Mengecek apakah username = admin dan password = 123
        $_SESSION['login'] = true; //Jika benar, menyimpan status login ke session
        header("Location: inputdata.php"); //Mengarahkan user ke halaman inputdata.php setelah login berhasil
        exit; //Menghentikan program agar tidak lanjut ke bawah
    } else {
        echo "<script>alert('Login gagal!');</script>"; //Menampilkan alert "Login gagal!" menggunakan JavaScript
    }
}
?>

<div class="login-container"> //Container untuk tampilan form login
    <h2 class="login-title">Login</h2> //Judul halaman login
    
    <form method="POST"> //Form menggunakan metode POST untuk mengirim data
        <input type="text" name="username" class="login-input" placeholder="Username"> //Input untuk memasukkan username
        
        <input type="password" name="password" class="login-input" placeholder="Password"> //Input untuk memasukkan password
        
        <button class="btn-login" name="login">Login</button> //untuk dideteksi di PHP
    </form>
</div>