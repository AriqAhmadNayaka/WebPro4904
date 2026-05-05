<?php

// Controller Posts ini bagian yang mengatur alur CRUD artikel/post.
// Jadi tugasnya menerima request dari user, memanggil model, lalu menampilkan view yang sesuai.
class Posts extends CI_Controller {

    // Constructor ini jalan duluan setiap controller Posts dipakai.
    // Di sini model, helper, dan library disiapkan supaya function lain tidak perlu load ulang.
    public function __construct(){
        parent::__construct();
        $this->load->model('Post_model');
        $this->load->helper(array('form', 'url'));
        $this->load->library(array('form_validation', 'upload', 'session'));
    }

    // Halaman utama posts, isinya mengambil semua data post lalu dikirim ke view index.
    public function index(){
        $data['posts'] = $this->Post_model->get_posts();
        $data['title'] = 'All Posts';
        $this->load->view('posts/index', $data);
    }

    // Function ini cuma menampilkan form tambah post baru.
    // Belum menyimpan data, karena penyimpanan dilakukan di function store.
    public function create(){
        $data['title'] = 'Create New Post';
        $this->load->view('posts/create', $data);
    }

    // Function store dipakai saat form tambah post dikirim.
    // Isinya validasi input, menyiapkan data, upload gambar kalau ada, lalu insert ke database.
    public function store(){
        // Bagian ini membuat aturan validasi supaya field penting tidak kosong dan judul/author tidak terlalu panjang.
        $this->form_validation->set_rules('title', 'Title', 'required|max_length[255]');
        $this->form_validation->set_rules('author', 'Author', 'required|max_length[255]');
        $this->form_validation->set_rules('article', 'Article', 'required');

        // Kalau validasi gagal, user dikembalikan ke form tambah sambil membawa pesan error.
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('posts/create');
            return;
        }

        // Data dari input form disusun dulu ke array supaya gampang dikirim ke model.
        $data = array(
            'title'      => $this->input->post('title'),
            'author'     => $this->input->post('author'),
            'article'    => $this->input->post('article'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        );

        // Kalau user memilih gambar, program coba upload dulu sebelum data disimpan.
        if (!empty($_FILES['image']['name'])) {
            $upload_result = $this->_upload_image();

            if ($upload_result['status']) {
                $data['image'] = 'posts/' . $upload_result['file_name'];
            } else {
                $this->session->set_flashdata('error', $upload_result['error']);
                redirect('posts/create');
                return;
            }
        }

        // Setelah data siap, barulah data post dimasukkan ke database lewat model.
        $post_id = $this->Post_model->insert_post($data);

        // Bagian ini memberi feedback ke user, berhasil diarahkan ke detail dan gagal balik ke form.
        if ($post_id) {
            $this->session->set_flashdata('success', 'Post created successfully!');
            redirect('posts/show/' . $post_id);
        } else {
            $this->session->set_flashdata('error', 'Failed to create post');
            redirect('posts/create');
        }
    }

    // Function show untuk menampilkan satu post berdasarkan id.
    // Kalau id-nya tidak ditemukan, halaman 404 ditampilkan supaya tidak kosong aneh.
    public function show($id){
        $post = $this->Post_model->get_posts($id);

        if (!$post) {
            show_404();
            return;
        }

        $data['post'] = $post;
        $data['title'] = $post->title;

        $this->load->view('posts/show', $data);
    }

    // Function ini menampilkan form edit untuk post yang dipilih.
    // Data lama ikut dikirim supaya input form bisa langsung terisi.
    public function edit($id){
        $post = $this->Post_model->get_posts($id);

        if (!$post) {
            show_404();
            return;
        }

        $data['post'] = $post;
        $data['title'] = 'Edit Post';

        $this->load->view('posts/edit', $data);
    }

    // Function update memproses perubahan data post.
    // Karena ini edit, field yang dikirim saja yang akan diganti.
    public function update($id){
        // Cek dulu apakah post masih ada, biar tidak update id yang sebenarnya tidak punya data.
        if (!$this->Post_model->exists($id)) {
            show_404();
            return;
        }

        // Validasi edit dibuat lebih ringan, tapi title dan author tetap dibatasi panjangnya.
        $this->form_validation->set_rules('title', 'Title', 'max_length[255]');
        $this->form_validation->set_rules('author', 'Author', 'max_length[255]');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('posts/edit/' . $id);
            return;
        }

        // updated_at selalu diperbarui supaya terlihat kapan post terakhir diedit.
        $data = array(
            'updated_at' => date('Y-m-d H:i:s')
        );

        // Bagian ini hanya memasukkan field yang memang diisi dari form.
        if ($this->input->post('title')) {
            $data['title'] = $this->input->post('title');
        }
        if ($this->input->post('author')) {
            $data['author'] = $this->input->post('author');
        }
        if ($this->input->post('article')) {
            $data['article'] = $this->input->post('article');
        }

        // Kalau ada gambar baru, gambar lama dihapus dulu lalu upload gambar yang baru.
        if (!empty($_FILES['image']['name'])) {
            $old_post = $this->Post_model->get_posts($id);
            if ($old_post->image) {
                $old_image_path = FCPATH . 'uploads/' . $old_post->image;
                if (file_exists($old_image_path)) {
                    unlink($old_image_path);
                }
            }

            $upload_result = $this->_upload_image();

            if ($upload_result['status']) {
                $data['image'] = 'posts/' . $upload_result['file_name'];
            } else {
                $this->session->set_flashdata('error', $upload_result['error']);
                redirect('posts/edit/' . $id);
                return;
            }
        }

        // Data yang sudah disiapkan dikirim ke model untuk update database.
        $result = $this->Post_model->update_post($id, $data);

        if ($result) {
            $this->session->set_flashdata('success', 'Post updated successfully!');
            redirect('posts');
        } else {
            $this->session->set_flashdata('error', 'Failed to update post');
            redirect('posts/edit/' . $id);
        }
    }

    // Function delete menghapus post yang dipilih user.
    // Selain data database, file gambar juga ikut dihapus kalau memang ada.
    public function delete($id){
        $post = $this->Post_model->get_posts($id);

        if (!$post) {
            $this->session->set_flashdata('error', 'Post not found');
            redirect('posts');
            return;
        }

        // Bagian ini menghapus gambar di folder uploads supaya tidak numpuk file tidak kepakai.
        if ($post->image) {
            $image_path = FCPATH . 'uploads/' . $post->image;
            if (file_exists($image_path)) {
                unlink($image_path);
            }
        }

        $result = $this->Post_model->delete_post($id);

        if ($result) {
            $this->session->set_flashdata('success', 'Post deleted successfully!');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete post');
        }

        redirect('posts');
    }

    // Function private ini khusus mengurus upload gambar.
    // Dibuat terpisah supaya kode upload tidak diulang di store dan update.
    private function _upload_image(){
        $upload_path = FCPATH . 'uploads/posts/';

        // Kalau folder uploads/posts belum ada, folder dibuat dulu secara otomatis.
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0777, true);
        }

        // Nama file dibersihkan dari karakter aneh lalu ditambah waktu agar tidak mudah bentrok.
        $original_name = $_FILES['image']['name'];
        $sanitized_name = preg_replace('/[^A-Za-z0-9._-]/', '_', $original_name);
        $file_name = time() . '_' . $sanitized_name;

        // Konfigurasi upload: lokasi, tipe file, ukuran maksimal, nama file, dan aturan overwrite.
        $config['upload_path']   = $upload_path;
        $config['allowed_types'] = 'jpg|jpeg|png';
        $config['max_size']      = 2048; // 2MB
        $config['file_name']     = $file_name;
        $config['overwrite']     = FALSE;

        $this->upload->initialize($config);

        // Hasil upload dikembalikan dalam bentuk array supaya mudah dicek statusnya.
        if ($this->upload->do_upload('image')) {
            $upload_data = $this->upload->data();
            return array(
                'status' => TRUE,
                'file_name' => $upload_data['file_name']
            );
        } else {
            return array(
                'status' => FALSE,
                'error' => $this->upload->display_errors('', '')
            );
        }
    }

    // Bagian API di bawah ini dipakai untuk testing lewat Postman.
    // Output-nya JSON, jadi bukan menampilkan halaman HTML seperti CRUD biasa.

    // API ini mengembalikan semua post dalam format JSON.
    public function api_index(){
        $posts = $this->Post_model->get_posts();
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($posts));
    }

    // API ini mengembalikan satu post berdasarkan id.
    // Kalau id tidak ditemukan, status HTTP dibuat 404.
    public function api_show($id){
        $post = $this->Post_model->get_posts($id);
        if (!$post) {
            $this->output
                ->set_status_header(404)
                ->set_content_type('application/json')
                ->set_output(json_encode(['error' => 'Post not found']));
            return;
        }
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($post));
    }

    // API ini membuat post baru dari body JSON.
    // Jadi datanya bukan dari form HTML, melainkan dari request mentah seperti di Postman.
    public function api_create(){
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!$data) {
            $this->output
                ->set_status_header(400)
                ->set_content_type('application/json')
                ->set_output(json_encode(['error' => 'Invalid JSON data']));
            return;
        }

        // Field wajib dicek satu-satu supaya API tidak menerima data yang terlalu kosong.
        $required_fields = ['title', 'author', 'article'];
        foreach ($required_fields as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                $this->output
                    ->set_status_header(400)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(['error' => "Field '$field' is required"]));
                return;
            }
        }

        // Data JSON yang valid disusun ulang sesuai nama kolom di tabel posts.
        $insert_data = [
            'title' => $data['title'],
            'author' => $data['author'],
            'article' => $data['article'],
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $post_id = $this->Post_model->insert_post($insert_data);

        if ($post_id) {
            $this->output
                ->set_status_header(201)
                ->set_content_type('application/json')
                ->set_output(json_encode(['success' => 'Post created', 'id' => $post_id]));
        } else {
            $this->output
                ->set_status_header(500)
                ->set_content_type('application/json')
                ->set_output(json_encode(['error' => 'Failed to create post']));
        }
    }

    // API ini mengubah post berdasarkan id memakai data JSON.
    // Sebelum update, dicek dulu id-nya ada atau tidak.
    public function api_update($id){
        if (!$this->Post_model->exists($id)) {
            $this->output
                ->set_status_header(404)
                ->set_content_type('application/json')
                ->set_output(json_encode(['error' => 'Post not found']));
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!$data) {
            $this->output
                ->set_status_header(400)
                ->set_content_type('application/json')
                ->set_output(json_encode(['error' => 'Invalid JSON data']));
            return;
        }

        // updated_at selalu diganti, lalu field lain dimasukkan kalau memang ada di JSON.
        $update_data = ['updated_at' => date('Y-m-d H:i:s')];

        if (isset($data['title']) && !empty($data['title'])) {
            $update_data['title'] = $data['title'];
        }
        if (isset($data['author']) && !empty($data['author'])) {
            $update_data['author'] = $data['author'];
        }
        if (isset($data['article']) && !empty($data['article'])) {
            $update_data['article'] = $data['article'];
        }

        $result = $this->Post_model->update_post($id, $update_data);

        if ($result) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['success' => 'Post updated']));
        } else {
            $this->output
                ->set_status_header(500)
                ->set_content_type('application/json')
                ->set_output(json_encode(['error' => 'Failed to update post']));
        }
    }

    // API ini menghapus post lewat request API.
    // Responnya tetap JSON supaya cocok dengan pola testing di Postman.
    public function api_delete($id){
        if (!$this->Post_model->exists($id)) {
            $this->output
                ->set_status_header(404)
                ->set_content_type('application/json')
                ->set_output(json_encode(['error' => 'Post not found']));
            return;
        }

        $result = $this->Post_model->delete_post($id);

        if ($result) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['success' => 'Post deleted']));
        } else {
            $this->output
                ->set_status_header(500)
                ->set_content_type('application/json')
                ->set_output(json_encode(['error' => 'Failed to delete post']));
        }
    }
}