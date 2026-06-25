<?php
session_start();

require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../app/bootstrap.php";

$userId = Auth::userId();
$user = $userId ? User::findById($conn, $userId) : null;

if (!$user || ($user["role"] ?? "user") !== "admin") {
    header("Location: home.php");
    exit;
}
?>
