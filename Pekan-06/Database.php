<?php
class Database { // class untuk mengelola koneksi database MySQL
    private $host = "localhost"; // alamat server database biasanya localhost
    private $user = "root"; // username default MySQL pada XAMPP
    private $pass = ""; // password MySQL default kosong XAMPP
    private $db   = "navibiz"; // nama database yang akan digunakan

    public $conn; // properti untuk menyimpan hasil koneksi database

    public function __construct(){ // constructor otomatis saat objek dibuat
        $this->conn = mysqli_connect( // fungsi untuk membuat koneksi database
            $this->host, // parameter host server database
            $this->user, // parameter username database
            $this->pass, // parameter password database
            $this->db // parameter nama database tujuan
        );

        if(!$this->conn){ // cek apakah koneksi database berhasil dibuat
            die("Koneksi gagal: " . mysqli_connect_error()); // hentikan program jika koneksi gagal
        }
    }
}
?>