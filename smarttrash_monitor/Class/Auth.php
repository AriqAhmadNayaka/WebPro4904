<?php

require_once __DIR__ . "/../config/database.php";

class Auth
{
    private $conn;

    // ===============================
    // Constructor
    // ===============================
    public function __construct()
    {
        $db = new database();
        $this->conn = $db->connect();
    }

    // ===============================
    // Cek Login
    // ===============================
    public function isLoggedIn()
    {
        return isset($_SESSION["login"]) && $_SESSION["login"] === true;
    }

    // ===============================
    // Login
    // ===============================
    public function login($email, $password)
    {
        $email = mysqli_real_escape_string($this->conn, $email);

        // --- ADMIN HARDCODE ---
        $adminEmail = "admin@cybervault.com";
        $adminPassword = "admin123";

        if ($email === $adminEmail && $password === $adminPassword) {

            $_SESSION["login"] = true;
            $_SESSION["role"] = "admin";
            $_SESSION["nama"] = "Administrator Utama";
            $_SESSION["email"] = $adminEmail;

            return true;
        }

        // --- LOGIN DATABASE ---
        $query = "SELECT * FROM datauser WHERE email='$email'";
        $result = mysqli_query($this->conn, $query);

        if ($result && mysqli_num_rows($result) == 1) {

            $row = mysqli_fetch_assoc($result);

            if (password_verify($password, $row["password"])) {

                $_SESSION["login"] = true;
                $_SESSION["id_user"] = $row["id"];
                $_SESSION["nama"] = $row["name"];
                $_SESSION["email"] = $row["email"];
                $_SESSION["role"] = $row["role"] ?? "user";

                return true;
            }
        }

        return false;
    }

    // ===============================
    // Register
    // ===============================
    public function register($nama, $email, $password)
    {
        $nama = mysqli_real_escape_string($this->conn, $nama);
        $email = mysqli_real_escape_string($this->conn, $email);

        // cek email
        $cek = mysqli_query(
            $this->conn,
            "SELECT id FROM datauser WHERE email='$email'"
        );

        if (mysqli_num_rows($cek) > 0) {
            return false;
        }

        // hash password
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO datauser (name, email, password, role)
                VALUES ('$nama','$email','$passwordHash','user')";

        return mysqli_query($this->conn, $sql);
    }

    // ===============================
    // Logout
    // ===============================
    public function logout()
    {
        session_unset();
        session_destroy();
    }

    // ===============================
    // Ambil Data User
    // ===============================
    public function getUser()
    {
        if (!$this->isLoggedIn()) {
            return null;
        }

        return [
            "id" => $_SESSION["id_user"] ?? null,
            "nama" => $_SESSION["nama"] ?? null,
            "email" => $_SESSION["email"] ?? null,
            "role" => $_SESSION["role"] ?? null
        ];
    }
}