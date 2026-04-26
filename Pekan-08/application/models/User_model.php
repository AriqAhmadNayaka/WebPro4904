<?php
defined('BASEPATH') OR exit('No direct script access allowed'); // Pastikan file ini tidak bisa diakses langsung melalui URL.

class User_model extends CI_Model
{
    public function check_login($username, $password)
    {
        // Cari user yang username dan password-nya cocok.
        return $this->db
            ->where('username', $username)
            ->where('password', md5($password))
            ->get('users')
            ->row();
    }
}
