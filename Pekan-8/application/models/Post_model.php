<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Post_model extends CI_Model {

    private $table = 'datauser';
    private $last_error = '';

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_all()
    {
        if (!$this->db->table_exists($this->table)) {
            return array();
        }

        return $this->db
            ->order_by('id', 'DESC')
            ->get($this->table)
            ->result();
    }

    public function get_by_id($id)
    {
        if (!$this->db->table_exists($this->table)) {
            return NULL;
        }

        return $this->db
            ->get_where($this->table, array('id' => $id))
            ->row();
    }

    public function get_by_email($email)
    {
        if (!$this->db->table_exists($this->table)) {
            return NULL;
        }

        return $this->db
            ->get_where($this->table, array('email' => $email))
            ->row();
    }

    public function insert($data)
    {
        if (!$this->db->table_exists($this->table)) {
            $this->last_error = 'Table datauser tidak ditemukan.';
            return FALSE;
        }

        $result = $this->db->insert($this->table, $data);
        if (!$result) {
            $error = $this->db->error();
            $this->last_error = !empty($error['message']) ? $error['message'] : 'Insert database gagal.';
        }

        return $result ? $this->db->insert_id() : FALSE;
    }

    public function update($id, $data)
    {
        if (!$this->db->table_exists($this->table)) {
            $this->last_error = 'Table datauser tidak ditemukan.';
            return FALSE;
        }

        $result = $this->db
            ->where('id', $id)
            ->update($this->table, $data);

        if (!$result) {
            $error = $this->db->error();
            $this->last_error = !empty($error['message']) ? $error['message'] : 'Update database gagal.';
        }

        return $result;
    }

    public function delete($id)
    {
        if (!$this->db->table_exists($this->table)) {
            $this->last_error = 'Table datauser tidak ditemukan.';
            return FALSE;
        }

        $result = $this->db
            ->where('id', $id)
            ->delete($this->table);

        if (!$result) {
            $error = $this->db->error();
            $this->last_error = !empty($error['message']) ? $error['message'] : 'Delete database gagal.';
        }

        return $result;
    }

    public function exists($id)
    {
        if (!$this->db->table_exists($this->table)) {
            $this->last_error = 'Table datauser tidak ditemukan.';
            return FALSE;
        }

        return $this->db
            ->where('id', $id)
            ->count_all_results($this->table) > 0;
    }

    public function get_last_error()
    {
        return $this->last_error;
    }
}
