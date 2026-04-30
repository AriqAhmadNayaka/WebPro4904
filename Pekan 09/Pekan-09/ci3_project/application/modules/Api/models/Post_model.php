<?php
// Mencegah file model diakses langsung dari browser.
defined('BASEPATH') OR exit('No direct script access allowed');

// Model untuk mengelola data post pada API.
class Post_model extends CI_Model {

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

    // Mengambil semua data post dari database.
    public function get_all()
    {
        // Mengurutkan data dari ID terbaru lalu mengembalikan hasil sebagai array object.
        return $this->db->order_by('id', 'DESC')->get($this->table)->result();
    }

    // Mengambil satu data post berdasarkan ID.
    public function get_by_id($id)
    {
        // Menjalankan query SELECT * FROM posts WHERE id = $id dan mengambil satu baris.
        return $this->db->get_where($this->table, array('id' => $id))->row();
    }

    // Menambahkan post baru ke database.
    public function create($data)
    {
        // Mengisi waktu data dibuat.
        $data['created_at'] = date('Y-m-d H:i:s');
        // Mengisi waktu data terakhir diubah.
        $data['updated_at'] = date('Y-m-d H:i:s');
        // Menjalankan query INSERT ke tabel posts.
        $this->db->insert($this->table, $data);
        // Mengembalikan ID post yang baru dibuat.
        return $this->db->insert_id();
    }

    // Mengubah data post berdasarkan ID.
    public function update($id, $data)
    {
        // Memperbarui waktu update setiap kali data diubah.
        $data['updated_at'] = date('Y-m-d H:i:s');
        // Menentukan data mana yang akan diupdate berdasarkan ID.
        $this->db->where('id', $id);
        // Menjalankan query UPDATE dan mengembalikan status berhasil/gagal.
        return $this->db->update($this->table, $data);
    }

    // Menghapus data post berdasarkan ID.
    public function delete($id)
    {
        // Menentukan data mana yang akan dihapus berdasarkan ID.
        $this->db->where('id', $id);
        // Menjalankan query DELETE dan mengembalikan status berhasil/gagal.
        return $this->db->delete($this->table);
    }

    // Mengecek apakah data post dengan ID tertentu ada.
    public function exists($id)
    {
        // Mengembalikan TRUE jika jumlah data dengan ID tersebut lebih dari 0.
        return $this->db->get_where($this->table, array('id' => $id))->num_rows() > 0;
    }
}
