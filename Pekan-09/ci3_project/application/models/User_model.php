<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Model ini menangani data pengguna untuk autentikasi.
class User_model extends CI_Model
{
    private $table = 'ci3_users';

    public function authenticate($email, $password)
    {
        // Ambil user berdasarkan email terlebih dahulu.
        $user = $this->db->get_where($this->table, array('email' => $email))->row();

        if (!$user) {
            return null;
        }

        // Verifikasi password hash sebelum login dinyatakan berhasil.
        return password_verify($password, $user->password) ? $user : null;
    }

    public function create($data)
    {
        // Simpan akun baru ke tabel user.
        return $this->db->insert($this->table, $data);
    }
}
