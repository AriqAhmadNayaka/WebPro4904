<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Api/User_model');
        $this->load->library('Jwt');
    }

    public function register()
    {
        $input = $this->_input();

        $name = isset($input['name']) ? trim($input['name']) : '';
        $email = isset($input['email']) ? trim($input['email']) : '';
        $password = isset($input['password']) ? $input['password'] : '';

        if ($name === '' || $email === '' || $password === '') {
            $this->_json(array('status' => FALSE, 'message' => 'Name, email, and password are required'), 422);
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->_json(array('status' => FALSE, 'message' => 'Email is invalid'), 422);
            return;
        }

        if ($this->User_model->email_exists($email)) {
            $this->_json(array('status' => FALSE, 'message' => 'Email already registered'), 409);
            return;
        }

        $user_id = $this->User_model->insert(array(
            'name' => $name,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_BCRYPT),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ));

        $this->_json(array(
            'status' => TRUE,
            'message' => 'Register success',
            'data' => array('id' => $user_id, 'name' => $name, 'email' => $email)
        ), 201);
    }

    public function login()
    {
        $input = $this->_input();

        $email = isset($input['email']) ? trim($input['email']) : '';
        $password = isset($input['password']) ? $input['password'] : '';

        if ($email === '' || $password === '') {
            $this->_json(array('status' => FALSE, 'message' => 'Email and password are required'), 422);
            return;
        }

        $user = $this->User_model->get_by_email($email);

        if (!$user || !password_verify($password, $user->password)) {
            $this->_json(array('status' => FALSE, 'message' => 'Invalid email or password'), 401);
            return;
        }

        $token = $this->jwt->encode(array(
            'user_id' => $user->id,
            'email' => $user->email
        ));

        $this->_json(array(
            'status' => TRUE,
            'message' => 'Login success',
            'token_type' => 'Bearer',
            'token' => $token,
            'data' => array(
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email
            )
        ));
    }

    public function me()
    {
        $payload = $this->_auth_payload();

        if (!$payload) {
            return;
        }

        $user = $this->User_model->get_by_id($payload['user_id']);

        if (!$user) {
            $this->_json(array('status' => FALSE, 'message' => 'User not found'), 404);
            return;
        }

        unset($user->password);

        $this->_json(array(
            'status' => TRUE,
            'message' => 'User profile',
            'data' => $user
        ));
    }

    public function logout()
    {
        $payload = $this->_auth_payload();

        if (!$payload) {
            return;
        }

        $this->_json(array(
            'status' => TRUE,
            'message' => 'Logout success. Please remove token on client side.'
        ));
    }

    private function _auth_payload()
    {
        $token = $this->jwt->get_token_from_header();

        if (!$token) {
            $this->_json(array('status' => FALSE, 'message' => 'Bearer token is required'), 401);
            return FALSE;
        }

        $payload = $this->jwt->decode($token);

        if (!$payload) {
            $this->_json(array('status' => FALSE, 'message' => 'Token is invalid or expired'), 401);
            return FALSE;
        }

        return $payload;
    }

    private function _input()
    {
        $raw = $this->input->raw_input_stream;
        $json = json_decode($raw, TRUE);

        if (is_array($json)) {
            return $json;
        }

        return $this->input->post();
    }

    private function _json($data, $status_code = 200)
    {
        return $this->output
            ->set_status_header($status_code)
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }
}
