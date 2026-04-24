<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Posts extends CI_Controller {

    private $post_model_ready = FALSE;
    private $photo_directory = '';

    public function __construct()
    {
        parent::__construct();
        $this->photo_directory = dirname(FCPATH) . DIRECTORY_SEPARATOR . 'Pekan-6' . DIRECTORY_SEPARATOR . 'img';
        $this->_load_post_model();
        $this->_require_login();
    }

    public function index()
    {
        $data['posts'] = $this->_get_all_posts();
        $data['title'] = 'CyberVault Management';

        $this->load->view('posts/index', $data);
    }

    public function create()
    {
        $data['title'] = 'Tambah User';
        $this->load->view('posts/create', $data);
    }

    public function store()
    {
        if (!$this->post_model_ready) {
            $this->session->set_flashdata('error', 'Database belum siap, jadi data user belum bisa disimpan.');
            redirect('posts/create');
            return;
        }

        $this->form_validation->set_rules('nama', 'Nama', 'required|max_length[255]');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|max_length[255]');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[4]');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('posts/create');
            return;
        }

        $data = array(
            'nama' => $this->input->post('nama', TRUE),
            'email' => $this->input->post('email', TRUE),
            'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
            'foto' => 'default.png'
        );

        if (!empty($_FILES['foto']['name'])) {
            $upload_result = $this->_upload_photo();

            if ($upload_result['status']) {
                $data['foto'] = $upload_result['file_name'];
            } else {
                $this->session->set_flashdata('error', $upload_result['error']);
                redirect('posts/create');
                return;
            }
        }

        $post_id = $this->Post_model->insert($data);

        if ($post_id) {
            $this->session->set_flashdata('success', 'Data user berhasil ditambahkan.');
            redirect('posts');
            return;
        }

        $error_message = $this->Post_model->get_last_error();
        $this->session->set_flashdata('error', $error_message ? 'Gagal menambahkan user. ' . $error_message : 'Gagal menambahkan user.');
        redirect('posts/create');
    }

    public function show($id)
    {
        $post = $this->_get_post($id);

        if (!$post) {
            show_404();
            return;
        }

        $data['post'] = $post;
        $data['title'] = $post->nama;
        $this->load->view('posts/show', $data);
    }

    public function edit($id)
    {
        $post = $this->_get_post($id);

        if (!$post) {
            show_404();
            return;
        }

        $data['post'] = $post;
        $data['title'] = 'Edit User';
        $this->load->view('posts/edit', $data);
    }

    public function update($id)
    {
        if (!$this->post_model_ready || !$this->Post_model->exists($id)) {
            show_404();
            return;
        }

        $this->form_validation->set_rules('nama', 'Nama', 'required|max_length[255]');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|max_length[255]');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('posts/edit/' . $id);
            return;
        }

        $data = array(
            'nama' => $this->input->post('nama', TRUE),
            'email' => $this->input->post('email', TRUE)
        );

        if (!empty($_FILES['foto']['name'])) {
            $old_post = $this->_get_post($id);
            if ($old_post && !empty($old_post->foto) && $old_post->foto !== 'default.png') {
                $old_image_path = $this->photo_directory . DIRECTORY_SEPARATOR . $old_post->foto;
                if (file_exists($old_image_path)) {
                    unlink($old_image_path);
                }
            }

            $upload_result = $this->_upload_photo();
            if ($upload_result['status']) {
                $data['foto'] = $upload_result['file_name'];
            } else {
                $this->session->set_flashdata('error', $upload_result['error']);
                redirect('posts/edit/' . $id);
                return;
            }
        }

        if ($this->Post_model->update($id, $data)) {
            $this->session->set_flashdata('success', 'Data user berhasil diperbarui.');
            redirect('posts');
            return;
        }

        $error_message = $this->Post_model->get_last_error();
        $this->session->set_flashdata('error', $error_message ? 'Gagal memperbarui user. ' . $error_message : 'Gagal memperbarui user.');
        redirect('posts/edit/' . $id);
    }

    public function delete($id)
    {
        if (!$this->post_model_ready) {
            $this->session->set_flashdata('error', 'Database belum siap, jadi data user belum bisa dihapus.');
            redirect('posts');
            return;
        }

        $post = $this->_get_post($id);
        if (!$post) {
            $this->session->set_flashdata('error', 'Data user tidak ditemukan.');
            redirect('posts');
            return;
        }

        if (!empty($post->foto) && $post->foto !== 'default.png') {
            $image_path = $this->photo_directory . DIRECTORY_SEPARATOR . $post->foto;
            if (file_exists($image_path)) {
                unlink($image_path);
            }
        }

        if ($this->Post_model->delete($id)) {
            $this->session->set_flashdata('success', 'Data user berhasil dihapus.');
        } else {
            $error_message = $this->Post_model->get_last_error();
            $this->session->set_flashdata('error', $error_message ? 'Gagal menghapus user. ' . $error_message : 'Gagal menghapus user.');
        }

        redirect('posts');
    }

    private function _upload_photo()
    {
        $upload_path = $this->photo_directory;

        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0777, TRUE);
        }

        $sanitized_name = preg_replace('/[^A-Za-z0-9._-]/', '_', $_FILES['foto']['name']);
        $config['upload_path'] = $upload_path;
        $config['allowed_types'] = 'jpg|jpeg|png';
        $config['max_size'] = 2048;
        $config['file_name'] = time() . '_' . $sanitized_name;
        $config['overwrite'] = FALSE;

        $this->upload->initialize($config);

        if ($this->upload->do_upload('foto')) {
            $upload_data = $this->upload->data();
            return array(
                'status' => TRUE,
                'file_name' => $upload_data['file_name']
            );
        }

        return array(
            'status' => FALSE,
            'error' => $this->upload->display_errors('', '')
        );
    }

    private function _load_post_model()
    {
        try {
            $this->load->model('Post_model');
            $this->post_model_ready = TRUE;
        } catch (Throwable $exception) {
            log_message('error', 'Post model failed to load: ' . $exception->getMessage());
            $this->post_model_ready = FALSE;
        }
    }

    private function _get_all_posts()
    {
        if (!$this->post_model_ready) {
            return array();
        }

        try {
            return $this->Post_model->get_all();
        } catch (Throwable $exception) {
            log_message('error', 'Failed to fetch posts: ' . $exception->getMessage());
            return array();
        }
    }

    private function _get_post($id)
    {
        if (!$this->post_model_ready) {
            return NULL;
        }

        try {
            return $this->Post_model->get_by_id($id);
        } catch (Throwable $exception) {
            log_message('error', 'Failed to fetch post: ' . $exception->getMessage());
            return NULL;
        }
    }

    private function _require_login()
    {
        if (!$this->session->userdata('login')) {
            $this->session->set_flashdata('error', 'Silakan login terlebih dahulu.');
            redirect('login');
            exit;
        }
    }
}
