<?php
// Mencegah file diakses langsung dari browser tanpa lewat CodeIgniter.
defined('BASEPATH') OR exit('No direct script access allowed');

// Model untuk mengatur query database pada fitur CRUD with AJAX.
class Crudjs_model extends CI_Model {

    // Nama tabel database yang digunakan untuk menyimpan data post.
    private $table = 'posts';

    // Constructor dijalankan otomatis saat model dipanggil.
    public function __construct()
    {
        // Memanggil constructor bawaan CI_Model.
        parent::__construct();
        // Memuat koneksi database CodeIgniter.
        $this->load->database();
    }

    // Mengambil semua data post dari tabel posts.
    public function get_all()
    {
        // Menjalankan query SELECT * FROM posts.
        $query = $this->db->get($this->table);
        // Mengubah hasil query menjadi array object.
        $records = $query->result();

        // Melakukan perulangan untuk setiap data post.
        foreach ($records as $record) {
            // Mengecek apakah data post memiliki gambar.
            if ($record->image) {
                // Menghapus prefix folder posts/ agar path gambar tidak dobel.
                $imagePath = str_replace('posts/', '', $record->image);
                // Membuat URL lengkap gambar agar bisa ditampilkan di halaman AJAX.
                $record->image_url = base_url('uploads/posts/' . $imagePath);
            } else {
                // Jika tidak ada gambar, nilai image_url dibuat null.
                $record->image_url = null;
            }
        }

        // Mengembalikan semua data post ke controller.
        return $records;
    }

    // Mengambil satu data post berdasarkan ID.
    public function get_by_id($id)
    {
        // Menjalankan query SELECT * FROM posts WHERE id = $id.
        $query = $this->db->get_where($this->table, array('id' => $id));
        // Mengambil satu baris hasil query sebagai object.
        $record = $query->row();

        // Mengecek apakah data ditemukan dan memiliki gambar.
        if ($record && $record->image) {
            // Menghapus prefix folder posts/ agar path gambar rapi.
            $imagePath = str_replace('posts/', '', $record->image);
            // Membuat URL lengkap gambar untuk dikirim ke JavaScript.
            $record->image_url = base_url('uploads/posts/' . $imagePath);
        }

        // Mengembalikan satu data post ke controller.
        return $record;
    }

    // Menambahkan data post baru ke database.
    public function insert($data)
    {
        // Menjalankan query INSERT ke tabel posts.
        $this->db->insert($this->table, $data);
        // Mengembalikan ID dari data yang baru ditambahkan.
        return $this->db->insert_id();
    }

    // Mengubah data post berdasarkan ID.
    public function update($id, $data)
    {
        // Menentukan data mana yang akan diupdate berdasarkan kolom id.
        $this->db->where('id', $id);
        // Menjalankan query UPDATE dan mengembalikan status berhasil/gagal.
        return $this->db->update($this->table, $data);
    }

    // Menghapus data post berdasarkan ID.
    public function delete($id)
    {
        // Menentukan data mana yang akan dihapus berdasarkan kolom id.
        $this->db->where('id', $id);
        // Menjalankan query DELETE dan mengembalikan status berhasil/gagal.
        return $this->db->delete($this->table);
    }

    // Mengecek apakah data dengan ID tertentu ada di database.
    public function exists($id)
    {
        // Mengambil data dari tabel posts berdasarkan ID.
        $query = $this->db->get_where($this->table, array('id' => $id));
        // Mengembalikan TRUE jika jumlah baris lebih dari 0.
        return $query->num_rows() > 0;
    }
}
