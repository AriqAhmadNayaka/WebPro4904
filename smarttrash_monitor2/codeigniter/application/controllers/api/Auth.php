<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
    }

    public function register()
    {
        if ($this->request_method() !== 'POST') {
            return $this->json_response(array('status' => FALSE, 'message' => 'Method not allowed'), 405);
        }

        $payload = $this->request_data();
        $name = trim(isset($payload['name']) ? $payload['name'] : '');
        $email = strtolower(trim(isset($payload['email']) ? $payload['email'] : ''));
        $password = isset($payload['password']) ? (string) $payload['password'] : '';
        $confirm_password = isset($payload['confirm_password']) ? (string) $payload['confirm_password'] : '';

        if ($name === '' || $email === '' || $password === '' || $confirm_password === '') {
            return $this->json_response(array('status' => FALSE, 'message' => 'Semua field wajib diisi.'), 422);
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->json_response(array('status' => FALSE, 'message' => 'Format email tidak valid.'), 422);
        }

        if ($password !== $confirm_password) {
            return $this->json_response(array('status' => FALSE, 'message' => 'Konfirmasi kata sandi tidak cocok.'), 422);
        }

        if (strlen($password) < 6) {
            return $this->json_response(array('status' => FALSE, 'message' => 'Kata sandi minimal 6 karakter.'), 422);
        }

        if ($this->User_model->get_by_email($email)) {
            return $this->json_response(array('status' => FALSE, 'message' => 'Email sudah terdaftar.'), 409);
        }

        $user_id = $this->User_model->insert(array(
            'name' => $name,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'role' => 'user',
            'photo' => NULL
        ));

        $user = $this->User_model->get_by_id($user_id);
        $token = $this->issue_api_token($user_id);

        return $this->json_response(array(
            'status' => TRUE,
            'message' => 'Akun berhasil dibuat.',
            'data' => array(
                'token' => $token,
                'user' => $this->serialize_user($user)
            )
        ), 201);
    }

    public function login()
    {
        if ($this->request_method() !== 'POST') {
            return $this->json_response(array('status' => FALSE, 'message' => 'Method not allowed'), 405);
        }

        $payload = $this->request_data();
        $email = strtolower(trim(isset($payload['email']) ? $payload['email'] : ''));
        $password = isset($payload['password']) ? (string) $payload['password'] : '';

        $user = $this->User_model->get_by_email($email);
        if (!$user || !password_verify($password, $user['password'])) {
            return $this->json_response(array('status' => FALSE, 'message' => 'Email atau kata sandi salah.'), 401);
        }

        $token = $this->issue_api_token($user['id']);

        return $this->json_response(array(
            'status' => TRUE,
            'message' => 'Login berhasil.',
            'data' => array(
                'token' => $token,
                'user' => $this->serialize_user($user)
            )
        ));
    }

    public function me()
    {
        $user = $this->require_api_auth();

        return $this->json_response(array(
            'status' => TRUE,
            'data' => array(
                'user' => $this->serialize_user($user)
            )
        ));
    }

    public function logout()
    {
        if ($this->request_method() !== 'POST') {
            return $this->json_response(array('status' => FALSE, 'message' => 'Method not allowed'), 405);
        }

        $token = $this->bearer_token();
        $this->revoke_api_token($token);

        return $this->json_response(array(
            'status' => TRUE,
            'message' => 'Logout berhasil.'
        ));
    }

    public function request_reset()
    {
        if ($this->request_method() !== 'POST') {
            return $this->json_response(array('status' => FALSE, 'message' => 'Method not allowed'), 405);
        }

        $payload = $this->request_data();
        $email = strtolower(trim(isset($payload['email']) ? $payload['email'] : ''));
        $user = $this->User_model->get_by_email($email);

        if (!$user) {
            return $this->json_response(array('status' => FALSE, 'message' => 'Email tidak ditemukan.'), 404);
        }

        $otp = (string) random_int(100000, 999999);

        $this->db->where('user_id', (int) $user['id'])->where('used_at IS NULL', NULL, FALSE)->delete('password_reset_tokens');
        $this->db->insert('password_reset_tokens', array(
            'user_id' => (int) $user['id'],
            'otp_code' => $otp,
            'expires_at' => date('Y-m-d H:i:s', strtotime('+10 minutes'))
        ));

        return $this->json_response(array(
            'status' => TRUE,
            'message' => 'Kode OTP berhasil dibuat.',
            'data' => array(
                'otp_debug' => $otp,
                'expires_in_minutes' => 10
            )
        ));
    }

    public function reset_password()
    {
        if ($this->request_method() !== 'POST') {
            return $this->json_response(array('status' => FALSE, 'message' => 'Method not allowed'), 405);
        }

        $payload = $this->request_data();
        $email = strtolower(trim(isset($payload['email']) ? $payload['email'] : ''));
        $otp = trim(isset($payload['otp']) ? $payload['otp'] : '');
        $password = isset($payload['password']) ? (string) $payload['password'] : '';
        $confirm_password = isset($payload['confirm_password']) ? (string) $payload['confirm_password'] : '';

        $user = $this->User_model->get_by_email($email);
        if (!$user) {
            return $this->json_response(array('status' => FALSE, 'message' => 'Email tidak ditemukan.'), 404);
        }

        if ($otp === '' || $password === '' || $confirm_password === '') {
            return $this->json_response(array('status' => FALSE, 'message' => 'Semua field reset password wajib diisi.'), 422);
        }

        if ($password !== $confirm_password) {
            return $this->json_response(array('status' => FALSE, 'message' => 'Konfirmasi kata sandi tidak cocok.'), 422);
        }

        $token = $this->db
            ->where('user_id', (int) $user['id'])
            ->where('otp_code', $otp)
            ->where('used_at IS NULL', NULL, FALSE)
            ->where('expires_at >=', date('Y-m-d H:i:s'))
            ->order_by('id', 'DESC')
            ->get('password_reset_tokens')
            ->row_array();

        if (!$token) {
            return $this->json_response(array('status' => FALSE, 'message' => 'OTP tidak valid atau sudah kedaluwarsa.'), 422);
        }

        $this->User_model->update($user['id'], array(
            'password' => password_hash($password, PASSWORD_DEFAULT)
        ));

        $this->db->where('id', (int) $token['id'])->update('password_reset_tokens', array(
            'used_at' => date('Y-m-d H:i:s')
        ));

        return $this->json_response(array(
            'status' => TRUE,
            'message' => 'Kata sandi berhasil diperbarui.'
        ));
    }

    private function serialize_user($user)
    {
        if (!$user) {
            return NULL;
        }

        return array(
            'id' => (int) $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role' => $user['role'],
            'photo' => !empty($user['photo']) ? base_url('uploads/profiles/' . $user['photo']) : NULL
        );
    }
}
