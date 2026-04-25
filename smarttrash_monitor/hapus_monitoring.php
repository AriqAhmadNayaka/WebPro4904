<?php

require_once "session_init.php";
require_once "Class/Auth.php";
require_once "config/database.php";

$auth = new Auth();

if (!$auth->isLoggedIn()) {
    header("Location: login.php");
    exit;
}

$user = $auth->getUser();

if ($user["role"] !== "admin") {
    die("Akses ditolak!");
}

$db = new Database();
$conn = $db->connect();

$id = $_GET["id"];

mysqli_query($conn, "DELETE FROM datauser WHERE id=$id");

header("Location: dashboard.php");
exit;