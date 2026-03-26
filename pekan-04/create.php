<?php
include 'koneksi.php';

if(isset($_POST['submit'])) { //cek apakah form nya sudah di isi apa belum
    $isi_artikel = $_POST['isi_artikel'];
    $sql = "INSERT INTO artikel VALUES ('', '$isi_artikel')";
    mysqli_query($conn, $sql);
    header("location:artikel_psikolog.php"); //mengalihkan ke halaman artikel_psikolog.php setelah berhasil mengirim artikel
}
?>