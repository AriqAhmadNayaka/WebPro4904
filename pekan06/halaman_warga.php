<?php
class Warga { // Kelas untuk menangani operasi CRUD pada data warga
    private $db; // Properti untuk menyimpan koneksi database

    public function __construct($db_connection) { // Konstruktor untuk menginisialisasi koneksi database
        $this->db = $db_connection;
    }

    // Mengambil semua data
    public function getAll() { // Metode untuk mengambil semua data warga dari database
        return mysqli_query($this->db, "SELECT * FROM warga"); // Menjalankan query untuk mengambil semua data dari tabel warga
    }

    // Menambah data warga
    public function create($nama, $alamat, $nohp, $fileName) { // Metode untuk menambah data warga ke database
        $nama = mysqli_real_escape_string($this->db, $nama); // Mengamankan nama dari SQL Injection
        $alamat = mysqli_real_escape_string($this->db, $alamat); // Mengamankan alamat dari SQL Injection
        $nohp = mysqli_real_escape_string($this->db, $nohp);// Mengamankan nomor HP dari SQL Injection
        
        $sql = "INSERT INTO warga (nama, alamat, nohp, file) VALUES ('$nama', '$alamat', '$nohp', '$fileName')"; // Query untuk memasukkan data baru ke dalam tabel warga
        return mysqli_query($this->db, $sql); // Menjalankan query dan mengembalikan hasilnya
    }

    // Menghapus data warga
    public function delete($id) { // Metode untuk menghapus data warga dari database berdasarkan ID
        $id = mysqli_real_escape_string($this->db, $id); // Mengamankan ID dari SQL Injection
        return mysqli_query($this->db, "DELETE FROM warga WHERE id='$id'"); // Menjalankan query untuk menghapus data dari tabel warga berdasarkan ID
    }

    // Update data warga
    public function update($id, $nama, $alamat, $nohp) { // Metode untuk memperbarui data warga di database berdasarkan ID
        $id = mysqli_real_escape_string($this->db, $id); // Mengamankan ID dari SQL Injection
        $nama = mysqli_real_escape_string($this->db, $nama); // Mengamankan nama dari SQL Injection
        $alamat = mysqli_real_escape_string($this->db, $alamat); // Mengamankan alamat dari SQL Injection
        $nohp = mysqli_real_escape_string($this->db, $nohp); // Mengamankan nomor HP dari SQL Injection

        $sql = "UPDATE warga SET nama='$nama', alamat='$alamat', nohp='$nohp' WHERE id='$id'"; // Query untuk memperbarui data di dalam tabel warga berdasarkan ID
        return mysqli_query($this->db, $sql);
    }
}
?>