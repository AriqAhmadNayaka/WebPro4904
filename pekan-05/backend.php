<?php
include 'koneksi.php';

if (isset($_POST['simpan'])) {
    $id = $_POST['id'];
    $judul = $_POST['judul'];
    $kategori = $_POST['kategori'];
    $deskripsi = $_POST['deskripsi'];
    
    // Logika Upload File
    $nama_gambar = $_FILES['gambar']['name'];
    $tmp_name = $_FILES['gambar']['tmp_name'];
    
    if ($id == "") { 
        // --- CREATE ---
        move_uploaded_file($tmp_name, "uploads/" . $nama_gambar);
        $query = "INSERT INTO konten_edukasi (judul, kategori, deskripsi, gambar) 
                  VALUES ('$judul', '$kategori', '$deskripsi', '$nama_gambar')";
    } else { 
        // --- UPDATE ---
        if ($nama_gambar != "") {
            // Jika user upload gambar baru
            move_uploaded_file($tmp_name, "uploads/" . $nama_gambar);
            $query = "UPDATE konten_edukasi SET judul='$judul', kategori='$kategori', 
                      deskripsi='$deskripsi', gambar='$nama_gambar' WHERE id=$id";
        } else {
            // Jika gambar tidak diganti
            $query = "UPDATE konten_edukasi SET judul='$judul', kategori='$kategori', 
                      deskripsi='$deskripsi' WHERE id=$id";
        }
    }

    if (mysqli_query($conn, $query)) {
        header("Location: index.php");
    }
}

// --- DELETE ---
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    // Hapus file fisik jika perlu
    $data = mysqli_query($conn, "SELECT gambar FROM konten_edukasi WHERE id=$id");
    $row = mysqli_fetch_assoc($data);
    unlink("uploads/" . $row['gambar']);

    mysqli_query($conn, "DELETE FROM konten_edukasi WHERE id=$id");
    header("Location: index.php");
}
?>