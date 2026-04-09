<?php
// Class Database Parent Class (Kelas Induk)
// Sesuai modul 6.4.2: class adalah template/cetakan untuk membuat objek
// Sesuai modul 6.4.6: konstruktor dijalankan otomatis saat objek dibuat,
// destruktor dijalankan otomatis saat objek tidak dipakai

class Database {

    // Sesuai modul 6.4.7 Enkapsulasi:
    // protected bisa diakses oleh class ini dan class turunannya (child class)
    // private hanya bisa diakses di dalam class ini saja
    protected $conn;
    private $host;
    private $username;
    private $password;
    private $database;

    // Konstruktor (__construct): dijalankan otomatis saat objek dibuat dengan "new Database()"
    // Sesuai modul 6.4.6: konstruktor digunakan untuk menginisialisasi nilai awal
    public function __construct() {
        $this->host     = "localhost";
        $this->username = "root";
        $this->password = "";
        $this->database = "inkluskill_db";

        // Membuat koneksi ke database, sama seperti di koneksi.php
        // tapi sekarang dibungkus dalam class agar bisa dipakai ulang (prinsip DRY modul 6.4.1)
        $this->conn = new mysqli(
            hostname: $this->host,
            username: $this->username,
            password: $this->password,
            database: $this->database
        );

        // Hentikan program jika koneksi gagal
        if ($this->conn->connect_error) {
            die("Koneksi gagal: " . $this->conn->connect_error);
        }

        // Set charset utf8 agar karakter Indonesia tersimpan dengan benar
        $this->conn->set_charset("utf8");
    }

    // Destruktor (__destruct): dijalankan otomatis saat objek selesai dipakai
    // Sesuai modul 6.4.6: destruktor berguna untuk membersihkan sumber daya, seperti menutup koneksi database
    public function __destruct() {
        if ($this->conn) {
            $this->conn->close();
        }
    }
}