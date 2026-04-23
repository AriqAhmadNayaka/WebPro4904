<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    private $tabel = 'users';

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Cek login berdasarkan email (password di-verify di controller)
    public function cek_login($email) {
        $query = $this->db->get_where($this->tabel, array('email' => $email));
        return $query->row(); // return object atau NULL
    }

    // Simpan user baru
    public function register($data) {
        return $this->db->insert($this->tabel, $data);
    }
}
