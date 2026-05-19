<?php
class Database {
    private string $host = "localhost";
    private string $user = "root";
    private string $password = "";
    private string $dbname = "db_monitoring_sampah";

    private static ?Database $instance = null;

    private mysqli $conn;

    private function __construct() {
        $this->conn = mysqli_connect(
          $this->host,
            $this->user,
            $this->password,
            $this->dbname  
        );

       if(!$this->conn) {
            die("koneksi gagal");
       }
    }

    public static function getInstance(): Database {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConection() : mysqli {
        return $this->conn;
    }
}
?>