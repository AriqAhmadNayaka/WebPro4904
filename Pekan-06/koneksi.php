<?php
class Database { // Kelas untuk mengelola koneksi database
    private $host = "localhost"; // Sesuaikan dengan host database
    private $username = "root"; // Sesuaikan dengan username database
    private $password = ""; // Sesuaikan dengan password database
    private $database = "lifetrack"; // Sesuaikan dengan nama database
    public $conn; // Variabel untuk menyimpan koneksi yang akan digunakan oleh file lain

    public function __construct() { // Konstruktor untuk membuat koneksi saat objek dibuat
        // Menggunakan mysqli secara OOP
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->database); // Membuat koneksi ke database

        if ($this->conn->connect_error) { // Cek koneksi
            die("Koneksi gagal: " . $this->conn->connect_error); // Jika koneksi gagal, tampilkan pesan error
        }
    }
}

$db = new Database();// Membuat objek database yang akan mengelola koneksi
$conn = $db->conn; // Variabel ini digunakan oleh file lain
?>