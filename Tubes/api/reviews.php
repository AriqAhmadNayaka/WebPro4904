<?php
session_start();

require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../app/bootstrap.php";

$controller = new ReviewController($conn);
$method = Request::method();
$action = $_GET["action"] ?? null;
$id = isset($_GET["id"]) ? (int) $_GET["id"] : null;

if ($method === "GET" && $action === "my_reviews") {
    $controller->mine();
}

if ($method === "GET" && ($action === "list" || $action === null) && !$id) {
    $controller->index();
}

if ($method === "GET" && $id) {
    $controller->show($id);
}

if ($method === "POST" && ($action === "create" || $action === null)) {
    $controller->store();
}

if (($method === "POST" && $action === "update") || $method === "PUT" || $method === "PATCH") {
    $controller->update($id);
}

if (($method === "POST" && $action === "delete") || $method === "DELETE") {
    $controller->destroy($id);
}

Response::json(["success" => false, "message" => "Endpoint review tidak ditemukan."], 404);
