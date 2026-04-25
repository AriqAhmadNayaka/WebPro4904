<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Model: User_model
// Bertanggung jawab atas semua operasi data yang berhubungan
// dengan pengguna (tabel 'users'), termasuk:
//   - Sanitasi input
//   - Cek email terdaftar
//   - Registrasi user baru
//   - Pencarian user untuk login
//   - Verifikasi password
//   - Manajemen cookie "Ingat Saya"
//
// Di CI3, model mewarisi CI_Model (bukan extends Database seperti versi native).
// Akses database dilakukan via $this->db (CI3 Active Record / Query Builder)
// sebagai pengganti $this->conn (mysqli) di versi native.

class User_model extends CI_Model {

    // __construct() dipanggil saat model di-load.
    // Database sudah di-autoload via autoload.php, jadi tidak perlu
    // connect manual seperti versi native (new mysqli(...)).
    public function __construct() {
        parent::__construct();
        // Tidak perlu load database manual — sudah di-autoload di application/config/autoload.php
    }

    // METHOD: testInput($data)
    // Pembersihan input dari user sebelum diproses lebih lanjut.
    // Urutan: trim (hapus spasi tepi) - stripslashes (hapus backslash) - htmlspecialchars (encode HTML)
    // Tujuan: mencegah XSS (Cross-Site Scripting) dan karakter berbahaya.
    public function testInput($data) {
        return htmlspecialchars(stripslashes(trim($data)));
    }

    // METHOD: cekEmailTerdaftar($email)
    // Memeriksa apakah email sudah pernah terdaftar di tabel 'users'.
    // Return: true jika sudah ada, false jika belum.
    //
    // Dari: $stmt = $this->conn->prepare("SELECT id FROM users WHERE email = ?")
    // Ke  : CI3 Active Record get_where() yang otomatis menggunakan prepared statement
    public function cekEmailTerdaftar($email) {
        // get_where() mengembalikan result object dari query SELECT WHERE
        // num_rows() menghitung jumlah baris hasil query
        $query = $this->db->get_where('users', array('email' => $email));
        return $query->num_rows() > 0;
    }
    // METHOD: register($name, $email, $password, $role)
    // Mendaftarkan user baru ke tabel 'users'.
    // Password di-hash menggunakan password_hash() (bcrypt) sebelum disimpan.
    // Return: ID user baru yang berhasil diinsert.
    //
    // Dari: INSERT INTO users dengan mysqli prepared statement + bind_param
    // Ke  : $this->db->insert() — CI3 otomatis escape semua nilai (aman dari SQL Injection)
    public function register($name, $email, $password, $role) {
        // Hash password dengan algoritma PASSWORD_DEFAULT (saat ini bcrypt)
        // Jangan pernah simpan password dalam bentuk plain text
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Array asosiatif: key = nama kolom tabel, value = data yang akan diinsert
        $data = array(
            'name'     => $name,
            'email'    => $email,
            'password' => $hashedPassword,
            'role'     => $role,
        );

        // CI3 Active Record: insert() otomatis escape semua nilai dan generate query INSERT
        $this->db->insert('users', $data);

        // insert_id() mengembalikan ID auto-increment dari baris yang baru saja diinsert
        return $this->db->insert_id();
    }

    // METHOD: cariUser($email, $role)
    // Mencari data user di tabel 'users' berdasarkan email DAN role.
    // Digunakan saat proses login untuk memverifikasi identitas.
    // Return: array asosiatif data user, atau null jika tidak ditemukan.
    //
    // Dari: SELECT ... WHERE email=? AND role=? dengan bind_param("ss")
    // Ke  : $this->db->get_where() dengan array kondisi (AND otomatis)
    public function cariUser($email, $role) {
        // get_where() dengan array kondisi menghasilkan query: WHERE email='...' AND role='...'
        $query = $this->db->get_where('users', array('email' => $email, 'role' => $role));

        // Jika tidak ada user yang cocok, kembalikan null
        if ($query->num_rows() === 0) return null;

        // row_array() mengambil baris pertama hasil query sebagai array asosiatif
        // Misalnya: ['id' => 1, 'name' => 'Admin', 'email' => '...', 'role' => 'sekolah', 'password' => '$2y$...']
        return $query->row_array();
    }

    // METHOD: verifikasiPassword($passwordInput, $passwordHash)
    // Memverifikasi password yang dimasukkan user dengan hash yang tersimpan di DB.
    // Menggunakan password_verify() — fungsi bawaan PHP yang aman dan tidak rentan timing attack.
    // Return: true jika cocok, false jika tidak.
    public function verifikasiPassword($passwordInput, $passwordHash) {
        return password_verify($passwordInput, $passwordHash);
    }

    // METHOD: simpanCookie($email, $role)
    // Menyimpan email dan role pengguna ke cookie browser selama 30 hari.
    // Dipanggil saat user mencentang checkbox "Ingat Saya" saat login.
    //
    // Menggunakan setcookie() PHP native (bukan CI3 cookie helper)
    // karena CI3 tidak melarang ini, asal dipanggil sebelum ada output HTML.
    // time() + (86400 * 30) = waktu sekarang + 30 hari (dalam detik)
    // "/" = cookie berlaku untuk seluruh path di domain ini
    public function simpanCookie($email, $role) {
        setcookie("inklu_email", $email, time() + (86400 * 30), "/");
        setcookie("inklu_role",  $role,  time() + (86400 * 30), "/");
    }

    // METHOD: hapusCookie()
    // Menghapus cookie "ingat saya" dengan menyetel waktu kadaluarsa
    // ke masa lampau (time() - 3600 = 1 jam yang lalu).
    // Browser akan otomatis menghapus cookie yang sudah kadaluarsa.
    public function hapusCookie() {
        setcookie("inklu_email", "", time() - 3600, "/");
        setcookie("inklu_role",  "", time() - 3600, "/");
    }

    // METHOD: bacaCookie()
    // Membaca nilai cookie "ingat saya" dari browser.
    // Return: array dengan key 'email' dan 'role'.
    // Jika cookie tidak ada, nilai-nya adalah string kosong.
    // Digunakan untuk pre-fill form login agar user tidak perlu mengetik ulang.
    public function bacaCookie() {
        return array(
            'email' => isset($_COOKIE['inklu_email']) ? $_COOKIE['inklu_email'] : '',
            'role'  => isset($_COOKIE['inklu_role'])  ? $_COOKIE['inklu_role']  : '',
        );
    }
}
