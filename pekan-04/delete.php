<?php

include 'koneksi.php';//koneksi ke database
$id = $_GET['id_artikel']; { //mengambil id_artikel dari url yang dikirim dari file artikel_psikolog.php
    $query = "DELETE FROM artikel WHERE id_artikel = '$id'"; //membuat query untuk menghapus data artikel
    //  dari database dengan id_artikel yang diambil dari url
    mysqli_query($conn, $query);//menjalankan query dan juga koneksi
    header("Location:artikel_psikolog.php"); //mengalihkan ke halaman artikel_psikolog.php
}
?>