<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    private $table = 'users';

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Dipakai oleh Auth controller untuk login
    public function get_by_email_role($email, $role) {
        $query = $this->db->get_where($this->table, array(
            'email' => $email,
            'role'  => $role
        ));
        return $query->row(); // kembalikan 1 baris data (object)
    }

    // Dipakai oleh Auth controller untuk registrasi
    public function insert($data) {
        return $this->db->insert($this->table, $data);
    }
}