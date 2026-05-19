<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model User_model
 *
 * Bertanggung jawab atas semua query database yang berhubungan
 * dengan tabel 'users': login dan registrasi akun.
 *
 * Dalam CI3, model mewarisi CI_Model dan menggunakan $this->db
 * sebagai pengganti objek koneksi manual (tidak perlu Database.php lagi).
 */
class User_model extends CI_Model {

    /**
     * Melakukan proses login.
     * Mengambil data user berdasarkan username, lalu memverifikasi
     * password menggunakan password_verify() terhadap hash di database.
     *
     * @param  string $username
     * @param  string $password
     * @return bool   true jika login berhasil
     */
    public function login(string $username, string $password): bool
    {
        $this->db->where('username', $username);
        $query = $this->db->get('users');
        $user  = $query->row_array();

        if ($user && password_verify($password, $user['password'])) {
            // Simpan data sesi user
            $this->session->set_userdata([
                'login'    => TRUE,
                'username' => $user['username'],
            ]);
            return TRUE;
        }

        return FALSE;
    }

    /**
     * Melakukan proses registrasi akun baru.
     * Mengecek duplikasi username, kecocokan password, lalu insert ke DB.
     *
     * @param  string $username
     * @param  string $password
     * @param  string $confirm_password
     * @return string 'sukses' atau pesan error
     */
    public function register(string $username, string $password, string $confirm_password): string
    {
        // Cek apakah username sudah terdaftar
        $this->db->where('username', $username);
        if ($this->db->get('users')->num_rows() > 0) {
            return 'Username sudah terdaftar!';
        }

        // Cek kecocokan password
        if ($password !== $confirm_password) {
            return 'Konfirmasi password tidak cocok!';
        }

        // Hash password sebelum disimpan (keamanan data)
        $hashed = password_hash($password, PASSWORD_DEFAULT);

        // Insert user baru
        $insert = $this->db->insert('users', [
            'username' => $username,
            'password' => $hashed,
        ]);

        return $insert ? 'sukses' : 'Gagal mendaftarkan user.';
    }
}
?>
