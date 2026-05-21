<?php

class Database {
    private static $instance = null;
    private $pdo;

    private function __construct() {
        $dsn = 'mysql:host=localhost;dbname=web_crud_db;charset=utf8mb4';
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
x        ];
        $this->pdo = new PDO($dsn, 'root', '', $options);
    }

    public static function getInstance() {  
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getPdo() {
        return $this->pdo;
    }

    /**
     * Create database schema + tables (idempotent/safe to run multiple times)
     */
    public static function createSchema() {
        try {
            $masterPdo = new PDO('mysql:host=localhost;charset=utf8mb4', 'root', '');
            $masterPdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Create DB
            $masterPdo->exec('CREATE DATABASE IF NOT EXISTS web_crud_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
            
            // Switch to app DB
            $masterPdo->exec('USE web_crud_db');

            // Users table
            $masterPdo->exec("CREATE TABLE IF NOT EXISTS `users` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `username` varchar(50) NOT NULL UNIQUE,
                `password` varchar(255) NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

            // Products table  
            $masterPdo->exec("CREATE TABLE IF NOT EXISTS `produk` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `nama` varchar(100) NOT NULL,
                `harga` decimal(10,2) NOT NULL,
                `deskripsi` text,
                `gambar` varchar(255) DEFAULT NULL,
                `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

            // Default admin user (admin/password)
            $hashed = password_hash('password', PASSWORD_DEFAULT);
            $stmt = $masterPdo->prepare("INSERT IGNORE INTO users (username, password) VALUES (?, ?)");
            $stmt->execute(['admin', $hashed]);

            $masterPdo = null; // Close connection
            return ['success' => true, 'message' => 'Schema ready!'];

        } catch (PDOException $e) {
            return ['success' => false, 'error' => 'Schema error: ' . $e->getMessage()];
        }
    }
}
?>