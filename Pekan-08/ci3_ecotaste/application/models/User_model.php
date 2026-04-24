<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model
{
    private $table = 'users';

    public function login($username, $password)
    {
        $user = $this->db
            ->where('username', $username)
            ->get($this->table)
            ->row();

        if (!$user) {
            return NULL;
        }

        if (!password_verify($password, $user->password)) {
            return NULL;
        }

        return $user;
    }
}
