<?php

class Database
{
    private string $host = 'localhost';
    private string $user = 'root';
    private string $pass = '';
    private string $database = 'db_webandoo_data';
    private ?mysqli $connection = null;

    public function getConnection(): mysqli
    {
        if ($this->connection instanceof mysqli) {
            return $this->connection;
        }

        $this->connection = mysqli_connect($this->host, $this->user, $this->pass, $this->database);

        if (!$this->connection) {
            throw new RuntimeException('Koneksi gagal: ' . mysqli_connect_error());
        }

        return $this->connection;
    }
}

$database = new Database();
$conn = $database->getConnection();
?>
