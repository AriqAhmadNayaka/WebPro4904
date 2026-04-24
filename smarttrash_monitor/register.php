<?php
// Membuka koneksi ke database MySQL (host, username, password, nama_database)
$conn = mysqli_connect("localhost", "root", "", "smarttrash_monitor");

// Mengecek apakah tombol dengan name 'daftar' sudah diklik/dikirim melalui method POST
if (isset($_POST['daftar'])) {
    
    /* mysqli_real_escape_string: Mengamankan input dari karakter aneh yang bisa merusak query (SQL Injection).
       trim: Menghapus spasi kosong di awal dan akhir teks.
    */
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $password_raw = $_POST['password']; // Mengambil password asli dari form

    // Query SQL untuk mengecek apakah email yang dimasukkan sudah ada di tabel datauser
    $cek = mysqli_query($conn, "SELECT email FROM monitoring WHERE email = '$email'");
    
    // mysqli_num_rows: Menghitung jumlah baris hasil query. Jika > 0, berarti email sudah terdaftar.
    if (mysqli_num_rows($cek) > 0) {
        echo "<script>alert('Email sudah digunakan! Gunakan email lain.');</script>";
    } else {
        
        /* password_hash: Mengubah password teks biasa (rahasia123) menjadi kode acak panjang yang aman. 
           PASSWORD_DEFAULT: Menggunakan algoritma enkripsi standar terbaru (saat ini bcrypt).
        */
        $password_hash = password_hash($password_raw, PASSWORD_DEFAULT);

        // Menyiapkan perintah SQL untuk memasukkan data baru ke tabel datauser
        $sql = "INSERT INTO monitoring (nama, email, password) VALUES ('$nama', '$email', '$password_hash')";
        
        // Menjalankan query ke database
        if (mysqli_query($conn, $sql)) {
            // Jika berhasil, tampilkan pesan dan pindahkan user ke halaman login menggunakan JavaScript
            echo "<script>alert('Pendaftaran Berhasil! Silakan Login.'); window.location.href='login.php';</script>";
        } else {
            // Jika gagal (misal: koneksi putus atau tabel error), tampilkan pesan error sistem
            echo "Terjadi kesalahan sistem: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sign Up • CyberVault</title>
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
        <h1 style="text-align:center; color:#00d4ff;">smart trash</h1>
        <h2 style="text-align:center; color:#00d4ff;">Sign Up</h2>
        
        <form action="" method="POST">
            <input type="text" name="nama" placeholder="Nama Lengkap" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            
            <button type="submit" name="daftar">Daftar Akun</button>
            
            <p style="text-align:center; font-size:12px;">Sudah punya akun? <a href="login.php" style="color:#00d4ff;">Login</a></p>
        </form>
    </div>
</body>
</html>