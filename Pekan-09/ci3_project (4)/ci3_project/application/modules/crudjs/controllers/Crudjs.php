<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Crudjs extends MX_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Crudjs_model');
        $this->load->library('upload');
        $this->load->helper(array('url', 'form'));
    }

    public function index()
    {
        $this->load->view('index');
    }

    public function list()
    {
        $this->json(array('status' => TRUE, 'data' => $this->Crudjs_model->get_all()));
    }

    public function get($id)
    {
        $post = $this->Crudjs_model->get_by_id($id);

        if (!$post) {
            $this->json(array('status' => FALSE, 'message' => 'Post not found'), 404);
            return;
        }

        $this->json(array('status' => TRUE, 'data' => $post));
    }

    public function store()
    {
        $data = $this->payload();

        if (empty($data['title']) || empty($data['author']) || empty($data['article'])) {
            $this->json(array('status' => FALSE, 'message' => 'Title, author, and article are required'), 400);
            return;
        }

        if (!empty($_FILES['image']['name'])) {
            $image = $this->upload_image();

            if ($image === FALSE) {
                $this->json(array('status' => FALSE, 'message' => $this->upload->display_errors('', '')), 422);
                return;
            }

            $data['image'] = $image;
        }

        $id = $this->Crudjs_model->insert($data);
        $this->json(array('status' => TRUE, 'message' => 'Post created', 'data' => $this->Crudjs_model->get_by_id($id)));
    }

    public function update($id)
    {
        $post = $this->Crudjs_model->get_by_id($id);

        if (!$post) {
            $this->json(array('status' => FALSE, 'message' => 'Post not found'), 404);
            return;
        }

        $data = $this->payload(FALSE);

        if (!empty($_FILES['image']['name'])) {
            $image = $this->upload_image();

            if ($image === FALSE) {
                $this->json(array('status' => FALSE, 'message' => $this->upload->display_errors('', '')), 422);
                return;
            }

            $this->delete_image($post->image);
            $data['image'] = $image;
        }

        $this->Crudjs_model->update($id, $data);
        $this->json(array('status' => TRUE, 'message' => 'Post updated', 'data' => $this->Crudjs_model->get_by_id($id)));
    }

    public function delete($id)
    {
        $post = $this->Crudjs_model->get_by_id($id);

        if (!$post) {
            $this->json(array('status' => FALSE, 'message' => 'Post not found'), 404);
            return;
        }

        $this->Crudjs_model->delete($id);
        $this->delete_image($post->image);
        $this->json(array('status' => TRUE, 'message' => 'Post deleted'));
    }

    private function payload($with_created_at = TRUE)
    {
        $data = array(
            'title' => $this->input->post('title', TRUE),
            'author' => $this->input->post('author', TRUE),
            'article' => $this->input->post('article', TRUE),
            'updated_at' => date('Y-m-d H:i:s')
        );

        if ($with_created_at) {
            $data['created_at'] = date('Y-m-d H:i:s');
        }

        return $data;
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

    private function delete_image($image)
    {
        if (!$image) {
            return;
        }

        $path = FCPATH . 'uploads/posts/' . basename($image);

        if (is_file($path)) {
            unlink($path);
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
