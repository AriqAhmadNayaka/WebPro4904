<?php
include 'koneksi.php';

// CEK tombol simpan ditekan
if (isset($_POST['simpan'])) {

    // Ambil data dari form
    $id = $_POST['id'] ?? '';
    $judul = $_POST['judul'] ?? '';
    $kategori = $_POST['kategori'] ?? '';
    $deskripsi = $_POST['deskripsi'] ?? '';

    // Validasi sederhana
    if ($judul == '' || $kategori == '') {
        die("Data tidak boleh kosong");
    }

    // =========================
    // UPLOAD GAMBAR
    // =========================
    $gambar = '';

    if (!empty($_FILES['gambar']['name'])) {
        $nama_file = $_FILES['gambar']['name'];
        $tmp = $_FILES['gambar']['tmp_name'];

        move_uploaded_file($tmp, "uploads/" . $nama_file);
        $gambar = $nama_file;
    }

    // =========================
    // MODE INSERT / UPDATE
    // =========================

    if ($id == '') {
        // INSERT
        $query = "INSERT INTO konten_edukasi (judul, kategori, deskripsi, gambar)
                  VALUES ('$judul', '$kategori', '$deskripsi', '$gambar')";
    } else {
        // UPDATE
        if ($gambar != '') {
            $query = "UPDATE konten_edukasi 
                      SET judul='$judul', kategori='$kategori', deskripsi='$deskripsi', gambar='$gambar' 
                      WHERE id='$id'";
        } else {
            $query = "UPDATE konten_edukasi 
                      SET judul='$judul', kategori='$kategori', deskripsi='$deskripsi' 
                      WHERE id='$id'";
        }
    }

    // Eksekusi query
    if (!mysqli_query($conn, $query)) {
        die("Query error: " . mysqli_error($conn));
    }

    // Redirect balik
    header("Location: index.php");
    exit;
}

// =========================
// HAPUS DATA
// =========================
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];

    mysqli_query($conn, "DELETE FROM konten_edukasi WHERE id='$id'");
    header("Location: index.php");
}
?>