<?php
// parent class Database sebelum mendefinisikan child class
require_once __DIR__ . '/Database.php';

// Class UserModel kelas turunan dari Database
// Sesuai modul 6.4.8 Inheritance: UserModel mewarisi $conn dari Database
// sehingga tidak perlu membuat koneksi ulang di sini
class UserModel extends Database {

    // Membersihkan input dari spasi berlebih, backslash, dan karakter berbahaya (XSS)
    // Sesuai modul 6.4.4: method adalah fungsi yang ada di dalam class
    public function testInput($data) {
        return htmlspecialchars(stripslashes(trim($data)));
    }

    // Method untuk mengecek apakah email sudah terdaftar di database
    // Mengembalikan true jika email sudah ada, false jika belum
    public function cekEmailTerdaftar($email) {
        $stmt = $this->conn->prepare("SELECT id FROM users WHERE email = ?");
        // "s" = parameter bertipe string sesuai tabel types modul 4.4.2
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        $ada = $stmt->num_rows > 0;
        $stmt->close();
        return $ada;
    }

    // Method untuk menyimpan user baru ke database (proses REGISTER)
    // Mengembalikan id user baru jika berhasil, null jika gagal
    public function register($name, $email, $password, $role) {
        // password_hash() agar password tidak tersimpan sebagai teks biasa
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // INSERT INTO users sesuai materi CRUD modul 4.4.2
        $sql  = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare(query: $sql);
        // "ssss" = 4 parameter bertipe string
        $stmt->bind_param("ssss", $name, $email, $hashedPassword, $role);

        if ($stmt->execute()) {
            $newId = $this->conn->insert_id;
            $stmt->close();
            return $newId;
        }
        $stmt->close();
        return null;
    }

    // Method untuk mencari user berdasarkan email dan role (proses LOGIN)
    // Mengembalikan array data user jika ditemukan, null jika tidak ada
    public function cariUser($email, $role) {
        $sql  = "SELECT id, name, email, password, role FROM users WHERE email = ? AND role = ?";
        $stmt = $this->conn->prepare(query: $sql);
        // "ss" = 2 parameter bertipe string
        $stmt->bind_param("ss", $email, $role);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($userId, $userName, $userEmail, $userPassword, $userRole);
        $stmt->fetch();

        if ($stmt->num_rows === 0) {
            $stmt->close();
            return null;
        }

        $user = [
            'id'       => $userId,
            'name'     => $userName,
            'email'    => $userEmail,
            'password' => $userPassword,
            'role'     => $userRole,
        ];
        $stmt->close();
        return $user;
    }

    // Method untuk memverifikasi password input dengan hash di database
    // Mengembalikan true jika cocok, false jika tidak
    public function verifikasiPassword($passwordInput, $passwordHash) {
        return password_verify($passwordInput, $passwordHash);
    }

    // Method untuk menyimpan cookie "Ingat Saya" sesuai modul 5.4.6
    // setcookie() menyimpan email dan role di browser selama 30 hari
    public function simpanCookie($email, $role) {
        setcookie("inklu_email", $email, time() + (86400 * 30), "/");
        setcookie("inklu_role",  $role,  time() + (86400 * 30), "/");
    }

    // Method untuk menghapus cookie sesuai modul 5.4.8
    // Cookie dihapus dengan set expire ke masa lalu (time() - 3600)
    public function hapusCookie() {
        setcookie("inklu_email", "", time() - 3600, "/");
        setcookie("inklu_role",  "", time() - 3600, "/");
    }

    // Method untuk membaca cookie email dan role dari browser sesuai modul 5.4.7
    // Mengembalikan array ['email' => ..., 'role' => ...]
    public function bacaCookie() {
        return [
            'email' => isset($_COOKIE['inklu_email']) ? $_COOKIE['inklu_email'] : '',
            'role' => isset($_COOKIE['inklu_role'])  ? $_COOKIE['inklu_role']  : '',
        ];
    }
}