<?php
include 'koneksi.php';

if (isset($_POST['simpan'])) {
    $id = $_POST['id']; // Ambil ID dari input hidden
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $kategori = $_POST['kategori'];
    
    // Proses File Gambar
    $gambar = $_FILES['gambar']['name'];
    $tmp = $_FILES['gambar']['tmp_name'];
    
    if (!empty($gambar)) {
        move_uploaded_file($tmp, "uploads/" . $gambar);
        $gambar_sql = ", gambar='$gambar'";
    } else {
        $gambar_sql = "";
    }

    if (empty($id)) {
        // Jika ID kosong = INSERT (Data Baru)
        $query = "INSERT INTO konten_edukasi (judul, kategori, gambar) VALUES ('$judul', '$kategori', '$gambar')";
    } else {
        // Jika ID isi = UPDATE (Edit Data)
        $query = "UPDATE konten_edukasi SET judul='$judul', kategori='$kategori' $gambar_sql WHERE id='$id'";
    }

    mysqli_query($conn, $query);
    header("Location: index.php");
}

// Logika Hapus
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM konten_edukasi WHERE id='$id'");
    header("Location: index.php");
}
?>