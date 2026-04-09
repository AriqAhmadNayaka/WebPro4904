<?php
class Auth { // Kelas untuk menangani autentikasi pengguna
    private $db; // Properti untuk menyimpan koneksi database

    public function __construct($db_connection) { // Konstruktor untuk menginisialisasi koneksi database
        $this->db = $db_connection;
    }

    public function login($username, $password) { // Metode untuk melakukan login pengguna
        // Mengamankan input dari SQL Injection
        $username = mysqli_real_escape_string($this->db, $username); // Mengamankan username
        $password = mysqli_real_escape_string($this->db, $password); // Mengamankan password (sebaiknya gunakan hashing, ini hanya contoh sederhana)

        $sql = "SELECT * FROM user WHERE username='$username' AND password='$password'"; // Query untuk memeriksa kecocokan username dan password
        $result = mysqli_query($this->db, $sql); // Menjalankan query dan menyimpan hasilnya

        if (mysqli_num_rows($result) == 1) { // Jika ditemukan satu baris yang cocok, berarti login berhasil
            $user_data = mysqli_fetch_assoc($result); // Mengambil data pengguna dari hasil query
            session_start(); // Memulai sesi untuk menyimpan informasi pengguna yang telah login
            $_SESSION['username'] = $user_data['username']; // Menyimpan username dalam sesi
            return true; // Mengembalikan true jika login berhasil
        } else {
            return false;// Mengembalikan false jika login gagal
        }
    }
}
?>