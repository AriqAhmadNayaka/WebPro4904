<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    private $table = 'user';

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    // Cek login user
    public function cek_login($username, $password)
    {
        $query = $this->db->get_where($this->table, array(
            'username' => $username,
            'password' => $password,
        ));
        return $query->num_rows() > 0;
    }

    // Cek username sudah ada atau belum
    public function cek_username($username)
    {
        $query = $this->db->get_where($this->table, array('username' => $username));
        return $query->num_rows() > 0;
    }

    // Insert data user baru
    public function register($data)
    {
        return $this->db->insert($this->table, $data);
    }
}
