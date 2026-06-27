<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Crudjs extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Crudjs/Crudjs_model');
        $this->load->helper(array('url', 'form'));
        $this->load->library(array('form_validation', 'upload'));
    }

    public function index()
    {
        $data['title'] = 'CRUD AJAX';
        $this->load->view('index', $data);
    }

    public function get_posts()
    {
        $this->_json(array(
            'status' => TRUE,
            'data' => $this->Crudjs_model->get_all()
        ));
    }

    public function get_post($id)
    {
        $post = $this->Crudjs_model->get_by_id($id);

        if (!$post) {
            $this->_json(array('status' => FALSE, 'message' => 'Post not found'), 404);
            return;
        }

        $this->_json(array('status' => TRUE, 'data' => $post));
    }

    public function create()
    {
        $this->form_validation->set_rules('title', 'Title', 'required|max_length[255]');
        $this->form_validation->set_rules('author', 'Author', 'required|max_length[255]');
        $this->form_validation->set_rules('article', 'Article', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->_json(array('status' => FALSE, 'message' => strip_tags(validation_errors())), 422);
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
            $upload = $this->_upload_image();
            if (!$upload['status']) {
                $this->_json(array('status' => FALSE, 'message' => $upload['error']), 422);
                return;
            }
            $data['image'] = 'posts/' . $upload['file_name'];
        }

        $id = $this->Crudjs_model->insert($data);
        $this->_json(array(
            'status' => (bool) $id,
            'message' => $id ? 'Post created successfully' : 'Failed to create post'
        ), $id ? 201 : 500);
    }

    public function update($id)
    {
        $post = $this->Crudjs_model->get_by_id($id);

        if (!$post) {
            $this->_json(array('status' => FALSE, 'message' => 'Post not found'), 404);
            return;
        }

        $this->form_validation->set_rules('title', 'Title', 'required|max_length[255]');
        $this->form_validation->set_rules('author', 'Author', 'required|max_length[255]');
        $this->form_validation->set_rules('article', 'Article', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->_json(array('status' => FALSE, 'message' => strip_tags(validation_errors())), 422);
            return;
        }

        $data = array(
            'title' => $this->input->post('title'),
            'author' => $this->input->post('author'),
            'article' => $this->input->post('article'),
            'updated_at' => date('Y-m-d H:i:s')
        );

        if (!empty($_FILES['image']['name'])) {
            if ($post->image && file_exists('./uploads/' . $post->image)) {
                unlink('./uploads/' . $post->image);
            }

            $upload = $this->_upload_image();
            if (!$upload['status']) {
                $this->_json(array('status' => FALSE, 'message' => $upload['error']), 422);
                return;
            }
            $data['image'] = 'posts/' . $upload['file_name'];
        }

        $result = $this->Crudjs_model->update($id, $data);
        $this->_json(array(
            'status' => (bool) $result,
            'message' => $result ? 'Post updated successfully' : 'Failed to update post'
        ));
    }

    public function delete($id)
    {
        $post = $this->Crudjs_model->get_by_id($id);

        if (!$post) {
            $this->_json(array('status' => FALSE, 'message' => 'Post not found'), 404);
            return;
        }

        if ($post->image && file_exists('./uploads/' . $post->image)) {
            unlink('./uploads/' . $post->image);
        }

        $result = $this->Crudjs_model->delete($id);
        $this->_json(array(
            'status' => (bool) $result,
            'message' => $result ? 'Post deleted successfully' : 'Failed to delete post'
        ));
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
            return array('status' => TRUE, 'file_name' => $this->upload->data('file_name'));
        }

        return array('status' => FALSE, 'error' => $this->upload->display_errors('', ''));
    }

    private function _json($data, $status_code = 200)
    {
        return $this->output
            ->set_status_header($status_code)
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }
}
