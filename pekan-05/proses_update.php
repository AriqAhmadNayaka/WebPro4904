<?php
include 'koneksi.php'; //koneksi ke database
    $query = mysqli_query($koneksi, "UPDATE artikel set 
    -- id_barang = '$_POST[id_barang]',
    isi_artikel = '$_POST[isi_artikel]',
    foto = '$_POST[foto]'--mengupdate data artikel di database dengan data baru di form

    where id_artikel = '$_POST[id_artikel]'") or die(mysqli_error($koneksi)); //mengambil id artikel yang ingin di update
    header("location:artikel_psikolog.php"); //ketika berhasil maka akan langsung di arahkan ke artikel_psikolog.php
?>