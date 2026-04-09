<?php
// File User.php berfungsi untuk mengelola proses registrasi, login, dan logout pengguna dengan menggunakan database MySQL untuk menyimpan data pengguna.
require_once "database.php";

// Class User berisi method-method untuk melakukan operasi terkait pengguna seperti register, login, checkSession, dan logout
class User {
    // Properti private untuk menyimpan objek koneksi database yang hanya bisa diakses dari dalam class ini (Enkapsulasi)
    private $db;

    // Konstruktor untuk membuat koneksi database saat objek User dibuat dan menyimpan koneksi
    public function __construct() {
        $database = new database();
        $this->db = $database->getConnection();
    }

    // Method untuk memeriksa apakah pengguna sudah login dengan mengecek keberadaan session 'user_id' yang diset saat login berhasil
    public function checkSession() {
        return isset($_SESSION['user_id']);
    }

    // Method untuk melakukan registrasi pengguna baru dengan memasukkan data ke dalam tabel users di database 
    public function register($nama, $email, $username, $password, $telepon, $alamat) {
        // MENGHAPUS ENKRIPSI PASSWORD: Simpan password apa adanya tanpa enkripsi (tidak aman untuk produksi)
        // $password = password_hash($password, PASSWORD_DEFAULT); // HAPUS ENKR   
        $stmt = $this->db->prepare("INSERT INTO users (nama, email, username, password, telepon, alamat) VALUES (?, ?, ?, ?, ?, ?)");
        // Bind parameter dengan tipe data yang sesuai (s untuk string)
        $stmt->bind_param("ssssss", $nama, $email, $username, $password, $telepon, $alamat);
        
        // Eksekusi query dan simpan hasilnya untuk menentukan apakah operasi berhasil atau tidak
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    // Method untuk melakukan login dengan memeriksa kecocokan username dan password dengan data di database
    public function login($username, $password, $remember) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username=?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        // HAPUS VERIFIKASI ENKRIPSI: Cek kecocokan password secara manual tanpa menggunakan password_verify karena password disimpan tanpa enkripsi
        if ($result->num_rows > 0) {
            $data = $result->fetch_assoc();
            
            // Cek kecocokan password secara manual
            if ($password == $data['password']) {
                $_SESSION['user_id'] = $data['id'];
                $_SESSION['nama'] = $data['nama'];

                // Jika opsi "Remember Me" dicentang, set cookie untuk menyimpan informasi login selama 30 hari
                if ($remember) {
                    setcookie("user_id", $data['id'], time() + (86400 * 30), "/");
                    setcookie("nama", $data['nama'], time() + (86400 * 30), "/");
                }
                // Jika login berhasil, kembalikan true untuk menandakan keberhasilan login
                return true;
            }
        }
        // Jika login gagal (username tidak ditemukan atau password tidak cocok), kembalikan false untuk menandakan kegagalan login
        return false;
    }

    // Method untuk melakukan logout dengan menghapus session dan cookie yang terkait dengan login pengguna
    public function logout() {
        session_unset();
        session_destroy();

        setcookie("user_id", "", time() - 3600, "/");
        setcookie("nama", "", time() - 3600, "/");
    }
}
?>