<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_model extends CI_Model {

    public function ensure_default_user() {
        if (!$this->db->table_exists('users')) {
            return;
        }

        if ($this->db->count_all('users') > 0) {
            return;
        }

        $fields = $this->db->list_fields('users');
        $data = array();

        if (in_array('username', $fields, TRUE)) {
            $data['username'] = 'admin';
        }
        if (in_array('nama', $fields, TRUE)) {
            $data['nama'] = 'admin';
        }
        if (in_array('email', $fields, TRUE)) {
            $data['email'] = 'admin@example.com';
        }
        if (in_array('password', $fields, TRUE)) {
            $data['password'] = password_hash('password', PASSWORD_DEFAULT);
        }
        if (in_array('image', $fields, TRUE)) {
            $data['image'] = NULL;
        }

        if (!empty($data)) {
            $this->db->insert('users', $data);
        }
    }

    public function find_user($login_value) {
        if (!$this->db->table_exists('users')) {
            return NULL;
        }

        $fields = $this->db->list_fields('users');
        $identifier_fields = array_intersect(array('username', 'email', 'nama'), $fields);

        if (empty($identifier_fields)) {
            return NULL;
        }

        $this->db->from('users');
        $this->db->group_start();
        foreach ($identifier_fields as $field) {
            $this->db->or_where($field, $login_value);
        }
        $this->db->group_end();

        return $this->db->get()->row_array();
    }

    public function get_display_name($user) {
        foreach (array('username', 'nama', 'email') as $field) {
            if (!empty($user[$field])) {
                return $user[$field];
            }
        }

        return 'User';
    }

    public function email_exists($email) {
        if (!$this->db->table_exists('users')) {
            return FALSE;
        }

        $fields = $this->db->list_fields('users');
        if (!in_array('email', $fields, TRUE)) {
            return FALSE;
        }

        return $this->db->where('email', $email)->count_all_results('users') > 0;
    }

    public function create_user($input) {
        if (!$this->db->table_exists('users')) {
            return FALSE;
        }

        $fields = $this->db->list_fields('users');
        $data = array();

        if (in_array('nama', $fields, TRUE)) {
            $data['nama'] = $input['nama'];
        }

        if (in_array('email', $fields, TRUE)) {
            $data['email'] = $input['email'];
        }

        if (in_array('username', $fields, TRUE)) {
            $data['username'] = isset($input['username']) ? $input['username'] : $input['email'];
        }

        if (in_array('password', $fields, TRUE)) {
            $data['password'] = password_hash($input['password'], PASSWORD_DEFAULT);
        }

        if (in_array('image', $fields, TRUE)) {
            $data['image'] = NULL;
        }

        if (empty($data)) {
            return FALSE;
        }

        return $this->db->insert('users', $data);
    }
}
