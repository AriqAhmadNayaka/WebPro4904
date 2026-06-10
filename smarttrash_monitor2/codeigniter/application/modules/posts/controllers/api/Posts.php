<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Posts extends MY_Controller
{
    private $image_extensions = array('jpg', 'jpeg', 'png', 'gif', 'webp');

    public function __construct()
    {
        parent::__construct();
        $this->load->model('posts/Posts_model');
    }

    public function index($id = NULL)
    {
        $method = $this->request_method();

        if ($method === 'GET') {
            return $this->handle_get($id);
        }

        if ($method === 'POST') {
            return $this->handle_post();
        }

        if ($method === 'PUT') {
            return $this->handle_put($id);
        }

        if ($method === 'DELETE') {
            return $this->handle_delete($id);
        }

        return $this->json_response(array(
            'status' => FALSE,
            'message' => 'Method not allowed'
        ), 405);
    }

    private function handle_get($id = NULL)
    {
        if ($id !== NULL) {
            $post = $this->Posts_model->get_by_id($id);

            if (!$post) {
                return $this->json_response(array(
                    'status' => FALSE,
                    'message' => 'Data tidak ditemukan'
                ), 404);
            }

            return $this->json_response(array(
                'status' => TRUE,
                'data' => $this->format_post($post)
            ));
        }

        $posts = $this->Posts_model->get_all();
        $formatted_posts = array_map(array($this, 'format_post'), $posts);

        return $this->json_response(array(
            'status' => TRUE,
            'data' => $formatted_posts
        ));
    }

    private function handle_post()
    {
        $payload = $this->request_data();
        $validation = $this->validate_payload($payload);

        if ($validation !== TRUE) {
            return $this->json_response(array(
                'status' => FALSE,
                'message' => $validation
            ), 422);
        }

        $data = array(
            'title' => trim($payload['title']),
            'content' => trim($payload['content'])
        );

        $uploaded_file = $this->do_upload('file');
        if (isset($uploaded_file['error'])) {
            return $this->json_response(array(
                'status' => FALSE,
                'message' => $uploaded_file['error']
            ), 422);
        }

        if (!empty($uploaded_file['file_name'])) {
            $data['file'] = $uploaded_file['file_name'];
        }

        $insert_id = $this->Posts_model->insert($data);

        return $this->json_response(array(
            'status' => TRUE,
            'message' => 'Post berhasil ditambahkan',
            'data' => $this->format_post($this->Posts_model->get_by_id($insert_id))
        ), 201);
    }

    private function handle_put($id = NULL)
    {
        $post = $id === NULL ? NULL : $this->Posts_model->get_by_id($id);

        if (!$post) {
            return $this->json_response(array(
                'status' => FALSE,
                'message' => 'Data tidak ditemukan'
            ), 404);
        }

        $payload = $this->request_data();
        $validation = $this->validate_payload($payload);

        if ($validation !== TRUE) {
            return $this->json_response(array(
                'status' => FALSE,
                'message' => $validation
            ), 422);
        }

        $data = array(
            'title' => trim($payload['title']),
            'content' => trim($payload['content'])
        );

        $uploaded_file = $this->do_upload('file');
        if (isset($uploaded_file['error'])) {
            return $this->json_response(array(
                'status' => FALSE,
                'message' => $uploaded_file['error']
            ), 422);
        }

        if (!empty($uploaded_file['file_name'])) {
            $data['file'] = $uploaded_file['file_name'];
            if (!empty($post['file'])) {
                $this->delete_uploaded_file($post['file']);
            }
        } else {
            $data['file'] = $post['file'];
        }

        $this->Posts_model->update($id, $data);

        return $this->json_response(array(
            'status' => TRUE,
            'message' => 'Post berhasil diperbarui',
            'data' => $this->format_post($this->Posts_model->get_by_id($id))
        ));
    }

    private function handle_delete($id = NULL)
    {
        $post = $id === NULL ? NULL : $this->Posts_model->get_by_id($id);

        if (!$post) {
            return $this->json_response(array(
                'status' => FALSE,
                'message' => 'Data tidak ditemukan'
            ), 404);
        }

        if (!empty($post['file'])) {
            $this->delete_uploaded_file($post['file']);
        }

        $this->Posts_model->delete($id);

        return $this->json_response(array(
            'status' => TRUE,
            'message' => 'Post berhasil dihapus'
        ));
    }

    private function validate_payload($payload)
    {
        $title = isset($payload['title']) ? trim($payload['title']) : '';
        $content = isset($payload['content']) ? trim($payload['content']) : '';

        if ($title === '') {
            return 'Title wajib diisi';
        }

        if ($content === '') {
            return 'Content wajib diisi';
        }

        return TRUE;
    }

    private function do_upload($field_name)
    {
        if (empty($_FILES[$field_name]['name'])) {
            return array('file_name' => '');
        }

        $config['upload_path'] = FCPATH . 'uploads/posts/';
        $config['allowed_types'] = 'jpg|jpeg|png|gif|webp';
        $config['max_size'] = 2048;
        $config['encrypt_name'] = TRUE;

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload($field_name)) {
            return array('error' => strip_tags($this->upload->display_errors()));
        }

        return $this->upload->data();
    }

    private function delete_uploaded_file($file_name)
    {
        $file_path = FCPATH . 'uploads/posts/' . $file_name;

        if (is_file($file_path)) {
            @unlink($file_path);
        }
    }

    private function format_post($post)
    {
        if (!$post) {
            return NULL;
        }

        $post['file_url'] = !empty($post['file']) ? base_url('uploads/posts/' . $post['file']) : NULL;
        $post['is_image'] = !empty($post['file']) && in_array(strtolower(pathinfo($post['file'], PATHINFO_EXTENSION)), $this->image_extensions, TRUE);

        return $post;
    }
}
