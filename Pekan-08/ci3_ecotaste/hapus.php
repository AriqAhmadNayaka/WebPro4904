<?php
session_start();

if (!isset($_SESSION['login'])) {
    header('Location: login.php');
    exit;
}

require 'database.php';
$db = new Database();

if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];

    if ($db->deleteMenu($id)) {
        echo "<script>
                alert('Data berhasil dihapus!');
                document.location.href = 'homepage.php';
              </script>";
    } else {
        echo "<script>
                alert('Gagal menghapus data!');
                document.location.href = 'homepage.php';
              </script>";
    }
} else {
    header('Location: homepage.php');
    exit;
}
?>
