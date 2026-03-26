<?php
include 'koneksi.php'; //menghubungkan ke database
if($SERVER['REQUEST_METHOD'] == 'POST') {  //cek apakah form sudah di submit atau belum dengan method post
    $id_artikel = $_POST['id_artikel']; //mengambil id_artikel dari form yang dikirim dari file edit.php
    $isi_artikel = $_POST['isi_artikel']; //mengambil isi_artikel dari form yang dikirim dari file edit.php
    $sql = "UPDATE artikel SET isi_artikel='$isi_artikel' WHERE id_artikel='$id_artikel'"; //membuat query untuk mengupdate
    //  data artikel di database dengan id_artikel yang diambil dari form
    mysqli_query($conn, $sql);//menjalankan query dan juga koneksi
    header("Location:artikel_psikolog.php"); //mengalihkan ke halaman artikel_psikolog.php jika berhasil
}
?>