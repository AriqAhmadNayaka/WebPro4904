<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Register_API extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Api/User_model', 'Api_user_model');
    }

    public function index($username = null, $password = null)
    {
        $teks = [
            'username' => $username ?: 'grandy',
            'password' => $password ?: 'grandy123'
        ];
        echo json_encode($teks);
        // echo "halo, username: " . $teks['username'] . " dan password: " . $teks['password'];
    }

    public function register_API()
    {
        $json_input = json_decode($this->input->raw_input_stream, true);
        $input = is_array($json_input) ? $json_input : $this->input->post();

        $name = isset($input['name']) && $input['name'] !== ''
            ? $input['name']
            : (isset($input['username']) ? $input['username'] : '');
        $email = isset($input['email']) ? strtolower($input['email']) : '';
        $password = isset($input['password']) ? $input['password'] : '';

        if ($name === '' || $email === '' || $password === '') {
            return $this->output
                ->set_status_header(400)
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => false,
                    'message' => 'name/username, email, and password are required'
                ]));
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->output
                ->set_status_header(400)
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => false,
                    'message' => 'Invalid email format'
                ]));
        }

        if ($this->Api_user_model->email_exists($email)) {
            return $this->output
                ->set_status_header(409)
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => false,
                    'message' => 'Email already registered'
                ]));
        }

        $user_id = $this->Api_user_model->create([
            'username' => htmlspecialchars($name),
            'email' => $email,
            'password' => password_hash($password, PASSWORD_BCRYPT),
            'role' => 'sekolah'
        ]);

        if (!$user_id) {
            return $this->output
                ->set_status_header(500)
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => false,
                    'message' => 'Failed to create user'
                ]));
        }

        $user = $this->Api_user_model->get_by_id($user_id);

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'status' => true,
                'message' => 'User registered successfully',
                'data' => [
                    'id' => $user->id,
                    'username' => $user->username,
                    'email' => $user->email,
                    'role' => $user->role,
                    'created_at' => $user->created_at
                ]
            ]));
            
    }
}
