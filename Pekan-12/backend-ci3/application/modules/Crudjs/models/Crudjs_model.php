<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Crudjs_model extends CI_Model {

    // Modul CRUD AJAX memakai tabel posts yang sama.
    private $table = 'posts';

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');
    }

    private function image_url($image)
    {
        // Membentuk URL gambar agar response AJAX bisa langsung dipakai oleh JavaScript.
        if (empty($image)) {
            return null;
        }

        $image = ltrim($image, '/');
        $path = strpos($image, '/') === false ? 'posts/' . $image : $image;

        return base_url('uploads/' . $path);
    }

    /**
     * Mengambil semua record post untuk tabel AJAX.
     */
    public function get_all()
    {
        $query = $this->db->get($this->table);
        $records = $query->result();
        
        // Tambahkan URL gambar untuk ditampilkan pada tabel dan modal detail.
        foreach ($records as $record) {
            $record->image_url = $this->image_url($record->image);
        }
        
        return $records;
    }

    /**
     * Mengambil satu record berdasarkan ID.
     */
    public function get_by_id($id)
    {
        $query = $this->db->get_where($this->table, array('id' => $id));
        $record = $query->row();
        
        if ($record) {
            $record->image_url = $this->image_url($record->image);
        }
        
        return $record;
    }

    /**
     * Menyimpan record baru dari form AJAX.
     */
    public function insert($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    /**
     * Mengubah record dari form AJAX.
     */
    public function update($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }

    /**
     * Menghapus record berdasarkan ID.
     */
    public function delete($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete($this->table);
    }

    /**
     * Mengecek apakah record tersedia.
     */
    public function exists($id)
    {
        $query = $this->db->get_where($this->table, array('id' => $id));
        return $query->num_rows() > 0;
    }
}
