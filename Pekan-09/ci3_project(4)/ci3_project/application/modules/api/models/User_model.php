<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    private $table = 'users';

    public function get_by_id($id)
    {
        $user = $this->db->select('id, name, email, created_at, updated_at')
            ->get_where($this->table, array('id' => $id))
            ->row();

        return $user;
    }

    public function get_by_email($email)
    {
        return $this->db->get_where($this->table, array('email' => strtolower($email)))->row();
    }

    public function create($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        $this->db->insert($this->table, $data);

        return $this->db->insert_id();
    }

    public function email_exists($email)
    {
        return $this->db->get_where($this->table, array('email' => strtolower($email)))->num_rows() > 0;
    }
}
