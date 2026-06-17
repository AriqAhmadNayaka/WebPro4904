<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Post extends MX_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Post_model');
        $this->load->library(array('jwt', 'upload'));
    }

    public function handle($id = null)
    {
        $method = $_SERVER['REQUEST_METHOD'];

        if ($method === 'GET') {
            $id ? $this->show($id) : $this->index();
        } elseif ($method === 'POST') {
            $this->store();
        } elseif ($method === 'PUT') {
            $this->update($id);
        } elseif ($method === 'DELETE') {
            $this->delete($id);
        } else {
            $this->json(array('status' => FALSE, 'message' => 'Method not allowed'), 405);
        }
    }

    private function index()
    {
        $this->json(array('status' => TRUE, 'data' => $this->Post_model->get_all()));
    }

    private function show($id)
    {
        $post = $this->Post_model->get_by_id($id);

        if (!$post) {
            $this->json(array('status' => FALSE, 'message' => 'Post not found'), 404);
            return;
        }

        $this->json(array('status' => TRUE, 'data' => $post));
    }

    private function store()
    {
        if (!$this->authorized()) {
            return;
        }

        $input = $this->request_input();

        if (empty($input['title']) || empty($input['author']) || empty($input['article'])) {
            $this->json(array('status' => FALSE, 'message' => 'Title, author, and article are required'), 400);
            return;
        }

        $data = array(
            'title' => $input['title'],
            'author' => $input['author'],
            'article' => $input['article'],
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        );

        if (!empty($_FILES['image']['name']) || !empty($_FILES['gambar']['name'])) {
            $this->normalize_upload_field();
            $image = $this->upload_image();

            if ($image === FALSE) {
                $this->json(array('status' => FALSE, 'message' => $this->upload->display_errors('', '')), 422);
                return;
            }

            $data['image'] = $image;
        }

        $id = $this->Post_model->insert($data);
        $this->json(array('status' => TRUE, 'message' => 'Post created', 'data' => $this->Post_model->get_by_id($id)), 201);
    }

    private function update($id)
    {
        if (!$this->authorized()) {
            return;
        }

        $post = $this->Post_model->get_by_id($id);

        if (!$post) {
            $this->json(array('status' => FALSE, 'message' => 'Post not found'), 404);
            return;
        }

        $input = $this->request_input();
        $data = array('updated_at' => date('Y-m-d H:i:s'));

        foreach (array('title', 'author', 'article') as $field) {
            if (isset($input[$field])) {
                $data[$field] = $input[$field];
            }
        }

        $this->Post_model->update($id, $data);
        $this->json(array('status' => TRUE, 'message' => 'Post updated', 'data' => $this->Post_model->get_by_id($id)));
    }

    private function delete($id)
    {
        if (!$this->authorized()) {
            return;
        }

        $post = $this->Post_model->get_by_id($id);

        if (!$post) {
            $this->json(array('status' => FALSE, 'message' => 'Post not found'), 404);
            return;
        }

        $this->Post_model->delete($id);
        $this->json(array('status' => TRUE, 'message' => 'Post deleted'));
    }

    private function authorized()
    {
        $token = $this->jwt->get_token_from_request();

        if (!$token || !$this->jwt->verify($token)) {
            $this->json(array('status' => FALSE, 'message' => 'Unauthorized'), 401);
            return FALSE;
        }

        return TRUE;
    }

    private function request_input()
    {
        $raw = file_get_contents('php://input');
        $json = json_decode($raw, TRUE);

        if (is_array($json)) {
            return $json;
        }

        parse_str($raw, $parsed);
        return $parsed ? $parsed : $this->input->post();
    }

    private function upload_image()
    {
        $upload_path = FCPATH . 'uploads/posts/';

        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0777, TRUE);
        }

        $config['upload_path'] = $upload_path;
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
        $config['max_size'] = 2048;
        $config['file_name'] = 'post_' . time() . '_' . uniqid();

        $this->upload->initialize($config);

        if (!$this->upload->do_upload('image')) {
            return FALSE;
        }

        return $this->upload->data('file_name');
    }

    private function normalize_upload_field()
    {
        if (empty($_FILES['image']['name']) && !empty($_FILES['gambar']['name'])) {
            $_FILES['image'] = $_FILES['gambar'];
        }
    }

    private function json($data, $status = 200)
    {
        $this->output
            ->set_status_header($status)
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode($data));
    }
}
