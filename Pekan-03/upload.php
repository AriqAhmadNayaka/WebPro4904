<?php
session_start();
if(isset($_POST['upload'])){
    $namaFile = $_FILES['foto']['name'];
    $tmp = $_FILES['foto']['tmp_name'];
    move_uploaded_file($tmp, "upload/".$namaFile);
    $_SESSION['foto'][] = $namaFile;
    header("Location: dasboard.php");
}
?>
