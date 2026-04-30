<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Post_model extends CI_Model {

    // Tabel utama untuk data post pada halaman web.
    private $table = 'posts';

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper('url');
    }

    private function image_url($image)
    {
        // Membentuk URL gambar yang benar dari nilai kolom image.
        if (empty($image)) {
            return null;
        }

        $image = ltrim($image, '/');
        $path = strpos($image, '/') === false ? 'posts/' . $image : $image;

        return base_url('uploads/' . $path);
    }

    /**
     * Mengambil semua post untuk halaman daftar post.
     */
    public function get_all()
    {
        $query = $this->db->get($this->table);
        $posts = $query->result();
        
        // Tambahkan URL gambar agar view bisa langsung menampilkan gambar.
        foreach ($posts as $post) {
            $post->image_url = $this->image_url($post->image);
        }
        
        return $posts;
    }

    /**
     * Mengambil detail post berdasarkan ID.
     */
    public function get_by_id($id)
    {
        $query = $this->db->get_where($this->table, array('id' => $id));
        $post = $query->row();
        
        if ($post) {
            $post->image_url = $this->image_url($post->image);
        }
        
        return $post;
    }

    /**
     * Menyimpan post baru dari form web.
     */
    public function insert($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    /**
     * Mengubah data post dari form edit.
     */
    public function update($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }

    /**
     * Menghapus post berdasarkan ID.
     */
    public function delete($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete($this->table);
    }

    /**
     * Mengecek apakah post tersedia.
     */
    public function exists($id)
    {
        $query = $this->db->get_where($this->table, array('id' => $id));
        return $query->num_rows() > 0;
    }
}
