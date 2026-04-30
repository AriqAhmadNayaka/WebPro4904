<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends MX_Controller {

    public function __construct()
    {
        parent::__construct();
        // Memuat model user dan library yang dibutuhkan untuk autentikasi API.
        $this->load->model('User_model');
        $this->load->library('jwt');
        $this->load->library('form_validation');
    }

    /**
     * Endpoint register user baru.
     * POST /api/auth/register
     */
    public function register()
    {
        // Semua response auth dikembalikan dalam format JSON.
        header('Content-Type: application/json; charset=utf-8');
        ob_clean();

        try {
            // Membaca body JSON dari Postman/client.
            $input = json_decode(file_get_contents('php://input'), true);

            // Validasi field wajib untuk proses pendaftaran.
            if (empty($input['name']) || empty($input['email']) || empty($input['password'])) {
                http_response_code(400);
                echo json_encode([
                    'status' => false,
                    'message' => 'Name, email, and password are required'
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                exit;
            }

            // Memastikan format email valid sebelum disimpan.
            if (!filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
                http_response_code(400);
                echo json_encode([
                    'status' => false,
                    'message' => 'Invalid email format'
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                exit;
            }

            // Email harus unik agar satu akun tidak terdaftar dua kali.
            if ($this->User_model->email_exists($input['email'])) {
                http_response_code(409);
                echo json_encode([
                    'status' => false,
                    'message' => 'Email already registered'
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                exit;
            }

            // Password minimal 6 karakter sesuai kebutuhan praktikum.
            if (strlen($input['password']) < 6) {
                http_response_code(400);
                echo json_encode([
                    'status' => false,
                    'message' => 'Password must be at least 6 characters'
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                exit;
            }

            // Password disimpan sebagai hash, bukan teks asli.
            $user_data = [
                'name' => htmlspecialchars($input['name']),
                'email' => strtolower($input['email']),
                'password' => password_hash($input['password'], PASSWORD_BCRYPT)
            ];

            $user_id = $this->User_model->create($user_data);

            if (!$user_id) {
                http_response_code(500);
                echo json_encode([
                    'status' => false,
                    'message' => 'Failed to create user'
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                exit;
            }

            // Mengambil ulang data user yang baru dibuat.
            $user = $this->User_model->get_by_id($user_id);

            // Token JWT dikirim agar user langsung dapat mengakses endpoint yang membutuhkan login.
            $token = $this->jwt->create([
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email
            ]);

            http_response_code(201);
            echo json_encode([
                'status' => true,
                'message' => 'User registered successfully',
                'data' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'created_at' => $user->created_at
                ],
                'access_token' => $token,
                'token_type' => 'Bearer'
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
     * Endpoint login user.
     * POST /api/auth/login
     */
    public function login()
    {
        header('Content-Type: application/json; charset=utf-8');
        ob_clean();

        try {
            $input = json_decode(file_get_contents('php://input'), true);

            // Email dan password wajib dikirim oleh client.
            if (empty($input['email']) || empty($input['password'])) {
                http_response_code(400);
                echo json_encode([
                    'status' => false,
                    'message' => 'Email and password are required'
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                exit;
            }

            // Mencari user berdasarkan email yang dikirim.
            $user = $this->User_model->get_by_email(strtolower($input['email']));

            if (!$user) {
                http_response_code(401);
                echo json_encode([
                    'status' => false,
                    'message' => 'Invalid credentials'
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                exit;
            }

            // Membandingkan password input dengan hash yang tersimpan di database.
            if (!password_verify($input['password'], $user->password)) {
                http_response_code(401);
                echo json_encode([
                    'status' => false,
                    'message' => 'Invalid credentials'
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                exit;
            }

            // Membuat token JWT jika email dan password valid.
            $token = $this->jwt->create([
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email
            ]);

            http_response_code(200);
            echo json_encode([
                'status' => true,
                'message' => 'Login successful',
                'data' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email
                ],
                'access_token' => $token,
                'token_type' => 'Bearer'
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
     * Endpoint logout user.
     * POST /api/auth/logout
     */
    public function logout()
    {
        header('Content-Type: application/json; charset=utf-8');
        ob_clean();

        try {
            // Token diambil dari header Authorization: Bearer <token>.
            $token = $this->jwt->get_token_from_request();

            if (!$token) {
                http_response_code(401);
                echo json_encode([
                    'status' => false,
                    'message' => 'No token provided'
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                exit;
            }

            // Token diverifikasi untuk memastikan request berasal dari user yang valid.
            $decoded = $this->jwt->verify($token);

            if (!$decoded) {
                http_response_code(401);
                echo json_encode([
                    'status' => false,
                    'message' => 'Invalid token'
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                exit;
            }

            // User dicek kembali berdasarkan ID yang ada di dalam token.
            $user = $this->User_model->get_by_id($decoded->id);

            if (!$user) {
                http_response_code(404);
                echo json_encode([
                    'status' => false,
                    'message' => 'User not found'
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                exit;
            }

            // Pada praktikum ini logout cukup mengembalikan status berhasil.
            // Pada aplikasi produksi, token biasanya disimpan ke blacklist atau dihapus dari database.
            http_response_code(200);
            echo json_encode([
                'status' => true,
                'message' => 'Logout successful'
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
     * Endpoint untuk mengambil data user yang sedang login.
     * GET /api/auth/me
     */
    public function me()
    {
        header('Content-Type: application/json; charset=utf-8');
        ob_clean();

        try {
            // Token diambil dari header Authorization.
            $token = $this->jwt->get_token_from_request();

            if (!$token) {
                http_response_code(401);
                echo json_encode([
                    'status' => false,
                    'message' => 'No token provided'
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                exit;
            }

            // Jika token valid, payload token akan berisi ID user.
            $decoded = $this->jwt->verify($token);

            if (!$decoded) {
                http_response_code(401);
                echo json_encode([
                    'status' => false,
                    'message' => 'Invalid or expired token'
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                exit;
            }

            // Data user asli tetap diambil dari database agar informasinya terbaru.
            $user = $this->User_model->get_by_id($decoded->id);

            if (!$user) {
                http_response_code(404);
                echo json_encode([
                    'status' => false,
                    'message' => 'User not found'
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                exit;
            }

            http_response_code(200);
            echo json_encode([
                'status' => true,
                'message' => 'User retrieved successfully',
                'data' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'created_at' => $user->created_at,
                    'updated_at' => $user->updated_at
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
}
