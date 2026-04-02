<?php
include("Koneksi.php");
session_start();

//mencek apakah user sudah login
if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit;
}

//cek apakah ada parameter 'id' di URL
if(isset($_GET['id'])){
    $id = $_GET['id'];
    //perintah untuk menghapus data berdasarkan ID
    $sql = "DELETE FROM user1 WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if($stmt){
        $stmt->bind_param("i", $id);
        
        if($stmt->execute()){
            //jika berhasil, balikkan ke dashboard
            header("Location: dashboard.php?pesan=hapus_berhasil");
        } else {
            echo "Gagal menghapus: " . $conn->error;
        }
        $stmt->close();
    }
} else {
    //jika tidak ada ID, balik ke dashboard
    header("Location: dashboard.php");
}

$conn->close();
?>