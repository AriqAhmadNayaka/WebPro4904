<?php
require_once 'Database.php';

class User {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function register($email, $password) {
        $conn = $this->db->getConnection();
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $conn->prepare("INSERT INTO user (email, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $email, $hashed);
        
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function login($email, $password) {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("SELECT id, email, password FROM user WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($user = $result->fetch_assoc()) {
            if (password_verify($password, $user['password'])) {
                $stmt->close();
                return $user;
            }
        }
        $stmt->close();
        return false;
    }
}
?>