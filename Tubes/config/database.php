<?php
$defaultConfig = [
    "host" => getenv("DB_HOST") ?: "localhost",
    "user" => getenv("DB_USER") ?: "root",
    "pass" => getenv("DB_PASS") ?: "",
    "name" => getenv("DB_NAME") ?: "db_webandoo_data",
    "create_database" => true
];

$localConfigPath = __DIR__ . "/database.local.php";
if (file_exists($localConfigPath)) {
    $customConfig = require $localConfigPath;
    if (is_array($customConfig)) {
        $defaultConfig["create_database"] = false;
        $defaultConfig = array_merge($defaultConfig, $customConfig);
    }
}

$host = $defaultConfig["host"];
$user = $defaultConfig["user"];
$pass = $defaultConfig["pass"];
$db = $defaultConfig["name"];

if ($defaultConfig["create_database"]) {
    $conn = mysqli_connect($host, $user, $pass);
} else {
    $conn = mysqli_connect($host, $user, $pass, $db);
}

if (!$conn) {
    http_response_code(500);
    header("Content-Type: application/json; charset=utf-8");
    echo json_encode([
        "success" => false,
        "message" => "Koneksi database gagal: " . mysqli_connect_error()
    ]);
    exit;
}

mysqli_set_charset($conn, "utf8mb4");

if ($defaultConfig["create_database"]) {
    $createDb = "CREATE DATABASE IF NOT EXISTS `$db` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci";
    if (!mysqli_query($conn, $createDb) || !mysqli_select_db($conn, $db)) {
        http_response_code(500);
        header("Content-Type: application/json; charset=utf-8");
        echo json_encode([
            "success" => false,
            "message" => "Database gagal disiapkan: " . mysqli_error($conn)
        ]);
        exit;
    }
}
?>
