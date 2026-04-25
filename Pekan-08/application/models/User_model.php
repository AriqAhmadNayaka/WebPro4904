<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model
{
    private $table = 'users';

    public function users_table_ready()
    {
        try {
            return $this->db->table_exists($this->table);
        } catch (Exception $e) {
            return FALSE;
        }
    }

    public function check_login($username, $password)
    {
        try {
            if (!$this->users_table_ready()) {
                return FALSE;
            }

            return $this->db->get_where($this->table, array(
                'username' => $username,
                'password' => $password,
            ))->row();
        } catch (Exception $e) {
            return FALSE;
        }
    }

    public function username_exists($username)
    {
        try {
            if (!$this->users_table_ready()) {
                return FALSE;
            }

            return $this->db->get_where($this->table, array(
                'username' => $username,
            ))->num_rows() > 0;
        } catch (Exception $e) {
            return FALSE;
        }
    }

    public function register($data)
    {
        try {
            if (!$this->users_table_ready()) {
                return FALSE;
            }

            return $this->db->insert($this->table, $data);
        } catch (Exception $e) {
            return FALSE;
        }
    }
}
