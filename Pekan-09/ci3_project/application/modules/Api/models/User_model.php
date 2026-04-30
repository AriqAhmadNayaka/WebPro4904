<?php
// Mencegah file model diakses langsung dari browser.
defined('BASEPATH') OR exit('No direct script access allowed');

// Model untuk mengelola data user pada API authentication.
class User_model extends CI_Model {

    // Nama tabel database yang digunakan oleh project Pekan-09.
    private $table = 'ci3_users';

    // Constructor dijalankan otomatis saat model dipanggil.
    public function __construct()
    {
        // Memanggil constructor bawaan CI_Model.
        parent::__construct();
        // Memuat koneksi database CodeIgniter.
        $this->load->database();
    }

    // Menambahkan user baru ke database.
    public function create($data)
    {
        // Menjalankan query INSERT ke tabel ci3_users.
        return $this->db->insert($this->table, $data) ? $this->db->insert_id() : false;
    }

    // Mengambil data user berdasarkan ID.
    public function get_by_id($id)
    {
        // Menjalankan query SELECT * FROM users WHERE id = $id dan mengambil satu baris.
        return $this->db->get_where($this->table, array('id' => $id))->row();
    }

    // Mengambil data user berdasarkan email.
    public function get_by_email($email)
    {
        // Mengubah email menjadi huruf kecil agar pencarian konsisten.
        return $this->db->get_where($this->table, array('email' => strtolower($email)))->row();
    }

    // Mengecek apakah email sudah terdaftar di database.
    public function email_exists($email)
    {
        // Mengembalikan TRUE jika jumlah data dengan email tersebut lebih dari 0.
        return $this->db->get_where($this->table, array('email' => strtolower($email)))->num_rows() > 0;
    }
}
