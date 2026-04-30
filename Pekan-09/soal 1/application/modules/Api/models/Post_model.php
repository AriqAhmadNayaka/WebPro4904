<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Post_model extends CI_Model {

    // Nama tabel untuk menyimpan artikel/post.
    private $table = 'posts';

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Mengambil semua post dengan pagination.
     */
    public function get_all($limit = 10, $offset = 0)
    {
        $query = $this->db
            ->select('id, title, author, article, image, created_at, updated_at')
            ->limit($limit, $offset)
            ->order_by('created_at', 'DESC')
            ->get($this->table);
        return $query->result();
    }

    /**
     * Menghitung total post untuk informasi pagination.
     */
    public function count_all()
    {
        return $this->db->count_all($this->table);
    }

    /**
     * Mengambil satu post berdasarkan ID.
     */
    public function get_by_id($id)
    {
        $query = $this->db
            ->select('id, title, author, article, image, created_at, updated_at')
            ->get_where($this->table, array('id' => $id));
        return $query->row();
    }

    /**
     * Menyimpan post baru.
     */
    public function create($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    /**
     * Mengubah data post.
     */
    public function update($id, $data)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }

    /**
     * Menghapus data post.
     */
    public function delete($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete($this->table);
    }

    /**
     * Mengecek apakah post tersedia berdasarkan ID.
     */
    public function exists($id)
    {
        $query = $this->db->get_where($this->table, array('id' => $id));
        return $query->num_rows() > 0;
    }

    /**
     * Mencari post berdasarkan kata kunci.
     */
    public function search($keyword, $limit = 10, $offset = 0)
    {
        $this->db->like('title', $keyword);
        $this->db->or_like('description', $keyword);
        $this->db->limit($limit, $offset);
        $this->db->order_by('created_at', 'DESC');
        $query = $this->db->get($this->table);
        return $query->result();
    }

    /**
     * Mengambil post berdasarkan user jika relasi user dipakai.
     */
    public function get_by_user($user_id, $limit = 10, $offset = 0)
    {
        $query = $this->db
            ->select('id, title, author, article, image, created_at, updated_at')
            ->where('user_id', $user_id)
            ->limit($limit, $offset)
            ->order_by('created_at', 'DESC')
            ->get($this->table);
        return $query->result();
    }
}
