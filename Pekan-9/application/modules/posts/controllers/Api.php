<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH.'libraries/REST_Controller.php';

class Api extends REST_Controller {

    public function __construct()
    {
        parent::__construct();
        // Model posts dipakai untuk semua operasi database CRUD.
        $this->load->model('Posts_model');
    }

    public function posts_get($id = NULL)
    {
        // GET /api/posts/{id} mengambil satu data berdasarkan id.
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

        // GET /api/posts mengambil semua data posts.
        return $this->response(array(
            'status' => TRUE,
            'data' => $this->Posts_model->get_all()
        ));
    }

    public function posts_post()
    {
        // POST /api/posts membuat data baru dari body JSON.
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
        // PUT /api/posts/{id} mengubah data yang id-nya dikirim di URL.
        if ($id === NULL || !$this->Posts_model->exists($id))
        {
            return $this->response(array(
                'status' => FALSE,
                'message' => 'Post not found'
            ), 404);
        }

        $payload = $this->input_data();
        $data = array('updated_at' => date('Y-m-d H:i:s'));

        // Hanya field yang dikirim client yang akan di-update.
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
        // DELETE /api/posts/{id} menghapus data berdasarkan id.
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
            // Jika post memiliki gambar, file gambarnya ikut dihapus dari folder uploads.
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
        // Validasi sederhana untuk memastikan field wajib tidak kosong.
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
