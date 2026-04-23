<?php
session_start();

class User {
    private $db;
    private $username;

    public function __construct() {
        $this->db = Database::getInstance()->getPdo();
    }

    public function login($username, $password) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $this->username = $user['username'];
            return true;
        }
        return false;
    }

    public static function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    public static function getUsername() {
        return $_SESSION['username'] ?? null;
    }

    public static function logout() {
        session_destroy();
        session_unset();
    }

    public function getCurrentUser() {
        if (!self::isLoggedIn()) return null;
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        return $stmt->fetch();
    }

    /**
     * Register new user - OOP Style
     */
    public static function register($username, $password) {
        // Validation
        $username = trim($username);
        if (strlen($username) < 3 || strlen($username) > 50) {
            return ['success' => false, 'error' => 'Username 3-50 karakter!'];
        }
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
            return ['success' => false, 'error' => 'Username hanya huruf, angka, underscore!'];
        }
        if (strlen($password) < 6) {
            return ['success' => false, 'error' => 'Password minimal 6 karakter!'];
        }

        $db = Database::getInstance()->getPdo();
        
        // Check duplicate
        $stmt = $db->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->fetch()) {
            return ['success' => false, 'error' => 'Username sudah terdaftar!'];
        }

        // Create user
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $db->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->execute([$username, $hashed]);
        
        return ['success' => true, 'message' => 'Pendaftaran berhasil! Silakan login.'];
    }
}
?>


