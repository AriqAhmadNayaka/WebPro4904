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

if (!isset($_GET["id"]) || !ctype_digit($_GET["id"])) {
    header("Location: dashboard.php");
    exit;
}

$id = (int) $_GET["id"];
$deleteQuery = mysqli_query($conn, "DELETE FROM datauser WHERE id=$id");

if (!$deleteQuery) {
    die("Gagal menghapus user: " . mysqli_error($conn));
}

header("Location: dashboard.php");
exit;
