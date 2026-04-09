<?php
class Database {
    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $db_name = "pekan-06"; 
    public $conn;

    public function __construct() {
        $this->conn = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        if (!$this->conn) {
            die("Koneksi gagal: " . mysqli_connect_error());
        }
    }

    public function login($username, $password) {
        $u = mysqli_real_escape_string($this->conn, $username);
        $p = mysqli_real_escape_string($this->conn, $password);
        $query = "SELECT * FROM users WHERE username = '$u' AND password = '$p'";
        $result = mysqli_query($this->conn, $query);
        return mysqli_fetch_assoc($result);
    }

    public function getAllMenus() {
        $query = "SELECT * FROM menus";
        return mysqli_query($this->conn, $query);
    }

    public function imageExists($imageName) {
        if (empty($imageName)) return false;
        return file_exists("img/" . $imageName);
    }

    public function getImageWebPath($imageName) {
        return "img/" . $imageName;
    }
}