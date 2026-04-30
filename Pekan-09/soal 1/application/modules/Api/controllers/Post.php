<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Post extends MX_Controller {

    public function __construct()
    {
        parent::__construct();
        // Memuat model, JWT, upload, dan helper URL untuk kebutuhan endpoint post.
        $this->load->model('Post_model');
        $this->load->library('jwt');
        $this->load->library('upload');
        $this->load->helper('url');
    }

    /**
     * Membaca multipart/form-data dari php://input.
     * Dipakai karena PHP tidak otomatis mengisi $_POST/$_FILES untuk request PUT.
     */
    private function parse_multipart_form()
    {
        $content_type = $_SERVER['CONTENT_TYPE'] ?? '';
        $input = [];
        
        // Jika request berbentuk multipart/form-data, pecah body berdasarkan boundary.
        if (strpos($content_type, 'multipart/form-data') !== false) {
            $boundary = '';
            if (preg_match('/boundary=([^;]+)/', $content_type, $matches)) {
                $boundary = trim($matches[1], '"');
            }
            
            if (!$boundary) {
                return [];
            }
            
            $raw = file_get_contents('php://input');
            $parts = preg_split('/' . preg_quote("--$boundary") . '/', $raw);
            
            foreach ($parts as $part) {
                if (empty(trim($part)) || $part === "--\r\n" || $part === "--") {
                    continue;
                }
                
                // Pisahkan header tiap field dari isinya.
                $split = preg_split("/\r\n\r\n/", trim($part), 2);
                if (count($split) !== 2) {
                    continue;
                }
                
                $headers = $split[0];
                $content = rtrim($split[1], "\r\n");
                
                // Ambil nama field, misalnya title, article, atau image.
                if (preg_match('/name="([^"]+)"/', $headers, $matches)) {
                    $name = $matches[1];
                    
                    // Jika terdapat filename, field ini dianggap sebagai upload file.
                    if (preg_match('/filename="([^"]+)"/', $headers, $filename_matches)) {
                        // Ambil Content-Type file jika tersedia.
                        $file_content_type = 'application/octet-stream';
                        if (preg_match('/Content-Type:\s*([^\r\n]+)/', $headers, $type_matches)) {
                            $file_content_type = trim($type_matches[1]);
                        }
                        
                        $input[$name] = [
                            'name' => $filename_matches[1],
                            'type' => $file_content_type,
                            'content' => $content
                        ];
                    } else {
                        // Field biasa selain file.
                        $input[$name] = $content;
                    }
                }
            }
            
            return $input;
        }
        
        // Fallback untuk body x-www-form-urlencoded.
        if (strpos($content_type, 'application/x-www-form-urlencoded') !== false) {
            parse_str(file_get_contents('php://input'), $input);
            return $input ?? [];
        }
        
        // Fallback terakhir untuk request POST biasa.
        return array_merge($_POST ?? [], $_FILES ?? []);
    }

    /**
     * Membentuk URL lengkap gambar agar dapat dibaca browser dan response API.
     */
    private function get_image_url($image_filename)
    {
        if (empty($image_filename)) {
            return null;
        }
        $image_filename = ltrim($image_filename, '/');
        $path = strpos($image_filename, '/') === false ? 'posts/' . $image_filename : $image_filename;

        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
        $base_path = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');

        if ($base_path === '.') {
            $base_path = '';
        }

        return $scheme . '://' . $host . $base_path . '/uploads/' . $path;
    }

    private function get_image_path($image_filename)
    {
        // Path fisik dipakai saat menghapus gambar lama dari folder uploads.
        $image_filename = ltrim($image_filename, '/');
        $path = strpos($image_filename, '/') === false ? 'posts/' . $image_filename : $image_filename;

        return FCPATH . 'uploads/' . $path;
    }

    /**
     * Dispatcher endpoint post berdasarkan HTTP verb.
     * Satu route /api/post dapat menangani GET, POST, PUT, dan DELETE.
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
            $this->delete($id);
        } else {
            http_response_code(405);
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'status' => false,
                'message' => 'Method not allowed'
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            exit;
        }
    }

    /**
     * Mengambil semua post. Endpoint ini tidak membutuhkan token.
     * GET /api/post
     */
    public function index()
    {
        header('Content-Type: application/json; charset=utf-8');
        ob_clean();

        try {
            // Parameter pagination bersifat opsional.
            $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
            $per_page = isset($_GET['per_page']) ? max(1, min(100, (int)$_GET['per_page'])) : 10;
            $offset = ($page - 1) * $per_page;

            // Mengambil data post dari model beserta total datanya.
            $posts = $this->Post_model->get_all($per_page, $offset);
            $total = $this->Post_model->count_all();

            // Menambahkan image_url agar client langsung mendapat URL gambar siap pakai.
            foreach ($posts as &$post) {
                if (!empty($post->image)) {
                    $post->image_url = $this->get_image_url($post->image);
                } else {
                    $post->image_url = null;
                }
            }

            http_response_code(200);
            echo json_encode([
                'status' => true,
                'message' => 'Posts retrieved successfully',
                'data' => $posts,
                'pagination' => [
                    'total' => (int)$total,
                    'per_page' => $per_page,
                    'current_page' => $page,
                    'last_page' => ceil($total / $per_page)
                ]
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            exit;

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'status' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            exit;
        }
    }

    /**
     * Mengambil detail satu post. Endpoint ini tidak membutuhkan token.
     * GET /api/post/{id}
     */
    public function show($id)
    {
        header('Content-Type: application/json; charset=utf-8');
        ob_clean();

        try {
            // ID harus angka agar query tidak menerima input yang tidak sesuai.
            if (!is_numeric($id)) {
                http_response_code(400);
                echo json_encode([
                    'status' => false,
                    'message' => 'Invalid post ID'
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                exit;
            }

            // Mengambil satu post berdasarkan ID.
            $post = $this->Post_model->get_by_id($id);

            if (!$post) {
                http_response_code(404);
                echo json_encode([
                    'status' => false,
                    'message' => 'Post not found'
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                exit;
            }

            // Menambahkan URL gambar lengkap pada response detail.
            if (!empty($post->image)) {
                $post->image_url = $this->get_image_url($post->image);
            } else {
                $post->image_url = null;
            }

            http_response_code(200);
            echo json_encode([
                'status' => true,
                'message' => 'Post retrieved successfully',
                'data' => $post
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            exit;

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'status' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            exit;
        }
    }

    /**
     * Membuat post baru. Endpoint ini membutuhkan token.
     * POST /api/post
     */
    public function store()
    {
        header('Content-Type: application/json; charset=utf-8');
        ob_clean();

        try {
            // Memastikan client mengirim Bearer token.
            $token = $this->jwt->get_token_from_request();

            if (!$token) {
                http_response_code(401);
                echo json_encode([
                    'status' => false,
                    'message' => 'Unauthorized: No token provided'
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                exit;
            }

            $decoded = $this->jwt->verify($token);

            if (!$decoded) {
                http_response_code(401);
                echo json_encode([
                    'status' => false,
                    'message' => 'Unauthorized: Invalid or expired token'
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                exit;
            }

            // Input bisa berupa JSON atau form-data.
            $input = json_decode(file_get_contents('php://input'), true);
            
            // Untuk POST form-data, PHP otomatis mengisi $_POST.
            if (!$input && !empty($_POST)) {
                $input = $_POST;
            }

            // Field title dan article wajib diisi.
            if (empty($input['title']) || empty($input['article'])) {
                http_response_code(400);
                echo json_encode([
                    'status' => false,
                    'message' => 'Title and article are required'
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                exit;
            }

            $post_data = [
                'title' => htmlspecialchars($input['title']),
                'author' => htmlspecialchars($input['author'] ?? 'Anonymous'),
                'article' => htmlspecialchars($input['article'])
            ];

            // Jika field image dikirim, simpan file ke folder uploads/posts.
            $image_name = '';
            if (!empty($_FILES['image']['name'])) {
                $image_name = $this->_upload_image();
                if (!$image_name) {
                    http_response_code(400);
                    echo json_encode([
                        'status' => false,
                        'message' => 'Image upload failed'
                    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                    exit;
                }
                $post_data['image'] = $image_name;
            }

            // Menyimpan data post ke tabel posts.
            $post_id = $this->Post_model->create($post_data);

            if (!$post_id) {
                http_response_code(500);
                echo json_encode([
                    'status' => false,
                    'message' => 'Failed to create post'
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                exit;
            }

            // Mengambil ulang data post yang baru dibuat untuk dikirim sebagai response.
            $post = $this->Post_model->get_by_id($post_id);

            if (!empty($post->image)) {
                $post->image_url = $this->get_image_url($post->image);
            } else {
                $post->image_url = null;
            }

            http_response_code(201);
            echo json_encode([
                'status' => true,
                'message' => 'Post created successfully',
                'data' => $post
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            exit;

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'status' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            exit;
        }
    }

    /**
     * Mengubah post. Endpoint ini membutuhkan token.
     * PUT /api/post/{id}
     */
    public function update($id)
    {
        header('Content-Type: application/json; charset=utf-8');
        ob_clean();

        try {
            // PUT hanya boleh dilakukan oleh request yang memiliki token valid.
            $token = $this->jwt->get_token_from_request();

            if (!$token) {
                http_response_code(401);
                echo json_encode([
                    'status' => false,
                    'message' => 'Unauthorized: No token provided'
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                exit;
            }

            $decoded = $this->jwt->verify($token);

            if (!$decoded) {
                http_response_code(401);
                echo json_encode([
                    'status' => false,
                    'message' => 'Unauthorized: Invalid or expired token'
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                exit;
            }

            // ID post harus valid sebelum proses update.
            if (!is_numeric($id)) {
                http_response_code(400);
                echo json_encode([
                    'status' => false,
                    'message' => 'Invalid post ID'
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                exit;
            }

            // Pastikan post yang akan diubah benar-benar ada.
            if (!$this->Post_model->exists($id)) {
                http_response_code(404);
                echo json_encode([
                    'status' => false,
                    'message' => 'Post not found'
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                exit;
            }

            // Membaca input JSON atau multipart/form-data.
            $input = json_decode(file_get_contents('php://input'), true);
            if (!$input) {
                $input = $this->parse_multipart_form();
                
                // Jika ada file dari request PUT, bentuk ulang $_FILES agar library upload CI bisa memprosesnya.
                if (!empty($input['image']) && is_array($input['image']) && isset($input['image']['content'])) {
                    $_FILES['image'] = [
                        'name' => $input['image']['name'] ?? 'image_' . time() . '.png',
                        'type' => $input['image']['type'] ?? 'image/png',
                        'tmp_name' => $this->_save_temp_file($input['image']['content']),
                        'error' => 0,
                        'size' => strlen($input['image']['content'])
                    ];
                }
            }

            // Title dan article tetap wajib saat update.
            if (empty($input['title']) || empty($input['article'])) {
                http_response_code(400);
                echo json_encode([
                    'status' => false,
                    'message' => 'Title and article are required'
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                exit;
            }

            $post_data = [
                'title' => htmlspecialchars($input['title']),
                'author' => htmlspecialchars($input['author'] ?? 'Anonymous'),
                'article' => htmlspecialchars($input['article'])
            ];

            // Upload gambar pada update bersifat opsional.
            if (!empty($_FILES['image']['name']) || (isset($input['image']) && is_array($input['image']) && !empty($input['image']['name']))) {
                $old_post = $this->Post_model->get_by_id($id);
                
                // Hapus gambar lama agar folder upload tidak menumpuk file yang tidak dipakai.
                if (!empty($old_post->image)) {
                    $old_file = $this->get_image_path($old_post->image);
                    if (file_exists($old_file)) {
                        unlink($old_file);
                    }
                }

                $image_name = $this->_upload_image();
                if ($image_name) {
                    $post_data['image'] = $image_name;
                }
                // Jika upload gambar gagal, update data teks tetap bisa dilanjutkan.
            }

            // Menyimpan perubahan post ke database.
            $result = $this->Post_model->update($id, $post_data);

            if (!$result) {
                http_response_code(500);
                echo json_encode([
                    'status' => false,
                    'message' => 'Failed to update post'
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                exit;
            }

            // Mengambil data terbaru setelah update.
            $post = $this->Post_model->get_by_id($id);

            if (!empty($post->image)) {
                $post->image_url = $this->get_image_url($post->image);
            } else {
                $post->image_url = null;
            }

            http_response_code(200);
            echo json_encode([
                'status' => true,
                'message' => 'Post updated successfully',
                'data' => $post
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            exit;

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'status' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            exit;
        }
    }

    /**
     * Menghapus post. Endpoint ini membutuhkan token.
     * DELETE /api/post/{id}
     */
    public function delete($id)
    {
        header('Content-Type: application/json; charset=utf-8');
        ob_clean();

        try {
            // DELETE hanya boleh dilakukan oleh request yang memiliki token valid.
            $token = $this->jwt->get_token_from_request();

            if (!$token) {
                http_response_code(401);
                echo json_encode([
                    'status' => false,
                    'message' => 'Unauthorized: No token provided'
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                exit;
            }

            $decoded = $this->jwt->verify($token);

            if (!$decoded) {
                http_response_code(401);
                echo json_encode([
                    'status' => false,
                    'message' => 'Unauthorized: Invalid or expired token'
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                exit;
            }

            // ID post harus berupa angka.
            if (!is_numeric($id)) {
                http_response_code(400);
                echo json_encode([
                    'status' => false,
                    'message' => 'Invalid post ID'
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                exit;
            }

            // Ambil data post agar file gambar terkait bisa ikut dihapus.
            $post = $this->Post_model->get_by_id($id);

            if (!$post) {
                http_response_code(404);
                echo json_encode([
                    'status' => false,
                    'message' => 'Post not found'
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                exit;
            }

            // Hapus file gambar jika post memiliki gambar.
            if (!empty($post->image)) {
                $file = $this->get_image_path($post->image);
                if (file_exists($file)) {
                    unlink($file);
                }
            }

            // Hapus data post dari database.
            $result = $this->Post_model->delete($id);

            if (!$result) {
                http_response_code(500);
                echo json_encode([
                    'status' => false,
                    'message' => 'Failed to delete post'
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                exit;
            }

            http_response_code(200);
            echo json_encode([
                'status' => true,
                'message' => 'Post deleted successfully'
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            exit;

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'status' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            exit;
        }
    }

    /**
     * Menyimpan konten file sementara dari multipart PUT.
     */
    private function _save_temp_file($content)
    {
        $temp_path = sys_get_temp_dir() . '/ci3_upload_' . uniqid() . '.tmp';
        file_put_contents($temp_path, $content);
        return $temp_path;
    }

    private function _upload_image()
    {
        // Konfigurasi upload gambar untuk post.
        $config['upload_path'] = FCPATH . 'uploads/posts/';
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
        $config['max_size'] = 2048; // 2MB
        $config['file_name'] = 'post_' . time() . '_' . uniqid();

        // Buat folder upload jika belum tersedia.
        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0755, true);
        }

        $this->upload->initialize($config);

        if (!$this->upload->do_upload('image')) {
            return false;
        }

        $upload_data = $this->upload->data();
        return $upload_data['file_name'];
    }
}
