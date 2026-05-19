<?php
require_once 'Database.php';


class User {
   
    private mysqli $conn;

    
    public function __construct(mysqli $conn) {
        $this->conn = $conn;
    }
    
    public function login(string $username, string $password): bool {
        $result = mysqli_query(
            $this->conn,
            "SELECT * FROM users WHERE username = '$username'"
        );
        $user = mysqli_fetch_assoc($result);

       
        if ($user && password_verify($password, $user['password'])) {
          
            $_SESSION['login'] = true;
            return true;
        }

        return false;
    }

    
    public function register(string $username, string $password, string $confirmPassword): string {
       
        $username = mysqli_real_escape_string($this->conn, $username);

      
        $check = mysqli_query(
            $this->conn,
            "SELECT username FROM users WHERE username = '$username'"
        );

        if (mysqli_num_rows($check) > 0) {
            return "Username sudah terdaftar!";
        }

      
        if ($password !== $confirmPassword) {
            return "Konfirmasi password tidak cocok!";
        }

       
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        
        $query = "INSERT INTO users (username, password) VALUES ('$username', '$hashedPassword')";
        if (mysqli_query($this->conn, $query)) {
            return "sukses";
        }

        return "Gagal mendaftarkan user.";
    }
}
?>
