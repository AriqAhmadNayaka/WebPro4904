<?php
session_start();

require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../app/bootstrap.php";

$controller = new AuthController($conn);
$method = Request::method();
$action = $_GET["action"] ?? null;

if ($method === "GET" && ($action === "me" || $action === null)) {
    $controller->me();
}

if ($method === "POST" && $action === "register") {
    $controller->register();
}

if ($method === "POST" && $action === "login") {
    $controller->login();
}

if ($method === "POST" && $action === "update_profile") {
    $controller->updateProfile();
}

if (($method === "POST" && $action === "logout") || $method === "DELETE") {
    $controller->logout();
}

Response::json(["success" => false, "message" => "Endpoint auth tidak ditemukan."], 404);
