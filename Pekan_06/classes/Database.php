<?php

require_once __DIR__ . '/AppConfig.php';

class Database
{
    private mysqli $connection;

    public function __construct()
    {
        $this->connection = new mysqli(
            AppConfig::DB_HOST,
            AppConfig::DB_USER,
            AppConfig::DB_PASS,
            AppConfig::DB_NAME
        );

        if ($this->connection->connect_error) {
            throw new RuntimeException('Koneksi database gagal: ' . $this->connection->connect_error);
        }

        $this->connection->set_charset('utf8mb4');
    }

    public function getConnection(): mysqli
    {
        return $this->connection;
    }
}
