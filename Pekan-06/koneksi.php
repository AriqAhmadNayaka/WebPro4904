<?php
class Koneksi { //Membuat class bernama Koneksi untuk mengatur koneksi ke database
    public $conn; //Mendeklarasikan variabel (property) $conn yang akan menyimpan koneksi database

    public function __construct(){ //Method constructor, otomatis dijalankan saat objek dibuat
        $this->conn = new mysqli("localhost", "root", "", "db_pasien"); //Membuat koneksi ke database menggunakan mysqli

        if ($this->conn->connect_error) { //Mengecek apakah terjadi error saat koneksi database
            die("Koneksi gagal: " . $this->conn->connect_error); //Menampilkan pesan error koneksi
        }
    }
}
?>