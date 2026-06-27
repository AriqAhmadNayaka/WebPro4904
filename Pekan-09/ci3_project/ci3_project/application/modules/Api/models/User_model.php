<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model
{
    private $table = 'users';

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function insert($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function get_by_id($id)
    {
        return $this->db->get_where($this->table, array('id' => $id))->row();
    }

    public function get_by_email($email)
    {
        return $this->db->get_where($this->table, array('email' => $email))->row();
    }

    public function email_exists($email)
    {
        return $this->db->get_where($this->table, array('email' => $email))->num_rows() > 0;
    }

    public function exists($id)
    {
        return $this->db->get_where($this->table, array('id' => $id))->num_rows() > 0;
    }
}
