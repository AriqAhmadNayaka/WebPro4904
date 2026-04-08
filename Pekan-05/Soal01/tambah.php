<?php
// Menghubungkan file koneksi database
include "koneksi.php";

// Mengecek apakah tombol "simpan" ditekan
if(isset($_POST['simpan'])){
    
    // Mengambil data dari form
    $judul = $_POST['judul']; // input nama
    $isi   = $_POST['isi'];   // input bio

    // Mengambil informasi file gambar yang diupload
    $nama_file = $_FILES['gambar']['name'];      // nama file
    $source    = $_FILES['gambar']['tmp_name'];  // lokasi file sementara
    $folder    = 'uploads/';                    // folder tujuan penyimpanan

    // CEK APAKAH FOLDER ADA
    if (!is_dir($folder)) {
        // Jika folder tidak ada, tampilkan error dan hentikan program
        die("Error: Folder 'uploads' tidak ditemukan! Buatlah folder bernama uploads di direktori yang sama.");
    }

    // PROSES PINDAH FILE dari temporary ke folder uploads
    if(move_uploaded_file($source, $folder.$nama_file)){
        
        // Jika upload berhasil, simpan data ke database
        mysqli_query($conn, "INSERT INTO catatan (judul, isi, gambar) 
                             VALUES ('$judul', '$isi', '$nama_file')");
        
        // Redirect ke halaman dashboard
        header("Location: dashboard.php");

    } else {
        // Jika upload gagal, tampilkan pesan error
        echo "Gagal Upload! Cek izin akses folder atau ukuran file.";
        
        // Menampilkan kode error upload dari PHP
        print_r($_FILES['gambar']['error']); 
    }
}
?>

<!-- Menghubungkan file CSS -->
<link rel="stylesheet" href="login.css">

<!-- Tampilan form input -->
<div class="form-box" style="margin:120px auto;">
    <h2>Tambah Profil</h2>

    <!-- Form dengan enctype multipart/form-data untuk upload file -->
    <form method="POST" enctype="multipart/form-data">
        
        <!-- Input nama -->
        <input type="text" name="judul" class="input" placeholder="Nama" required>
        
        <!-- Input bio -->
        <textarea name="isi" class="input" placeholder="Bio" required></textarea>
        
        <!-- Input upload gambar -->
        <input type="file" name="gambar" class="input" required>
        
        <!-- Tombol submit -->
        <button name="simpan" class="btn-login">Simpan & Upload</button>
    
    </form>
</div>