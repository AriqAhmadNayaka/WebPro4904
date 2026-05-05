<?php

// Model ini bagian yang urus hubungan data post dengan database.
// Controller nanti tinggal manggil model ini, jadi query database tidak berantakan di controller.
class Post_model extends CI_Model {

    // Nama tabel disimpan di variabel supaya kalau tabelnya ganti, editnya tidak nyari-nyari terlalu jauh.
    private $table = 'posts';

    // Constructor ini otomatis jalan waktu model dipanggil.
    // Isinya cuma load database, karena semua function di bawah butuh koneksi database.
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    // Function ini dipakai untuk ambil data post.
    // Kalau id tidak dikirim, berarti ambil semua post. Kalau id dikirim, ambil satu post saja.
    public function get_posts($id = FALSE){
        if($id === FALSE){
            // Bagian ini mengambil semua data dari tabel posts.
            $query = $this->db->get($this->table);
            $posts = $query->result();

            // Tiap post dicek apakah punya gambar atau tidak, lalu dibuatkan URL gambarnya.
            foreach ($posts as $post) {
                if ($post->image) {
                    $imagePath = str_replace('posts/', '', $post->image);
                    $post->image_url = base_url('uploads/posts/' . $imagePath);
                } else {
                    $post->image_url = null;
                }
            }

            return $posts;
        }

        // Bagian ini khusus mengambil satu post berdasarkan id yang dikirim dari controller.
        $query = $this->db->get_where($this->table, array('id' => $id));
        $post = $query->row();

        // Kalau satu post itu ada gambarnya, dibuat juga URL gambar supaya view lebih gampang nampilin.
        if ($post && $post->image) {
            $imagePath = str_replace('posts/', '', $post->image);
            $post->image_url = base_url('uploads/posts/' . $imagePath);
        }

        return $post;
    }

    // Function ini buat menyimpan data post baru ke database.
    // Setelah insert, id post baru dikembalikan supaya bisa dipakai redirect ke halaman detail.
    public function insert_post($data){
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    // Function ini buat update post berdasarkan id.
    // Data yang dikirim sudah disiapkan dulu dari controller, jadi model tinggal menjalankan query update.
    public function update_post($id, $data){
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }

    // Function ini buat menghapus post dari database sesuai id.
    // Jadi yang hilang adalah baris data post yang dipilih user.
    public function delete_post($id){
        $this->db->where('id', $id);
        return $this->db->delete($this->table);
    }

    // Function kecil ini buat mengecek apakah post dengan id tertentu memang ada.
    // Biasanya dipakai sebelum update atau delete, biar tidak asal proses data yang tidak jelas.
    public function exists($id)
    {
        $query = $this->db->get_where($this->table, array('id' => $id));
        return $query->num_rows() > 0;
    }
}