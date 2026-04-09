<?php
include 'Database.php'; // menghubungkan dengan class Database
class User {
    private $conn; // variabel untuk menyimpan koneksi database
    // deklarasi properti user
    public $id;
    public $nama;
    public $email;
    public $password;

    // constructor: dijalankan saat objek dibuat
    public function __construct($id = null, $nama = null, $email = null, $password = null) {
        $db = new Database();
        // mengambil koneksi dari class Database
        $this->conn = $db->getConnection();
        // inisialisasi data user
        $this->id = $id;
        $this->nama = $nama;
        $this->email = $email;
        $this->password = $password;
    }

    // method register: menyimpan data user ke database
    public function register() {
        $query = "INSERT INTO user (nama, email, password) 
                  VALUES ('$this->nama', '$this->email', '$this->password')";
        return $this->conn->query($query);
    }
    // method login: mengecek apakah user ada di database
    public function login() {
        // query untuk mencari user berdasarkan email dan password
        $query = "SELECT * FROM user 
                  WHERE email='$this->email' AND password='$this->password'";
        $result = $this->conn->query($query);
        // jika data ditemukan (login berhasil)
        if ($result->num_rows > 0) {
            // ambil data user dalam bentuk array
            return $result->fetch_assoc();
        } else {
            // jika tidak ditemukan (login gagal)
            return false;
        }
    }
}
?>