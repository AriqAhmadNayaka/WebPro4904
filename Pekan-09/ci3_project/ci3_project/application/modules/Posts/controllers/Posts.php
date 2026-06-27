<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Posts extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Posts/Post_model');
        $this->load->helper(array('url', 'form'));
        $this->load->library(array('form_validation', 'upload', 'session'));
    }

    public function index()
    {
        $data['posts'] = $this->Post_model->get_all();
        $data['title'] = 'All Posts';
        $this->load->view('index', $data);
    }

    public function create()
    {
        $data['title'] = 'Create New Post';
        $this->load->view('create', $data);
    }

    public function store()
    {
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

        if ($this->Post_model->insert($data)) {
            $this->session->set_flashdata('success', 'Post created successfully!');
            redirect('posts');
        }

        $this->session->set_flashdata('error', 'Failed to create post');
        redirect('posts/create');
    }

    public function show($id)
    {
        $post = $this->Post_model->get_by_id($id);

        if (!$post) {
            show_404();
            return;
        }

        $data['post'] = $post;
        $data['title'] = $post->title;
        $this->load->view('show', $data);
    }

    public function edit($id)
    {
        $post = $this->Post_model->get_by_id($id);

        if (!$post) {
            show_404();
            return;
        }

        $data['post'] = $post;
        $data['title'] = 'Edit Post';
        $this->load->view('edit', $data);
    }

    public function update($id)
    {
        if (!$this->Post_model->exists($id)) {
            show_404();
            return;
        }

        $this->form_validation->set_rules('title', 'Title', 'required|max_length[255]');
        $this->form_validation->set_rules('author', 'Author', 'required|max_length[255]');
        $this->form_validation->set_rules('article', 'Article', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('posts/edit/' . $id);
            return;
        }

        $data = array(
            'title' => $this->input->post('title'),
            'author' => $this->input->post('author'),
            'article' => $this->input->post('article'),
            'updated_at' => date('Y-m-d H:i:s')
        );

        if (!empty($_FILES['image']['name'])) {
            $old_post = $this->Post_model->get_by_id($id);
            if ($old_post && $old_post->image) {
                $old_image_path = './uploads/' . $old_post->image;
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

        if ($this->Post_model->update($id, $data)) {
            $this->session->set_flashdata('success', 'Post updated successfully!');
            redirect('posts');
        }

        $this->session->set_flashdata('error', 'Failed to update post');
        redirect('posts/edit/' . $id);
    }

    public function delete($id)
    {
        $post = $this->Post_model->get_by_id($id);

        if (!$post) {
            $this->session->set_flashdata('error', 'Post not found');
            redirect('posts');
            return;
        }

        if ($post->image) {
            $image_path = './uploads/' . $post->image;
            if (file_exists($image_path)) {
                unlink($image_path);
            }
        }

        if ($this->Post_model->delete($id)) {
            $this->session->set_flashdata('success', 'Post deleted successfully!');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete post');
        }

        redirect('posts');
    }

    private function _upload_image()
    {
        $upload_path = './uploads/posts/';

        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0777, TRUE);
        }

        $original_name = $_FILES['image']['name'];
        $sanitized_name = preg_replace('/[^A-Za-z0-9._-]/', '_', $original_name);
        $file_name = time() . '_' . $sanitized_name;

        $config['upload_path'] = $upload_path;
        $config['allowed_types'] = 'jpg|jpeg|png';
        $config['max_size'] = 2048;
        $config['file_name'] = $file_name;
        $config['overwrite'] = FALSE;

        $this->upload->initialize($config);

        if ($this->upload->do_upload('image')) {
            $upload_data = $this->upload->data();
            return array('status' => TRUE, 'file_name' => $upload_data['file_name']);
        }

        return array('status' => FALSE, 'error' => $this->upload->display_errors('', ''));
    }
}
