<?php
// 1. Class Utama: Database
class Database {
    private $host = "localhost";
    private $user = "root";
    private $pass = "";
    private $db   = "ecotaste";
    protected $conn;

    public function __construct() {
        $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->db);
        if ($this->conn->connect_error) {
            die("Koneksi Database Gagal: " . $this->conn->connect_error);
        }
    }
}

// 2. Class Auth (Turunan dari Database) untuk Login & Register
class Auth extends Database {
    public function register($username, $email, $password) {
        // Cek apakah username/email sudah ada
        $cek = $this->conn->query("SELECT * FROM users WHERE username='$username' OR email='$email'");
        if ($cek->num_rows > 0) {
            return "Username atau Email sudah terdaftar!";
        }
        
        $hash_pass = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hash_pass')";
        if ($this->conn->query($sql) === TRUE) {
            return "success";
        }
        return "Error: " . $this->conn->error;
    }

    public function login($username, $password) {
        $result = $this->conn->query("SELECT * FROM users WHERE username='$username' OR email='$username'");
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                $_SESSION['isLoggedIn'] = true;
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['username'] = $row['username'];
                return true;
            }
        }
        return false;
    }
}

// 3. Class Donasi (Turunan dari Database) untuk CRUD
class Donasi extends Database {
    // CREATE + UPLOAD
    public function tambahData($user_id, $data, $file) {
        $nama = $data['nama_makanan'];
        $jumlah = $data['jumlah'];
        $tgl = $data['tanggal_kedaluwarsa'];
        
        $gambar = $file['gambar']['name'];
        $tmp = $file['gambar']['tmp_name'];
        $fotobaru = date('d-m-Y_H-i-s').'_'.$gambar;
        $path = "uploads/".$fotobaru;

        if(move_uploaded_file($tmp, $path)) {
            $stmt = $this->conn->prepare("INSERT INTO donasi_makanan (user_id, nama_makanan, jumlah, tanggal_kedaluwarsa, gambar) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("issss", $user_id, $nama, $jumlah, $tgl, $fotobaru);
            return $stmt->execute();
        }
        return false;
    }

    // READ
    public function tampilkanSemua($user_id) {
        $result = $this->conn->query("SELECT * FROM donasi_makanan WHERE user_id='$user_id' ORDER BY id DESC");
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }

    // Ambil Data Tunggal (Untuk Edit)
    public function getDonasiById($id, $user_id) {
        $result = $this->conn->query("SELECT * FROM donasi_makanan WHERE id='$id' AND user_id='$user_id'");
        return $result->fetch_assoc();
    }

    // UPDATE + UPLOAD
    public function editData($id, $user_id, $data, $file) {
        $nama = $data['nama_makanan'];
        $jumlah = $data['jumlah'];
        $tgl = $data['tanggal_kedaluwarsa'];
        $status = $data['status'];

        if ($file['gambar']['name'] != "") {
            $gambar = $file['gambar']['name'];
            $tmp = $file['gambar']['tmp_name'];
            $fotobaru = date('d-m-Y_H-i-s').'_'.$gambar;
            $path = "uploads/".$fotobaru;

            if(move_uploaded_file($tmp, $path)) {
                $lama = $this->getDonasiById($id, $user_id);
                if(is_file("uploads/".$lama['gambar'])) unlink("uploads/".$lama['gambar']); // Hapus foto lama
                
                $stmt = $this->conn->prepare("UPDATE donasi_makanan SET nama_makanan=?, jumlah=?, tanggal_kedaluwarsa=?, status=?, gambar=? WHERE id=? AND user_id=?");
                $stmt->bind_param("sssssii", $nama, $jumlah, $tgl, $status, $fotobaru, $id, $user_id);
                return $stmt->execute();
            }
        } else {
            $stmt = $this->conn->prepare("UPDATE donasi_makanan SET nama_makanan=?, jumlah=?, tanggal_kedaluwarsa=?, status=? WHERE id=? AND user_id=?");
            $stmt->bind_param("ssssii", $nama, $jumlah, $tgl, $status, $id, $user_id);
            return $stmt->execute();
        }
    }

    // DELETE
    public function hapusData($id, $user_id) {
        $lama = $this->getDonasiById($id, $user_id);
        if(is_file("uploads/".$lama['gambar'])) unlink("uploads/".$lama['gambar']); // Hapus foto

        return $this->conn->query("DELETE FROM donasi_makanan WHERE id='$id' AND user_id='$user_id'");
    }
}
?>