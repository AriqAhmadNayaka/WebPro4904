<?php
class User {
    private $conn;
    public $username;
    public $password;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function register() {
        $query = "INSERT INTO users (username, password) VALUES (?, ?)";
        $stmt = $this->conn->prepare($query);

        $hash = password_hash($this->password, PASSWORD_DEFAULT);
        $stmt->bind_param("ss", $this->username, $hash);

        return $stmt->execute();
    }

    public function login() {
        $query = "SELECT * FROM users WHERE username=?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $this->username);
        $stmt->execute();

        $result = $stmt->get_result()->fetch_assoc();

        if ($result && password_verify($this->password, $result['password'])) {
            return true;
        }
        return false;
    }
}
?>