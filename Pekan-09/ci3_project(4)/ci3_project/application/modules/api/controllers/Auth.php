<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends MX_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->library('jwt');
    }

    public function register()
    {
        $input = $this->json_input();

        if (empty($input['name']) || empty($input['email']) || empty($input['password'])) {
            $this->json(array('status' => FALSE, 'message' => 'Name, email, and password are required'), 400);
            return;
        }

        if ($this->User_model->email_exists($input['email'])) {
            $this->json(array('status' => FALSE, 'message' => 'Email already registered'), 409);
            return;
        }

        $user_id = $this->User_model->create(array(
            'name' => htmlspecialchars($input['name']),
            'email' => strtolower($input['email']),
            'password' => password_hash($input['password'], PASSWORD_BCRYPT)
        ));

        $user = $this->User_model->get_by_id($user_id);
        $token = $this->jwt->create(array('id' => $user->id, 'name' => $user->name, 'email' => $user->email));

        $this->json(array('status' => TRUE, 'data' => $user, 'access_token' => $token, 'token_type' => 'Bearer'), 201);
    }

    public function login()
    {
        $input = $this->json_input();

        if (empty($input['email']) || empty($input['password'])) {
            $this->json(array('status' => FALSE, 'message' => 'Email and password are required'), 400);
            return;
        }

        $user = $this->User_model->get_by_email($input['email']);

        if (!$user || !password_verify($input['password'], $user->password)) {
            $this->json(array('status' => FALSE, 'message' => 'Invalid email or password'), 401);
            return;
        }

        unset($user->password);
        $token = $this->jwt->create(array('id' => $user->id, 'name' => $user->name, 'email' => $user->email));

        $this->json(array('status' => TRUE, 'data' => $user, 'access_token' => $token, 'token_type' => 'Bearer'));
    }

    public function logout()
    {
        $user = $this->authenticated_user();

        if (!$user) {
            return;
        }

        $this->json(array('status' => TRUE, 'message' => 'Logout success'));
    }

    public function me()
    {
        $user = $this->authenticated_user();

        if (!$user) {
            return;
        }

        $this->json(array('status' => TRUE, 'data' => $user));
    }

    private function authenticated_user()
    {
        $token = $this->jwt->get_token_from_request();
        $payload = $token ? $this->jwt->verify($token) : FALSE;

        if (!$payload) {
            $this->json(array('status' => FALSE, 'message' => 'Unauthorized'), 401);
            return FALSE;
        }

        return $payload;
    }

    private function json_input()
    {
        $input = json_decode(file_get_contents('php://input'), TRUE);
        return is_array($input) ? $input : $this->input->post();
    }

    private function json($data, $status = 200)
    {
        $this->output
            ->set_status_header($status)
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode($data));
    }
}
