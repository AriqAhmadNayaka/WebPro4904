<?php
include 'config.php';
session_start();

$id = $_GET["id"];
$sql = "SELECT file_path FROM articles WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$file = $result->fetch_assoc()["file_path"];

if (file_exists($file)) unlink($file);

$sql = "DELETE FROM articles WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: home.php");
exit();
?>
