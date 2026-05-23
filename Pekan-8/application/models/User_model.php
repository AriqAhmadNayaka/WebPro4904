<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model
{
    private $table = 'users';

    public function get_by_username($username)
    {
        return $this->db->get_where($this->table, array('username' => $username))->row();
    }

    public function update_password($id, $hashed_password)
    {
        return $this->db
            ->where('id', (int) $id)
            ->update($this->table, array('password' => $hashed_password));
    }
}
