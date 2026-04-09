<?php
require_once 'Database.php';

/**
 * Kelas User
 * 
 * Menangani semua operasi yang berhubungan dengan pengguna:
 * - Login (verifikasi kredensial)
 * - Register (pendaftaran akun baru)
 * 
 * Kelas ini menerima objek koneksi database melalui constructor
 * (Dependency Injection), sehingga mudah diuji dan tidak tergantung
 * langsung pada implementasi Database tertentu.
 */
class User {
    // Menyimpan koneksi database
    private mysqli $conn;

    /**
     * Constructor menerima koneksi dari luar (Dependency Injection).
     * Ini membuat kelas User tidak perlu tahu cara membuat koneksi —
     * cukup terima dan pakai.
     */
    public function __construct(mysqli $conn) {
        $this->conn = $conn;
    }

    /**
     * Melakukan proses login.
     * 
     * @param string $username — Username yang diinput pengguna
     * @param string $password — Password yang diinput pengguna
     * @return bool — true jika login berhasil, false jika gagal
     */
    public function login(string $username, string $password): bool {
        // Ambil data user berdasarkan username
        $result = mysqli_query(
            $this->conn,
            "SELECT * FROM users WHERE username = '$username'"
        );
        $user = mysqli_fetch_assoc($result);

        // Verifikasi password yang diinput dengan hash di database
        if ($user && password_verify($password, $user['password'])) {
            // Tandai sesi bahwa user sudah login
            $_SESSION['login'] = true;
            return true;
        }

        return false;
    }

    /**
     * Melakukan proses registrasi akun baru.
     * 
     * @param string $username         — Username yang dipilih
     * @param string $password         — Password baru
     * @param string $confirmPassword  — Konfirmasi password
     * @return string — Pesan hasil: 'sukses' atau keterangan error
     */
    public function register(string $username, string $password, string $confirmPassword): string {
        // Escape input untuk mencegah SQL Injection
        $username = mysqli_real_escape_string($this->conn, $username);

        // Cek apakah username sudah terdaftar
        $check = mysqli_query(
            $this->conn,
            "SELECT username FROM users WHERE username = '$username'"
        );

        if (mysqli_num_rows($check) > 0) {
            return "Username sudah terdaftar!";
        }

        // Cek kecocokan password dan konfirmasinya
        if ($password !== $confirmPassword) {
            return "Konfirmasi password tidak cocok!";
        }

        // Hash password sebelum disimpan ke database (keamanan data)
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Simpan user baru ke database
        $query = "INSERT INTO users (username, password) VALUES ('$username', '$hashedPassword')";
        if (mysqli_query($this->conn, $query)) {
            return "sukses";
        }

        return "Gagal mendaftarkan user.";
    }
}
?>
