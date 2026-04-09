<?php
class Auth {
    private $conn; // menyimpan koneksi database

    public function __construct($db){
        $this->conn = $db; // inisialisasi koneksi database ke class
        
        // inisialisasi koneksi database ke class
        if(session_status() == PHP_SESSION_NONE){
        session_start(); // mulai session jika belum aktif
        }
    }

    // LOGIN
    public function login($username, $password, $remember = false){
        // mengaamankan input dari SQL injection
        $username = mysqli_real_escape_string($this->conn, $username);
        $password = mysqli_real_escape_string($this->conn, $password);
        // query akan cek user di database
        $query = mysqli_query($this->conn,
            "SELECT * FROM user WHERE username='$username' AND password='$password'"
        );

        // jika data ditemukan (login berhasil)
        if(mysqli_num_rows($query) > 0){
            $_SESSION['username'] = $username; //simpen username ke session

            if($remember){ //jika user centang remember me
                setcookie("username", $username, time() + (86400 * 7), "/"); //simpan username ke cookie selama 7 hari
            }

            return true; //kembalikan true jika berhasil
        }

        return false; //kembalikan false jika gagal
    }

    // CEK LOGIN
    public function isLogin(){
        //cek jika username tersedia
        return isset($_SESSION['username']);
    }

    // LOGOUT
    public function logout(){
        session_destroy(); //hapus semua data session
        //hapus cookie dengan waktu kadaluarsa
        setcookie("username", "", time() - 3600, "/");
        header("Location: login2.php"); //redirect ke halaman login
        exit;
    }

    // AMBIL USER LOGIN
    public function user(){
        //ambil username dari session jika ada
        return $_SESSION['username'] ?? null;
    }
}
?>