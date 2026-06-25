<?php
session_start();

require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../app/bootstrap.php";

$controller = new ContentController($conn);
$method = Request::method();
$id = isset($_GET["id"]) ? (int) $_GET["id"] : null;

if ($method === "GET") {
    $controller->index();
}

if ($method === "DELETE" || ($method === "POST" && ($_GET["action"] ?? "") === "delete")) {
    $controller->destroy($id);
}

if ($method === "POST") {
    $controller->store();
}

Response::json(["success" => false, "message" => "Endpoint konten tidak ditemukan."], 404);
