<?php

/**
 * Class Database
 * Mengelola koneksi database menggunakan pola Singleton untuk memastikan
 * hanya ada satu koneksi yang aktif selama siklus hidup request.
 */
class Database {
    private static $instance = null;
    private $conn;

    // Konfigurasi database
    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $db_name = "nuids_db";

    /**
     * Constructor private untuk mencegah instansiasi langsung dari luar kelas.
     */
    private function __construct() {
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->db_name);

        // Cek koneksi
        if ($this->conn->connect_error) {
            die("Koneksi Gagal! " . $this->conn->connect_error);
        }
    }

    /**
     * Mendapatkan instansi tunggal dari kelas Database.
     */
    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    /**
     * Mendapatkan objek koneksi mysqli.
     */
    public function getConnection() {
        return $this->conn;
    }
}
