<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Post extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Post_model');
        $this->load->library('jwt');
        $this->load->library('upload');
    }

    /**
     * Main dispatcher - routes based on HTTP method
     */
    public function handle($id = null)
    {
        $method = $_SERVER['REQUEST_METHOD'];

        if ($method === 'GET') {
            if ($id) {
                $this->show($id);
            } else {
                $this->index();
            }
        } elseif ($method === 'POST') {
            $this->store();
        } elseif ($method === 'PUT') {
            $this->update($id);
        } elseif ($method === 'DELETE') {
            $this->destroy($id);
        } else {
            http_response_code(405);
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(array(
                'status'  => false,
                'message' => 'Method not allowed'
            ), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            exit;
        }
    }

    /**
     * GET /api/post
     */
    private function index()
    {
        header('Content-Type: application/json; charset=utf-8');
        ob_clean();

        try {
            $page     = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
            $per_page = isset($_GET['per_page']) ? max(1, min(100, (int)$_GET['per_page'])) : 10;
            $offset   = ($page - 1) * $per_page;

            $posts = $this->Post_model->get_all($per_page, $offset);
            $total = $this->Post_model->count_all();

            foreach ($posts as $post) {
                $post->image_url = !empty($post->image)
                    ? $this->_get_image_url($post->image)
                    : null;
            }

            http_response_code(200);
            echo json_encode(array(
                'status'     => true,
                'message'    => 'Posts retrieved successfully',
                'data'       => $posts,
                'pagination' => array(
                    'total'        => (int)$total,
                    'per_page'     => $per_page,
                    'current_page' => $page,
                    'last_page'    => (int)ceil($total / $per_page)
                )
            ), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            exit;

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(array(
                'status'  => false,
                'message' => 'Server error: ' . $e->getMessage()
            ), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            exit;
        }
    }

    /**
     * GET /api/post/{id}
     */
    private function show($id)
    {
        header('Content-Type: application/json; charset=utf-8');
        ob_clean();

        try {
            if (!is_numeric($id)) {
                http_response_code(400);
                echo json_encode(array(
                    'status'  => false,
                    'message' => 'Invalid post ID'
                ), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                exit;
            }

            $post = $this->Post_model->get_by_id($id);

            if (!$post) {
                http_response_code(404);
                echo json_encode(array(
                    'status'  => false,
                    'message' => 'Post not found'
                ), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                exit;
            }

            $post->image_url = !empty($post->image)
                ? $this->_get_image_url($post->image)
                : null;

            http_response_code(200);
            echo json_encode(array(
                'status'  => true,
                'message' => 'Post retrieved successfully',
                'data'    => $post
            ), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            exit;

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(array(
                'status'  => false,
                'message' => 'Server error: ' . $e->getMessage()
            ), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            exit;
        }
    }

    /**
     * POST /api/post (requires auth)
     */
    private function store()
    {
        header('Content-Type: application/json; charset=utf-8');
        ob_clean();

        try {
            $token = $this->jwt->get_token_from_request();
            if (!$token) {
                http_response_code(401);
                echo json_encode(array('status' => false, 'message' => 'Unauthorized: no token provided'), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                exit;
            }
            $decoded = $this->jwt->verify($token);
            if (!$decoded) {
                http_response_code(401);
                echo json_encode(array('status' => false, 'message' => 'Unauthorized: invalid or expired token'), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                exit;
            }

            // Baca input: JSON dulu, fallback ke $_POST (form-data)
            $input = json_decode(file_get_contents('php://input'), true);
            if (empty($input) && !empty($_POST)) {
                $input = $_POST;
            }

            if (empty($input['title']) || empty($input['article'])) {
                http_response_code(400);
                echo json_encode(array('status' => false, 'message' => 'Title and article are required'), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                exit;
            }

            $post_data = array(
                'title'   => htmlspecialchars($input['title']),
                'author'  => htmlspecialchars(isset($input['author']) ? $input['author'] : 'Anonymous'),
                'article' => htmlspecialchars($input['article'])
            );

            if (!empty($_FILES['image']['name'])) {
                $image_name = $this->_upload_image();
                if (!$image_name) {
                    http_response_code(400);
                    echo json_encode(array('status' => false, 'message' => 'Image upload failed: ' . $this->upload->display_errors('', '')), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                    exit;
                }
                $post_data['image'] = 'posts/' . $image_name;
            }

            $post_id = $this->Post_model->create($post_data);

            if (!$post_id) {
                http_response_code(500);
                echo json_encode(array('status' => false, 'message' => 'Failed to create post'), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                exit;
            }

            $post            = $this->Post_model->get_by_id($post_id);
            $post->image_url = !empty($post->image) ? $this->_get_image_url($post->image) : null;

            http_response_code(201);
            echo json_encode(array(
                'status'  => true,
                'message' => 'Post created successfully',
                'data'    => $post
            ), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            exit;

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(array('status' => false, 'message' => 'Server error: ' . $e->getMessage()), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            exit;
        }
    }

    /**
     * PUT /api/post/{id} (requires auth)
     *
     * PHP tidak otomatis isi $_POST untuk PUT dengan form-data,
     * jadi kita parse manual lewat _parse_put_input().
     */
    private function update($id)
    {
        header('Content-Type: application/json; charset=utf-8');
        ob_clean();

        try {
            $token = $this->jwt->get_token_from_request();
            if (!$token) {
                http_response_code(401);
                echo json_encode(array('status' => false, 'message' => 'Unauthorized: no token provided'), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                exit;
            }
            $decoded = $this->jwt->verify($token);
            if (!$decoded) {
                http_response_code(401);
                echo json_encode(array('status' => false, 'message' => 'Unauthorized: invalid or expired token'), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                exit;
            }

            if (!is_numeric($id)) {
                http_response_code(400);
                echo json_encode(array('status' => false, 'message' => 'Invalid post ID'), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                exit;
            }

            if (!$this->Post_model->exists($id)) {
                http_response_code(404);
                echo json_encode(array('status' => false, 'message' => 'Post not found'), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                exit;
            }

            // Baca input PUT:
            // 1. Coba JSON body
            // 2. Coba $_POST (kadang terisi di beberapa server)
            // 3. Parse multipart manual
            $raw   = file_get_contents('php://input');
            $input = json_decode($raw, true);

            if (empty($input)) {
                if (!empty($_POST)) {
                    $input = $_POST;
                } else {
                    $input = $this->_parse_put_input($raw);
                }
            }

            if (empty($input['title']) || empty($input['article'])) {
                http_response_code(400);
                echo json_encode(array('status' => false, 'message' => 'Title and article are required'), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                exit;
            }

            $post_data = array(
                'title'   => htmlspecialchars($input['title']),
                'author'  => htmlspecialchars(isset($input['author']) ? $input['author'] : 'Anonymous'),
                'article' => htmlspecialchars($input['article'])
            );

            // Handle image update
            if (!empty($_FILES['image']['name'])) {
                $old_post = $this->Post_model->get_by_id($id);
                if (!empty($old_post->image)) {
                    $old_file = FCPATH . 'uploads/' . $old_post->image;
                    if (file_exists($old_file)) {
                        unlink($old_file);
                    }
                }
                $image_name = $this->_upload_image();
                if ($image_name) {
                    $post_data['image'] = 'posts/' . $image_name;
                }
            }

            $this->Post_model->update($id, $post_data);

            $post            = $this->Post_model->get_by_id($id);
            $post->image_url = !empty($post->image) ? $this->_get_image_url($post->image) : null;

            http_response_code(200);
            echo json_encode(array(
                'status'  => true,
                'message' => 'Post updated successfully',
                'data'    => $post
            ), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            exit;

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(array('status' => false, 'message' => 'Server error: ' . $e->getMessage()), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            exit;
        }
    }

    /**
     * DELETE /api/post/{id} (requires auth)
     */
    private function destroy($id)
    {
        header('Content-Type: application/json; charset=utf-8');
        ob_clean();

        try {
            $token = $this->jwt->get_token_from_request();
            if (!$token) {
                http_response_code(401);
                echo json_encode(array('status' => false, 'message' => 'Unauthorized: no token provided'), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                exit;
            }
            $decoded = $this->jwt->verify($token);
            if (!$decoded) {
                http_response_code(401);
                echo json_encode(array('status' => false, 'message' => 'Unauthorized: invalid or expired token'), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                exit;
            }

            if (!is_numeric($id)) {
                http_response_code(400);
                echo json_encode(array('status' => false, 'message' => 'Invalid post ID'), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                exit;
            }

            $post = $this->Post_model->get_by_id($id);
            if (!$post) {
                http_response_code(404);
                echo json_encode(array('status' => false, 'message' => 'Post not found'), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                exit;
            }

            if (!empty($post->image)) {
                $file = FCPATH . 'uploads/' . $post->image;
                if (file_exists($file)) {
                    unlink($file);
                }
            }

            $this->Post_model->delete($id);

            http_response_code(200);
            echo json_encode(array(
                'status'  => true,
                'message' => 'Post deleted successfully'
            ), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            exit;

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(array('status' => false, 'message' => 'Server error: ' . $e->getMessage()), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            exit;
        }
    }

    /**
     * Parse PUT multipart/form-data secara manual.
     * PHP tidak otomatis populate $_POST untuk method PUT.
     */
    private function _parse_put_input($raw = '')
    {
        $input        = array();
        $content_type = isset($_SERVER['CONTENT_TYPE']) ? $_SERVER['CONTENT_TYPE'] : '';

        // Kalau bukan multipart, coba url-encoded
        if (strpos($content_type, 'multipart/form-data') === false) {
            parse_str($raw, $input);
            return $input;
        }

        // Ambil boundary
        preg_match('/boundary=(.*)$/', $content_type, $matches);
        if (empty($matches[1])) {
            return $input;
        }

        $boundary = $matches[1];
        $parts    = preg_split('/-+' . preg_quote($boundary, '/') . '/', $raw);

        foreach ($parts as $part) {
            if (empty(trim($part)) || $part === '--') {
                continue;
            }

            // Pisahkan header dan content
            $split = explode("\r\n\r\n", $part, 2);
            if (count($split) < 2) {
                continue;
            }

            list($headers_raw, $content) = $split;
            $content = rtrim($content, "\r\n");

            // Ambil nama field dari header
            if (preg_match('/name="([^"]+)"/', $headers_raw, $name_match)) {
                $input[$name_match[1]] = $content;
            }
        }

        return $input;
    }

    /**
     * Generate full image URL
     */
    private function _get_image_url($filename)
    {
        if (empty($filename)) return null;
        return base_url('uploads/' . $filename);
    }

    /**
     * Upload image helper
     */
    private function _upload_image()
    {
        $upload_path = FCPATH . 'uploads/posts/';

        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0755, true);
        }

        $config['upload_path']   = $upload_path;
        $config['allowed_types'] = 'gif|jpg|jpeg|png';
        $config['max_size']      = 2048;
        $config['file_name']     = 'post_' . time() . '_' . uniqid();

        $this->upload->initialize($config);

        if ($this->upload->do_upload('image')) {
            return $this->upload->data()['file_name'];
        }
        return false;
    }
}