<?php
require_once 'config.php';
$id = $_GET['id'];
mysqli_query($conn, "DELETE FROM waste_reports WHERE id=$id");
header("Location: index.php");
?>