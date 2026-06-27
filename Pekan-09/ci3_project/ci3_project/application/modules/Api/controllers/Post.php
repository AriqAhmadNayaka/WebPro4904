<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Post extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Api/Post_model');
        $this->load->library('Jwt');
        $this->load->library('upload');
    }

    public function index()
    {
        if (!$this->_auth_payload()) {
            return;
        }

        $this->_json(array(
            'status' => TRUE,
            'message' => 'Posts data',
            'data' => $this->Post_model->get_all()
        ));
    }

    public function show($id)
    {
        if (!$this->_auth_payload()) {
            return;
        }

        $post = $this->Post_model->get_by_id($id);

        if (!$post) {
            $this->_json(array('status' => FALSE, 'message' => 'Post not found'), 404);
            return;
        }

        $this->_json(array(
            'status' => TRUE,
            'message' => 'Post detail',
            'data' => $post
        ));
    }

    public function create()
    {
        if (!$this->_auth_payload()) {
            return;
        }

        $input = $this->_input();
        $validation = $this->_validate($input);

        if ($validation !== TRUE) {
            $this->_json(array('status' => FALSE, 'message' => $validation), 422);
            return;
        }

        $data = array(
            'title' => trim($input['title']),
            'author' => trim($input['author']),
            'article' => trim($input['article']),
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

        $id = $this->Post_model->insert($data);

        $this->_json(array(
            'status' => (bool) $id,
            'message' => $id ? 'Post created successfully' : 'Failed to create post',
            'data' => $id ? $this->Post_model->get_by_id($id) : null
        ), $id ? 201 : 500);
    }

    public function update($id)
    {
        if (!$this->_auth_payload()) {
            return;
        }

        if (!$this->Post_model->exists($id)) {
            $this->_json(array('status' => FALSE, 'message' => 'Post not found'), 404);
            return;
        }

        $input = $this->_input();
        $validation = $this->_validate($input);

        if ($validation !== TRUE) {
            $this->_json(array('status' => FALSE, 'message' => $validation), 422);
            return;
        }

        $data = array(
            'title' => trim($input['title']),
            'author' => trim($input['author']),
            'article' => trim($input['article']),
            'updated_at' => date('Y-m-d H:i:s')
        );

        if (!empty($_FILES['image']['name'])) {
            $old_post = $this->Post_model->get_by_id($id);

            if ($old_post && $old_post->image && file_exists('./uploads/' . $old_post->image)) {
                unlink('./uploads/' . $old_post->image);
            }

            $upload = $this->_upload_image();

            if (!$upload['status']) {
                $this->_json(array('status' => FALSE, 'message' => $upload['error']), 422);
                return;
            }

            $data['image'] = 'posts/' . $upload['file_name'];
        }

        $result = $this->Post_model->update($id, $data);

        $this->_json(array(
            'status' => (bool) $result,
            'message' => $result ? 'Post updated successfully' : 'Failed to update post',
            'data' => $result ? $this->Post_model->get_by_id($id) : null
        ));
    }

    public function delete($id)
    {
        if (!$this->_auth_payload()) {
            return;
        }

        if (!$this->Post_model->exists($id)) {
            $this->_json(array('status' => FALSE, 'message' => 'Post not found'), 404);
            return;
        }

        $post = $this->Post_model->get_by_id($id);

        if ($post && $post->image && file_exists('./uploads/' . $post->image)) {
            unlink('./uploads/' . $post->image);
        }

        $result = $this->Post_model->delete($id);

        $this->_json(array(
            'status' => (bool) $result,
            'message' => $result ? 'Post deleted successfully' : 'Failed to delete post'
        ));
    }

    private function _auth_payload()
    {
        $token = $this->jwt->get_token_from_header();

        if (!$token) {
            $this->_json(array('status' => FALSE, 'message' => 'Bearer token is required'), 401);
            return FALSE;
        }

        $payload = $this->jwt->decode($token);

        if (!$payload) {
            $this->_json(array('status' => FALSE, 'message' => 'Token is invalid or expired'), 401);
            return FALSE;
        }

        return $payload;
    }

    private function _input()
    {
        $raw = $this->input->raw_input_stream;
        $json = json_decode($raw, TRUE);

        if (is_array($json)) {
            return $json;
        }

        return $this->input->post();
    }

    private function _validate($input)
    {
        if (!isset($input['title']) || trim($input['title']) === '') {
            return 'Title is required';
        }

        if (!isset($input['author']) || trim($input['author']) === '') {
            return 'Author is required';
        }

        if (!isset($input['article']) || trim($input['article']) === '') {
            return 'Article is required';
        }

        return TRUE;
    }

    private function _upload_image()
    {
        $upload_path = './uploads/posts/';

        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0777, TRUE);
        }

        $original_name = $_FILES['image']['name'];
        $sanitized_name = preg_replace('/[^A-Za-z0-9._-]/', '_', $original_name);
        $file_name = 'post_' . time() . '_' . $sanitized_name;

        $config['upload_path'] = $upload_path;
        $config['allowed_types'] = 'jpg|jpeg|png|gif';
        $config['max_size'] = 2048;
        $config['file_name'] = $file_name;
        $config['overwrite'] = FALSE;

        $this->upload->initialize($config);

        if ($this->upload->do_upload('image')) {
            return array(
                'status' => TRUE,
                'file_name' => $this->upload->data('file_name')
            );
        }

        return array(
            'status' => FALSE,
            'error' => $this->upload->display_errors('', '')
        );
    }

    private function _json($data, $status_code = 200)
    {
        return $this->output
            ->set_status_header($status_code)
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }
}
