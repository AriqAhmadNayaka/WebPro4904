<?php
class User_model extends CI_Model {

    public function register($email, $password) {
        $data = [
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT)
        ];
        return $this->db->insert('user', $data);
    }

    public function login($email, $password) {
        $this->db->where('email', $email);
        $user = $this->db->get('user')->row_array();

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }
}