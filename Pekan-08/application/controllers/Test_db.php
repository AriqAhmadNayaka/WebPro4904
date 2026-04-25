<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test_db extends MY_Controller
{
    public function index()
    {
        $this->load->database();

        if ($this->db->conn_id) {
            $query = $this->db->query('SELECT DATABASE() AS db_name');
            $row = $query->row();

            echo 'Koneksi database berhasil. Database aktif: ' . $row->db_name;
            return;
        }

        echo 'Koneksi database gagal.';
    }
}
