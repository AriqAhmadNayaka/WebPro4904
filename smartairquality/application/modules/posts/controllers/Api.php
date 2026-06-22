<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH.'libraries/REST_Controller.php';

class Api extends REST_Controller {

    public function __construct()
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE');
        header('Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding, Authorization, Accept');
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            exit;
        }

        parent::__construct();
        $this->load->model('Posts_model');
    }

    public function test_get()
    {
        return $this->response(array(
            'status' => TRUE,
            'message' => 'API backend berhasil terhubung!'
        ));
    }

    public function posts_get($id = NULL)
    {
        if ($id !== NULL)
        {
            $post = $this->Posts_model->get_by_id($id);

            if (!$post)
            {
                return $this->response(array(
                    'status' => FALSE,
                    'message' => 'Post not found'
                ), 404);
            }

            return $this->response(array(
                'status' => TRUE,
                'data' => $post
            ));
        }

        return $this->response(array(
            'status' => TRUE,
            'data' => $this->Posts_model->get_all()
        ));
    }

    public function posts_post()
    {
        $data = $this->_post_payload();

        if ($data === FALSE)
        {
            return $this->response(array(
                'status' => FALSE,
                'message' => 'Title, author, and article are required'
            ), 422);
        }

        $id = $this->Posts_model->insert($data);

        return $this->response(array(
            'status' => TRUE,
            'message' => 'Post created successfully',
            'data' => $this->Posts_model->get_by_id($id)
        ), 201);
    }

    public function posts_put($id = NULL)
    {
        if ($id === NULL || !$this->Posts_model->exists($id))
        {
            return $this->response(array(
                'status' => FALSE,
                'message' => 'Post not found'
            ), 404);
        }

        $payload = $this->input_data();
        $data = array('updated_at' => date('Y-m-d H:i:s'));

        foreach (array('title', 'author', 'article', 'image') as $field)
        {
            if (isset($payload[$field]) && $payload[$field] !== '')
            {
                $data[$field] = $payload[$field];
            }
        }

        $this->Posts_model->update($id, $data);

        return $this->response(array(
            'status' => TRUE,
            'message' => 'Post updated successfully',
            'data' => $this->Posts_model->get_by_id($id)
        ));
    }

    public function posts_delete($id = NULL)
    {
        if ($id === NULL)
        {
            return $this->response(array(
                'status' => FALSE,
                'message' => 'Post id is required'
            ), 422);
        }

        $post = $this->Posts_model->get_by_id($id);

        if (!$post)
        {
            return $this->response(array(
                'status' => FALSE,
                'message' => 'Post not found'
            ), 404);
        }

        if (!empty($post->image))
        {
            $image_path = FCPATH.'uploads/'.$post->image;

            if (file_exists($image_path))
            {
                unlink($image_path);
            }
        }

        $this->Posts_model->delete($id);

        return $this->response(array(
            'status' => TRUE,
            'message' => 'Post deleted successfully'
        ));
    }

    private function _post_payload()
    {
        $payload = $this->input_data();

        if (empty($payload['title']) || empty($payload['author']) || empty($payload['article']))
        {
            return FALSE;
        }

        return array(
            'title' => $payload['title'],
            'author' => $payload['author'],
            'article' => $payload['article'],
            'image' => isset($payload['image']) ? $payload['image'] : NULL,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        );
    }
}