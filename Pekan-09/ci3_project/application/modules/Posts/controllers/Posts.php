<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Posts extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Posts_model');
        $this->load->helper(array('url', 'form'));
        $this->load->library(array('session', 'upload', 'form_validation'));
        $this->Posts_model->ensure_table();
    }

    public function index()
    {
        $data['title'] = 'Posts HMVC';
        $data['posts'] = $this->Posts_model->get_all();

        $this->load->view('posts/index', $data);
    }

    public function create()
    {
        $this->load->view('posts/form', array(
            'title' => 'Tambah Post',
            'post' => null,
            'action' => site_url('posts/store'),
        ));
    }

    public function store()
    {
        if ($this->input->method() !== 'post') {
            redirect('posts');
            return;
        }

        $this->form_validation->set_rules('title', 'Title', 'required|max_length[255]');
        $this->form_validation->set_rules('author', 'Author', 'required|max_length[255]');
        $this->form_validation->set_rules('article', 'Article', 'required');

        if ($this->form_validation->run() === false) {
            $this->create();
            return;
        }

        $data = array(
            'title' => $this->input->post('title', true),
            'author' => $this->input->post('author', true),
            'article' => $this->input->post('article', true),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        );

        if (!empty($_FILES['image']['name'])) {
            $upload = $this->upload_image();
            if (!$upload['status']) {
                $this->session->set_flashdata('error', $upload['error']);
                $this->create();
                return;
            }

            $data['image'] = 'posts/' . $upload['file_name'];
        }

        $this->Posts_model->insert($data);
        $this->session->set_flashdata('success', 'Post berhasil ditambahkan.');
        redirect('posts');
    }

    public function edit($id)
    {
        $post = $this->Posts_model->get_by_id($id);

        if (!$post) {
            show_404();
            return;
        }

        $this->load->view('posts/form', array(
            'title' => 'Edit Post',
            'post' => $post,
            'action' => site_url('posts/update/' . $post->id),
        ));
    }

    public function update($id)
    {
        if ($this->input->method() !== 'post') {
            redirect('posts');
            return;
        }

        $post = $this->Posts_model->get_by_id($id);

        if (!$post) {
            show_404();
            return;
        }

        $this->form_validation->set_rules('title', 'Title', 'required|max_length[255]');
        $this->form_validation->set_rules('author', 'Author', 'required|max_length[255]');
        $this->form_validation->set_rules('article', 'Article', 'required');

        if ($this->form_validation->run() === false) {
            $this->edit($id);
            return;
        }

        $data = array(
            'title' => $this->input->post('title', true),
            'author' => $this->input->post('author', true),
            'article' => $this->input->post('article', true),
            'updated_at' => date('Y-m-d H:i:s'),
        );

        if (!empty($_FILES['image']['name'])) {
            $upload = $this->upload_image();
            if (!$upload['status']) {
                $this->session->set_flashdata('error', $upload['error']);
                $this->edit($id);
                return;
            }

            $this->delete_image($post);
            $data['image'] = 'posts/' . $upload['file_name'];
        }

        $this->Posts_model->update($id, $data);
        $this->session->set_flashdata('success', 'Post berhasil diperbarui.');
        redirect('posts');
    }

    public function delete($id)
    {
        if ($this->input->method() !== 'post') {
            redirect('posts');
            return;
        }

        $post = $this->Posts_model->get_by_id($id);

        if ($post) {
            $this->delete_image($post);
            $this->Posts_model->delete($id);
            $this->session->set_flashdata('success', 'Post berhasil dihapus.');
        }

        redirect('posts');
    }

    private function upload_image()
    {
        $upload_path = './uploads/posts/';

        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0777, true);
        }

        $safe_name = preg_replace('/[^A-Za-z0-9._-]/', '_', $_FILES['image']['name']);

        $config = array(
            'upload_path' => $upload_path,
            'allowed_types' => 'jpg|jpeg|png',
            'max_size' => 2048,
            'file_name' => time() . '_' . $safe_name,
            'overwrite' => false,
        );

        $this->upload->initialize($config);

        if ($this->upload->do_upload('image')) {
            $upload_data = $this->upload->data();
            return array('status' => true, 'file_name' => $upload_data['file_name']);
        }

        return array('status' => false, 'error' => $this->upload->display_errors('', ''));
    }

    private function delete_image($post)
    {
        if (!empty($post->image)) {
            $path = './uploads/' . $post->image;
            if (file_exists($path)) {
                unlink($path);
            }
        }
    }
}
