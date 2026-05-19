<?php

/**
 * Kelas Database
 * 
 * Bertanggung jawab mengelola koneksi ke database MySQL.
 * Menggunakan pola Singleton supaya hanya ada SATU objek koneksi
 * yang dibuat selama aplikasi berjalan — tidak boros resource.
 */
class Database
{
    // Menyimpan nama host, user, password, dan nama database
    private string $host     = "localhost";
    private string $user     = "root";
    private string $password = "";
    private string $dbname   = "tugas_besar";

    // Menyimpan instance tunggal (Singleton pattern)
    private static ?Database $instance = null;

    // Menyimpan objek koneksi MySQLi
    private mysqli $conn;

    /**
     * Constructor bersifat private agar kelas ini tidak bisa di-new
     * dari luar. Koneksi hanya dibuat lewat getInstance().
     */
    private function __construct()
    {
        $this->conn = mysqli_connect(
            $this->host,
            $this->user,
            $this->password,
            $this->dbname
        );

        // Jika koneksi gagal, hentikan program dan tampilkan pesan error
        if (!$this->conn) {
            die("Koneksi gagal: " . mysqli_connect_error());
        }
    }

    /**
     * Mengembalikan satu-satunya instance Database.
     * Jika belum ada instance, buat dulu; jika sudah ada, pakai yang lama.
     */
    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    /**
     * Mengembalikan objek koneksi MySQLi untuk dipakai kelas lain.
     */
    public function getConnection(): mysqli
    {
        return $this->conn;
    }
}
