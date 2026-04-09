<?php
class Database {

    // deklarasi properti untuk menyimpan konfigurasi database
    private $host = "localhost";      // alamat server database
    private $username = "root";       // username MySQL
    private $password = "";           // password MySQL
    private $database = "loginuser";  // nama database
    public $conn;                     // variabel untuk menyimpan koneksi

    public function __construct() {
        // membuat koneksi ke database menggunakan mysqli
        $this->conn = new mysqli(
            $this->host,
            $this->username,
            $this->password,
            $this->database
        );

        // mengecek apakah koneksi berhasil atau gagal
        if ($this->conn->connect_error) {
            die("Koneksi gagal: " . $this->conn->connect_error);
        }
    }
    // method untuk mengambil koneksi database
    public function getConnection() {
        return $this->conn; // mengembalikan objek koneksi
    }
}
?>