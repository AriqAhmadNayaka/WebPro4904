<?php
// 1. Memulai session agar server bisa mengingat user yang sudah berhasil login
session_start();

// 2. Membuat koneksi ke database CyberVault
$conn = mysqli_connect("localhost", "root", "", "cybervault");

// 3. Mengecek apakah form login sudah disubmit (tombol 'login' diklik)
if (isset($_POST['login'])) {
    
    /* mysqli_real_escape_string: Membersihkan input email dari karakter berbahaya.
        trim: Menghapus spasi yang tidak sengaja terketik di awal/akhir email.
    */
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $password = $_POST['password']; // Password yang diketik user (teks biasa)

    // 4. Query untuk mencari apakah ada user dengan email tersebut di database
    $result = mysqli_query($conn, "SELECT * FROM datauser WHERE email = '$email'");

    // 5. Cek apakah email ditemukan (jumlah baris hasil query harus sama dengan 1)
    if (mysqli_num_rows($result) === 1) {
        
        // Mengambil data user tersebut dari database ke dalam bentuk array $row
        $row = mysqli_fetch_assoc($result);
        
        /* password_verify: Fungsi PHP paling penting di sini.
            Ia akan mencocokkan password teks biasa yang diketik user ($password) 
            dengan password hash yang terenkripsi di database ($row['password']).
        */
        if (password_verify($password, $row['password'])) {
            
            // Jika cocok, buat session 'login' menjadi true
            $_SESSION['login'] = true;
            // Menyimpan nama user ke session agar bisa ditampilkan di halaman lain (misal: "Halo, Admin")
            $_SESSION['nama'] = $row['nama'];
            
            // Pindahkan user ke halaman utama manajemen data
            header("Location: datauser.php");
            exit; // Menghentikan script agar tidak mengeksekusi kode di bawahnya
        }
    }
    
    // 6. Jika email tidak ditemukan ATAU password salah, buat pesan error
    $error = "Email atau Password salah!";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login • CyberVault</title>
<style>
        /*CSS untuk mempercantik tampilan form*/
        body { font-family: sans-serif; background: #0a0a0f; color: #fff; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .box { background: rgba(255,255,255,0.05); padding: 30px; border-radius: 12px; border: 1px solid #00d4ff; width: 300px; }
        input { width: 100%; padding: 10px; margin: 10px 0; background: #1a1a2e; border: 1px solid #333; color: #fff; border-radius: 5px; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background: #00d4ff; border: none; font-weight: bold; cursor: pointer; border-radius: 5px; color: #000; }
    </style>
</head>
<body>
    <div class="box">
        <h1 style="text-align:center; color:#00d4ff; margin-top: 0;">CyberVault</h1>
        <h2 style="text-align:center; color:#00d4ff; font-weight: normal; font-size: 18px; margin-bottom: 30px;">Login System</h2>
        
        <?php if(isset($error)) echo "<div class='error'>$error</div>"; ?>
        
        <form action="" method="POST">
            <input type="email" name="email" placeholder="Email" required autofocus>
            <input type="password" name="password" placeholder="Password" required>
            
            <button type="submit" name="login">Masuk</button>
            
            <p style="text-align:center; font-size:12px; margin-top: 20px; color: #888;">
                Belum punya akun? <a href="daftar.php" style="color:#00d4ff;">Daftar di sini</a>
            </p>
        </form>
    </div>
</body>
</html>