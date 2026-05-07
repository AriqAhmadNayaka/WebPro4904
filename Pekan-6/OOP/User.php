<?php
require_once "Database.php";

class User {
    private $db;

    public function __construct() {
        $this->db = (new Database())->conn;
    }

    public function register($name, $email, $password) {
        $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("sss", $name, $email, $password);
        return $stmt->execute();
    }

    public function login($email, $password) {
        $sql = "SELECT * FROM users WHERE email=? AND password=?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ss", $email, $password);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows == 1 ? $result->fetch_assoc() : null;
    }
}
?>
