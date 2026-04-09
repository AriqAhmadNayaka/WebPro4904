<?php
class User { // class untuk mengelola data user database
    private $conn; // properti untuk menyimpan koneksi database aktif

    public function __construct($db){ // constructor menerima koneksi dari luar class
        $this->conn = $db; // simpan koneksi database ke properti class
    }

    // cek username sudah ada atau belum
    public function cekUsername($username){ // fungsi untuk cek username di database
        $result = mysqli_query($this->conn, // jalankan query ke database MySQL
            "SELECT * FROM user WHERE username='$username'" // query cek username sudah ada
        );
        return mysqli_num_rows($result) > 0; // true jika username sudah ditemukan
    }

    // insert data user
    public function register($data){ // fungsi untuk menyimpan data user baru
        return mysqli_query($this->conn, // jalankan query insert ke database
        "INSERT INTO user (name, email, username, password, telepon, alamat) 
         VALUES (
            '{$data['name']}', 
            '{$data['email']}', 
            '{$data['username']}', 
            '{$data['password']}', 
            '{$data['telepon']}', 
            '{$data['alamat']}' 
         )");
    }
}
?>