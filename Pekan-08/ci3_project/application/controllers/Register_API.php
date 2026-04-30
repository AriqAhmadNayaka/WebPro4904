<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Register_API extends CI_Controller
{

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

        $teks = [
            'username' => isset($input['username']) && $input['username'] !== '' ? $input['username'] : 'grandy',
            'password' => isset($input['password']) && $input['password'] !== '' ? $input['password'] : 'grandy123',
            'email' => isset($input['email']) && $input['email'] !== '' ? $input['email'] : 'miaw'
        ];

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($teks));
            
    }
}
