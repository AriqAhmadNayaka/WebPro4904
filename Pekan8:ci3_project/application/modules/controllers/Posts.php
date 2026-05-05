<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Posts extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Post_model');
        $this->load->helper(array('url', 'form'));
        $this->load->library(array('form_validation', 'upload', 'session'));
    }

    public function index() {
        $data['posts'] = $this->Post_model->get_all();
        $data['title'] = 'All Posts';
        $this->load->view('posts/index', $data);
    }

    public function create() {
        $data['title'] = 'Create New Post';
        $this->load->view('posts/create', $data);
    }

    public function store() {
        $this->form_validation->set_rules('title', 'Title', 'required|max_length[255]');
        $this->form_validation->set_rules('author', 'Author', 'required|max_length[255]');
        $this->form_validation->set_rules('article', 'Article', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('posts/create');
            return;
        }

        $data = array(
            'title' => $this->input->post('title'),
            'author' => $this->input->post('author'),
            'article' => $this->input->post('article'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        );
        if ($empty($_FILES['image']['name'])) {
            $upload_result = $this->upload_image();
            if ($upload_result['status']) {
                $data['image'] = 'posts/' . $upload_result['file_name'];
            } else {
                $this->session->set_flashdata('error', $upload_result['error']);
                redirect('posts/create');
                return;
            }
        }

        $post_id = $this->Post_model->insert($data);
        if ($post_id) {
            $this->session->set_flashdata('success', 'Post created successfully.');
            redirect('posts');
        } else {
            $this->session->set_flashdata('error', 'Failed to create post. Please try again.');
            redirect('posts/create');
        }
    }

    public function show($id) {
        $post = $this->Post_model->get_by_id($id);
        if (!$post) {
            show_404();
            return;
        }
        $data['post'] = $post;
        $data['title'] = $post->title;
        $this->load->view('posts/show', $data);
    }

    public function edit($id) {
        $post = $this->Post_model->get_by_id($id);
        if (!$post) {
            show_404();
            return;
        }
        $data['post'] = $post;
        $data['title'] = 'Edit Post';
        $this->load->view('posts/edit', $data);
    }

    public function update($id) {
        if (!$this->Post_model->exists($id)) {
            show_404();
            return;
        }

        $this->form_validation->set_rules('title', 'Title', 'required|max_length[255]');
        $this->form_validation->set_rules('author', 'Author', 'required|max_length[255]');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('posts/edit/' . $id);
            return;
        }

        $data = array(
            'updated_at' => date('Y-m-d H:i:s')
        );
    
        if ($this->input->post('title')) {
            $data['title'] = $this->input->post('title');
        }
        if ($this->input->post('author')) {
            $data['author'] = $this->input->post('author');
        }
        if ($this->input->post('article')) {
            $data['article'] = $this->input->post('article');
        }

        if (!empty($_FILES['image']['name'])) {
            $old_post = $this->Post_model->get_by_id($id);
            if ($old_post->image) {
                $old_image_path = FCPATH . 'uploads/' . $old_post->image;
                if (file_exists($old_image_path)) {
                    unlink($old_image_path);
                }
            }

            $upload_result = $this->upload_image();
            if ($upload_result['status']) {
                $data['image'] = 'posts/' . $upload_result['file_name'];
            } else {
                $this->session->set_flashdata('error', $upload_result['error']);
                redirect('posts/edit/' . $id);
                return;
            }
        }

        $result = $this->Post_model->update($id, $data);
        if ($result) {
            $this->session->set_flashdata('success', 'Post updated successfully.');
            redirect('posts');
        } else {
            $this->session->set_flashdata('error', 'Failed to update post. Please try again.');
            redirect('posts/edit/' . $id);
        }
    }

    public function delete($id) {
        $post = $this->Post_model->get_by_id($id);
        if (!$post) {
            $this->session->set_flashdata('error', 'Post not found.');
            redirect('posts');
            return;
        }

        if ($post->image) {
            $image_path = FCPATH . 'uploads/' . $post->image;
            if (file_exists($image_path)) {
                unlink($image_path);
            }
        }

        $result = $this->Post_model->delete($id);
        if ($result) {
            $this->session->set_flashdata('success', 'Post deleted successfully.');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete post. Please try again.');
        }
        redirect('posts');
    }

    private function _upload_image() {
        $upload_path = './uploads/posts/';
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0777, true);
        }

        $original_name = $_FILES['image']['name'];
        $sanitized_name = preg_replace('/[^A-zA-Z0-9_-]/', '_', $original_name);
        $file_name = time() . '_' . $sanitized_name;

        $config['upload_path'] = $upload_path;
        $config['allowed_types'] = 'jpg|jpeg|png';
        $config['max_size'] = 2048;
        $config['file_name'] = $file_name;
        $config['overwrite'] = FALSE;

        $this->upload->initialize($config);
        if ($this->upload->do_upload('image')) {
            $upload_data = $this->upload->data();
            return array('status' => true, 'file_name' => $upload_data['file_name']);
        } else {
            return array('status' => false, 'error' => $this->upload->display_errors('', ''));
        }
    }
}