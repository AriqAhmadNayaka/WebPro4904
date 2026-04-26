<?php
class Auth {
    // Simpan koneksi database biar bisa dipakai di method login.
    private $db;

    public function __construct($db_connection) {
        $this->db = $db_connection;
    }

    public function login($username, $password) {
        // Input dirapihin dulu biar lebih aman pas masuk ke query.
        $username = mysqli_real_escape_string($this->db, $username);
        $password = mysqli_real_escape_string($this->db, $password);

        // Cek apakah ada user yang cocok sama username dan password ini.
        $sql = "SELECT * FROM user WHERE username='$username' AND password='$password'";
        $result = mysqli_query($this->db, $sql);

        if (mysqli_num_rows($result) == 1) {
            $user_data = mysqli_fetch_assoc($result);
            session_start();
            $_SESSION['username'] = $user_data['username'];
            return true;
        } else {
            return false;
        }
    }
}
?>
