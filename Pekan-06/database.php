<?php
// Berfungsi untuk menghubungkan aplikasi web dengan database MySQL menggunakan mysqli
class Database {
    // Deklarasi properti untuk menyimpan informasi koneksi database
    // Properti private: hanya bisa diakses dari dalam class ini untuk keamanan (Enkapsulasi)
    private $host = "localhost";
    private $user = "root";
    private $pass = "";
    private $db = "navibiz";

    // Properti public untuk menyimpan objek koneksi database yang dapat diakses dari luar class
    public $database;

    // Konstruktor: method khusus yang otomatis dipanggil saat objek dibuat
    public function __construct() {
        // Membuat koneksi ke database menggunakan mysqli dan menyimpan objek koneksi di properti $database
        $this->database = new mysqli($this->host, $this->user, $this->pass, $this->db);
        
        // Mengecek apakah koneksi berhasil, jika gagal maka program akan berhenti dan menampilkan pesan error
        if ($this->database->connect_error) {
            // die() akan menghentikan seluruh proses program dan menampilkan pesan error
            die("Koneksi gagal: " . $this->database->connect_error);
        }
    }

    // Method getter: Berfungsi untuk memberikan akses objek koneksi ke file/class lain yang membutuhkannya
    public function getConnection() {
        return $this->database;
    }
}
?>