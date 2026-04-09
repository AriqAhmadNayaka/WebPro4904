<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "session_init.php";
require_once "koneksi.php";

global $conn;

if (!isset($_SESSION["login"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit;
}

if (isset($_GET["id"]) && !empty($_GET["id"])) {
    $id = (int)$_GET["id"]; 


    $queryHapus = "DELETE FROM datauser WHERE id = $id";

    if (mysqli_query($conn, $queryHapus)) {
        header("Location: dashboard.php");
        exit;
    } else {
        echo "
        <script>
            alert('Gagal menghapus data: " . mysqli_error($conn) . "');
            window.location.href = 'dashboard.php';
        </script>";
    }
} else {
    header("Location: dashboard.php");
    exit;
}
?>