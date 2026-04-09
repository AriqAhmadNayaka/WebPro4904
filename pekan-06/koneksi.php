<?php
class Database { // Kelas untuk menangani koneksi ke database
    public $host = "localhost";  // Properti untuk menyimpan host database
    public $username = "root"; // Properti untuk menyimpan username database
    public $password = ""; // Properti untuk menyimpan password database
    public $namadb = "desa_kita"; // Properti untuk menyimpan nama database
    public $conn; // Properti untuk menyimpan koneksi database

    public function __construct() { // Konstruktor untuk menginisialisasi koneksi database saat objek dibuat
        $this->conn = mysqli_connect($this->host, $this->username, $this->password); // Membuat koneksi ke database menggunakan properti yang telah didefinisikan
        mysqli_select_db($this->conn, $this->namadb); // Memilih database yang akan digunakan untuk koneksi
    }
}
?>