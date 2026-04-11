<?php
class User {
    // Properti untuk menyimpan koneksi database agar bisa dipakai di semua method
    private $conn;

    // Constructor: Fungsi yang otomatis jalan saat "new User()" dipanggil
    public function __construct() {
        // Membuat koneksi ke database cybervault
        $this->conn = new mysqli("localhost", "root", "", "cybervault");
        
        // Cek jika koneksi gagal, langsung hentikan program
        if ($this->conn->connect_error) {
            die("Koneksi gagal: " . $this->conn->connect_error);
        }
    }

    // Method untuk mengambil semua data user dari tabel
    public function getAllUsers() {
        $sql = "SELECT * FROM datauser ORDER BY id DESC";
        return $this->conn->query($sql); // Mengembalikan hasil query
    }

    // Method untuk memproses pendaftaran user baru
    public function tambahUser($data, $file) {
        // Membersihkan inputan teks dari karakter aneh (keamanan SQL Injection)
        $nama     = $this->conn->real_escape_string($data['nama']);
        $email    = $this->conn->real_escape_string($data['email']);
        
        // Mengenkripsi password agar tidak terlihat teks aslinya di database
        $password = password_hash($data['password'], PASSWORD_DEFAULT);
        
        // Memanggil method internal uploadFoto untuk mengurus file
        $fotoFinal = $this->uploadFoto($file);

        // Perintah SQL untuk memasukkan data
        $sql = "INSERT INTO datauser (nama, email, password, foto) 
                VALUES ('$nama', '$email', '$password', '$fotoFinal')";
        
        return $this->conn->query($sql);
    }

    // Method internal (private) khusus untuk mengurus validasi & upload foto
    private function uploadFoto($file) {
        // Jika user tidak upload foto (error 4), gunakan foto default
        if ($file['error'] === 4) return "default.png";

        $namaFile   = $file['name'];
        $tmpName    = $file['tmp_name'];
        $ekstensi   = strtolower(pathinfo($namaFile, PATHINFO_EXTENSION)); // Ambil format file (jpg/png)
        $valid      = ['jpg', 'jpeg', 'png']; // Daftar format yang diizinkan

        // Validasi: Jika format tidak ada di daftar $valid
        if (!in_array($ekstensi, $valid)) {
            echo "<script>alert('Format tidak didukung!');</script>";
            return "default.png";
        }

        // Buat nama file unik (biar tidak bentrok) dan pindahkan ke folder img
        $namaBaru = uniqid() . '.' . $ekstensi;
        move_uploaded_file($tmpName, 'img/' . $namaBaru);
        
        return $namaBaru; // Kirim balik nama file barunya
    }
}
