<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Post extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Post_model');
        $this->load->library('jwt');
        $this->load->library('upload');
        $this->Post_model->ensure_table();
    }

    /**
     * Parse multipart/form-data from php://input for PUT/DELETE requests.
     */
    private function parse_multipart_form()
    {
        $content_type = $_SERVER['CONTENT_TYPE'] ?? '';
        $input = array();

        if (strpos($content_type, 'multipart/form-data') !== false) {
            $boundary = '';
            if (preg_match('/boundary=(?:[^;]+;)?(.+)/', $content_type, $matches)) {
                $boundary = trim($matches[1], '"');
            }

            if (!$boundary) {
                return array();
            }

            $raw = file_get_contents('php://input');
            $parts = preg_split('/--' . preg_quote($boundary, '/') . '/', $raw);

            foreach ($parts as $part) {
                if (empty(trim($part)) || $part === "--\r\n" || $part === "--") {
                    continue;
                }

                $split = preg_split("/\r\n\r\n/", trim($part), 2);
                if (count($split) !== 2) {
                    continue;
                }

                $headers = $split[0];
                $content = rtrim($split[1], "\r\n");

                if (preg_match('/name="([^"]+)"/', $headers, $matches)) {
                    $name = $matches[1];

                    if (preg_match('/filename="([^"]+)"/', $headers, $filename_matches)) {
                        $file_content_type = 'application/octet-stream';
                        if (preg_match('/Content-Type:\s*([^\r\n]+)/', $headers, $type_matches)) {
                            $file_content_type = trim($type_matches[1]);
                        }

                        $input[$name] = array(
                            'name' => $filename_matches[1],
                            'type' => $file_content_type,
                            'content' => $content,
                        );
                    } else {
                        $input[$name] = $content;
                    }
                }
            }
        }

        return $input;
    }

    public function index()
    {
        $this->authorize();
        $records = $this->Post_model->get_all();
        $this->json(array(
            'status' => true,
            'message' => 'Posts retrieved successfully',
            'data' => $records,
        ));
    }

    public function show($id)
    {
        $this->authorize();
        $record = $this->Post_model->get_by_id($id);

        if (!$record) {
            return $this->json(array(
                'status' => false,
                'message' => 'Post not found',
            ), 404);
        }

        $this->json(array(
            'status' => true,
            'message' => 'Post retrieved successfully',
            'data' => $record,
        ));
    }

    public function store()
    {
        $this->authorize();

        $data = array(
            'title' => $this->input->post('title', true),
            'author' => $this->input->post('author', true),
            'article' => $this->input->post('article', true),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        );

        if (!$data['title'] || !$data['author'] || !$data['article']) {
            return $this->json(array(
                'status' => false,
                'message' => 'Title, author, and article are required',
            ), 400);
        }

        if (!empty($_FILES['image']['name'])) {
            $upload = $this->upload_image();
            if (!$upload['status']) {
                return $this->json(array(
                    'status' => false,
                    'message' => $upload['error'],
                ), 400);
            }
            $data['image'] = 'posts/' . $upload['file_name'];
        }

        $id = $this->Post_model->insert($data);

        $this->json(array(
            'status' => true,
            'message' => 'Post created successfully',
            'data' => $this->Post_model->get_by_id($id),
        ), 201);
    }

    public function update($id)
    {
        $this->authorize();

        if (!$this->Post_model->exists($id)) {
            return $this->json(array(
                'status' => false,
                'message' => 'Post not found',
            ), 404);
        }

        $input = $this->parse_multipart_form();
        if (empty($input)) {
            parse_str(file_get_contents('php://input'), $input);
        }

        $data = array('updated_at' => date('Y-m-d H:i:s'));

        foreach (array('title', 'author', 'article') as $field) {
            $value = $this->input->post($field, true);
            if ($value === null && isset($input[$field]) && !is_array($input[$field])) {
                $value = $input[$field];
            }
            if ($value !== null && $value !== '') {
                $data[$field] = $value;
            }
        }

        if (!empty($_FILES['image']['name'])) {
            $upload = $this->upload_image();
            if (!$upload['status']) {
                return $this->json(array(
                    'status' => false,
                    'message' => $upload['error'],
                ), 400);
            }
            $this->delete_image($id);
            $data['image'] = 'posts/' . $upload['file_name'];
        }

        $this->Post_model->update($id, $data);

        $this->json(array(
            'status' => true,
            'message' => 'Post updated successfully',
            'data' => $this->Post_model->get_by_id($id),
        ));
    }

    public function delete($id)
    {
        $this->authorize();

        if (!$this->Post_model->exists($id)) {
            return $this->json(array(
                'status' => false,
                'message' => 'Post not found',
            ), 404);
        }

        $this->delete_image($id);
        $this->Post_model->delete($id);

        $this->json(array(
            'status' => true,
            'message' => 'Post deleted successfully',
        ));
    }

    private function authorize()
    {
        $token = $this->jwt->get_token_from_request();

        if (!$token || !$this->jwt->verify($token)) {
            $this->json(array(
                'status' => false,
                'message' => 'Unauthorized',
            ), 401);
            exit;
        }
    }

    private function upload_image()
    {
        $upload_path = './uploads/posts/';

        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0777, true);
        }

        $original_name = $_FILES['image']['name'];
        $sanitized_name = preg_replace('/[^A-Za-z0-9._-]/', '_', $original_name);
        $file_name = time() . '_' . $sanitized_name;

        $config['upload_path'] = $upload_path;
        $config['allowed_types'] = 'jpg|jpeg|png';
        $config['max_size'] = 2048;
        $config['file_name'] = $file_name;
        $config['overwrite'] = false;

        $this->upload->initialize($config);

        if ($this->upload->do_upload('image')) {
            $upload_data = $this->upload->data();
            return array('status' => true, 'file_name' => $upload_data['file_name']);
        }

        return array('status' => false, 'error' => $this->upload->display_errors('', ''));
    }

    private function delete_image($id)
    {
        $record = $this->Post_model->get_by_id($id);
        if ($record && $record->image) {
            $path = './uploads/' . $record->image;
            if (file_exists($path)) {
                unlink($path);
            }
        }
    }

    private function json($data, $code = 200)
    {
        ob_clean();
        http_response_code($code);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        exit;
    }
}
