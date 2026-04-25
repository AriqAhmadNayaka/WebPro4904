<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Posts extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Post_model');
        $this->require_login();
    }

    private function require_login()
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }
    }

    public function index()
    {
        $data['title'] = 'Data Posts';
        $data['posts'] = $this->Post_model->get_all();

        $this->load->view('posts/index', $data);
    }

    public function create()
    {
        $data['title'] = 'Tambah Post';
        $data['post'] = NULL;
        $data['action'] = base_url('index.php/posts/save');

        $this->load->view('posts/form', $data);
    }

    public function edit($id = NULL)
    {
        $post = $this->Post_model->get_by_id($id);
        if (!$post) {
            show_404();
        }

        $data['title'] = 'Edit Post';
        $data['post'] = $post;
        $data['action'] = base_url('index.php/posts/save/' . $post->id);

        $this->load->view('posts/form', $data);
    }

    public function save($id = NULL)
    {
        $post = NULL;
        if ($id !== NULL) {
            $post = $this->Post_model->get_by_id($id);
            if (!$post) {
                show_404();
            }
        }

        $this->form_validation->set_rules('judul', 'Judul', 'required|trim');
        $this->form_validation->set_rules('deskripsi', 'Deskripsi', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            $data['title'] = $id ? 'Edit Post' : 'Tambah Post';
            $data['post'] = $post;
            $data['action'] = base_url('index.php/posts/save' . ($id ? '/' . $id : ''));

            $this->load->view('posts/form', $data);
            return;
        }

        $save_data = array(
            'judul' => $this->input->post('judul', TRUE),
            'deskripsi' => $this->input->post('deskripsi', TRUE),
        );

        $uploaded_file = $this->do_upload('file');
        if (isset($uploaded_file['error'])) {
            $data['title'] = $id ? 'Edit Post' : 'Tambah Post';
            $data['post'] = $post;
            $data['action'] = base_url('index.php/posts/save' . ($id ? '/' . $id : ''));
            $data['upload_error'] = $uploaded_file['error'];

            $this->load->view('posts/form', $data);
            return;
        }

        if (!empty($uploaded_file['file_name'])) {
            $save_data['file'] = $uploaded_file['file_name'];
            if ($post && !empty($post->file)) {
                $old_file = FCPATH . 'uploads/' . $post->file;
                if (file_exists($old_file)) {
                    unlink($old_file);
                }
            }
        } elseif ($post) {
            $save_data['file'] = $post->file;
        }

        if ($id) {
            $this->Post_model->update($id, $save_data);
            $this->session->set_flashdata('success', 'Data post berhasil diperbarui.');
        } else {
            $this->Post_model->insert($save_data);
            $this->session->set_flashdata('success', 'Data post berhasil disimpan.');
        }

        redirect('posts');
    }

    public function delete($id = NULL)
    {
        $post = $this->Post_model->get_by_id($id);
        if (!$post) {
            show_404();
        }

        if (!empty($post->file)) {
            $file_path = FCPATH . 'uploads/' . $post->file;
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }

        $this->Post_model->delete($id);
        $this->session->set_flashdata('success', 'Data post berhasil dihapus.');
        redirect('posts');
    }

    private function do_upload($field_name)
    {
        if (empty($_FILES[$field_name]['name'])) {
            return array('file_name' => '');
        }

        $config['upload_path'] = FCPATH . 'uploads/';
        $config['allowed_types'] = 'jpg|jpeg|png|gif|pdf|doc|docx';
        $config['max_size'] = 2048;
        $config['encrypt_name'] = TRUE;

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload($field_name)) {
            return array('error' => strip_tags($this->upload->display_errors()));
        }

        return $this->upload->data();
    }
}
