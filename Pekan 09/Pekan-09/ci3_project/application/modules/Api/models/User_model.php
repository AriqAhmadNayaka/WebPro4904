<?php
// Mencegah file model diakses langsung dari browser.
defined('BASEPATH') OR exit('No direct script access allowed');

// Model untuk mengelola data user pada API authentication.
class User_model extends CI_Model {

    // Nama tabel database yang digunakan untuk menyimpan data user.
    private $table = 'users';

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
        // Mengisi waktu data dibuat.
        $data['created_at'] = date('Y-m-d H:i:s');
        // Mengisi waktu data terakhir diubah.
        $data['updated_at'] = date('Y-m-d H:i:s');
        // Menjalankan query INSERT ke tabel users.
        $this->db->insert($this->table, $data);
        // Mengembalikan ID user yang baru dibuat.
        return $this->db->insert_id();
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

    // Mengambil semua data user (untuk keperluan debug/cek data).
    public function get_all()
    {
        return $this->db->select('id, name, email, created_at')->get($this->table)->result();
    }
}
