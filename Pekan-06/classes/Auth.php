<?php
// Class Auth - menangani registrasi, login, logout, cek session
class Auth {
    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // simpan akun hasil registrasi ke session
    public function register($data) {
        $_SESSION["akun"] = [
            "role"     => trim($data["role"]),
            "nama"     => trim($data["nama"]),
            "email"    => trim($data["email"]),
            "password" => trim($data["password"]),
        ];
    }

    // cek email & password, return true kalau cocok
    public function login($email, $password) {
        $akun = $_SESSION["akun"] ?? [];
        if (($akun["email"] ?? "") === $email && ($akun["password"] ?? "") === $password) {
            $_SESSION["user"] = $akun["nama"];
            $_SESSION["role"] = $akun["role"];
            return true;
        }
        return false;
    }

    public function check() {
        return isset($_SESSION["user"]);
    }

    public function user() {
        return $_SESSION["user"] ?? null;
    }

    public function akun() {
        return $_SESSION["akun"] ?? [];
    }

    public function logout() {
        session_destroy();
    }
}
?>
