<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model
{
    private $table = 'users';

    public function find_by_username($username)
    {
        return $this->db->get_where($this->table, array('username' => $username))->row();
    }

    public function verify_credentials($username, $password)
    {
        $users = $this->db
            ->where('username', $username)
            ->order_by('id', 'DESC')
            ->get($this->table)
            ->result();

        if (empty($users)) {
            return NULL;
        }

        foreach ($users as $user) {
            $stored_password = (string) $user->password;

            if ($stored_password === $password) {
                return $user;
            }

            if (password_verify($password, $stored_password)) {
                return $user;
            }
        }

        return NULL;
    }

    public function create($data)
    {
        return $this->db->insert($this->table, $data);
    }
}
