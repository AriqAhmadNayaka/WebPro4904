<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model
{
    // Semua operasi model ini mengarah ke tabel users.
    private $table = 'users';

    public function find_by_email($email)
    {
        // Dipakai saat login/register untuk cek apakah email ada.
        return $this->db->get_where($this->table, ['email' => $email])->row_array();
    }

    public function register($name, $email, $password, $role)
    {
        // Password di-hash supaya tidak pernah tersimpan dalam bentuk plaintext.
        $data = [
            'name' => $name,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'role' => $role,
        ];

        return $this->db->insert($this->table, $data);
    }
}
