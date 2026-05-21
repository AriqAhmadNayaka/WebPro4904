<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends MX_Controller {

    public function __construct()
    {
        parent::__construct();
        header('Access-Control-Allow-Origin: http://localhost:5173');
        header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');

        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(204);
            exit;
        }

        $this->load->model('User_model');
        $this->load->library('jwt');
        $this->load->library('form_validation');
    }

    public function register()
    {

        header('Content-Type: application/json; charset=utf-8');
        ob_clean();

        try {
            $input = json_decode(file_get_contents('php://input'), true);

            if (empty($input['name']) || empty($input['email']) || empty($input['password'])) {
                http_response_code(400);
                echo json_encode([
                    'status' => false,
                    'message' => 'Name, email, and password are required'
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                exit;
            }

            if (!filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            echo json_encode([
                'status' => false,
                'message' => 'Invalid email format'
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            exit;
            }

            if ($this->User_model->email_exists($input['email'])) {
                http_response_code(409);
                echo json_encode([
                    'status' => false,
                    'message' => 'Email already registered'
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                exit;
            }

            if (strlen($input['password']) < 6) {
                http_response_code(400);
                echo json_encode([
                    'status' => false,
                    'message' => 'Password must be at least 6 characters'
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                exit;
            }
            
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

            $user = $this->User_model->get_by_id($user_id);

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

    public function login()
    {
        header('Content-Type: application/json; charset=utf-8');
        ob_clean();

        try {
            $input = json_decode(file_get_contents('php://input'), true);

            if (empty($input['email']) || empty($input['password'])) {
                http_response_code(400);
                echo json_encode([
                    'status' => false,
                    'message' => 'Email and password are required'
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                exit;
            }

            $user = $this->User_model->get_by_email(strtolower($input['email']));

            if (!$user) {
                http_response_code(401);
                echo json_encode([
                    'status' => false,
                    'message' => 'Invalid credentials'
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                exit;
            }

            if (!password_verify($input['password'], $user->password)) {
                http_response_code(401);
                echo json_encode([
                    'status' => false,
                    'message' => 'Invalid credentials'
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                exit;
            }

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

    public function logout()
    {
        header('Content-Type: application/json; charset=utf-8');
        ob_clean();

        try {
            $token = $this->jwt->get_token_from_request();

            if (!$token) {
                http_response_code(401);
                echo json_encode([
                    'status' => false,
                    'message' => 'No token provided'
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                exit;
            }

            $decoded = $this->jwt->verify($token);

            if (!$decoded) {
                http_response_code(401);
                echo json_encode([
                    'status' => false,
                    'message' => 'Invalid token'
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                exit;
            }

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

    public function me()
    {
        header('Content-Type: application/json; charset=utf-8');
        ob_clean();

        try {
            $token = $this->jwt->get_token_from_request();

            if (!$token) {
                http_response_code(401);
                echo json_encode([
                    'status' => false,
                    'message' => 'No token provided'
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                exit;
            }

            $decoded = $this->jwt->verify($token);

            if (!$decoded) {
                http_response_code(401);
                echo json_encode([
                    'status' => false,
                    'message' => 'Invalid or expired token'
                ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                exit;
            }

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
                    'updated_at' => $user->updated_at,
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
