<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Controller ini menangani alur autentikasi pengguna.
class Auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        // Memuat model user untuk proses login dan register.
        $this->load->model('User_model');
    }

    public function index()
    {
        // Jika user sudah login, arahkan langsung ke dashboard.
        if ($this->session->userdata('user_id')) {
            redirect('dashboard');
        }

        $data['title'] = 'Login | InkluSkill';

        if ($this->input->method() === 'post') {
            // Validasi input login sebelum dicek ke database.
            $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email');
            $this->form_validation->set_rules('password', 'Password', 'required|trim');

            if ($this->form_validation->run()) {
                // Cek kecocokan email dan password melalui model.
                $user = $this->User_model->authenticate(
                    $this->input->post('email', true),
                    $this->input->post('password', false)
                );

                if ($user) {
                    // Simpan data penting user ke session saat login berhasil.
                    $this->session->set_userdata(array(
                        'user_id' => $user->id,
                        'username' => $user->username,
                        'email' => $user->email,
                        'role' => $user->role,
                        'logged_in' => true,
                    ));

                    $this->session->unset_userdata('error');
                    $this->session->set_flashdata('success', 'Login berhasil. Selamat datang di dashboard.');
                    redirect('dashboard');
                }

                $this->session->unset_userdata('success');
                $this->session->set_flashdata('error', 'Email atau password tidak cocok.');
                redirect('login');
            }
        }

        $this->load->view('auth/login', $data);
    }

    public function register()
    {
        // User yang sudah login tidak perlu membuka halaman register lagi.
        if ($this->session->userdata('user_id')) {
            redirect('dashboard');
        }

        $data['title'] = 'Register | InkluSkill';

        if ($this->input->method() === 'post') {
            // Validasi data register sebelum disimpan ke database.
            $this->form_validation->set_rules('username', 'Nama Lengkap', 'required|trim|min_length[3]');
            $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[ci3_users.email]');
            $this->form_validation->set_rules('password', 'Password', 'required|trim|min_length[5]');

            if ($this->form_validation->run()) {
                // Password disimpan dalam bentuk hash agar lebih aman.
                $payload = array(
                    'username' => $this->input->post('username', true),
                    'email' => $this->input->post('email', true),
                    'password' => password_hash($this->input->post('password', false), PASSWORD_DEFAULT),
                    'role' => 'sekolah',
                );

                $this->User_model->create($payload);
                $this->session->unset_userdata('error');
                $this->session->set_flashdata('success', 'Registrasi berhasil. Silakan login.');
                redirect('login');
            }
        }

        $this->load->view('auth/register', $data);
    }
     public function register_API()
    {
        // User yang sudah login tidak perlu membuka halaman register lagi.
        if ($this->session->userdata('user_id')) {
            redirect('dashboard');
        }

        $data['title'] = 'Register | InkluSkill';

        if ($this->input->method() === 'post') {
            // Validasi data register sebelum disimpan ke database.
            $this->form_validation->set_rules('username', 'Nama Lengkap', 'required|trim|min_length[3]');
            $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[ci3_users.email]');
            $this->form_validation->set_rules('password', 'Password', 'required|trim|min_length[5]');

            if ($this->form_validation->run()) {
                // Password disimpan dalam bentuk hash agar lebih aman.
                $payload = array(
                    'username' => $this->input->post('username', true),
                    'email' => $this->input->post('email', true),
                    'password' => password_hash($this->input->post('password', false), PASSWORD_DEFAULT),
                    'role' => 'sekolah',
                );
                 $payload2 = [
                    'username' => $this->input->post('username', true),
                    'email' => $this->input->post('email', true),
                    'password' => password_hash($this->input->post('password', false), PASSWORD_DEFAULT),
                    'role' => 'sekolah',
                 ];

                $this->User_model->create($payload);
               // $this->session->unset_userdata('error');
                //$this->session->set_flashdata('success', 'Registrasi berhasil. Silakan login.');
                //redirect('login');

                }
                }
                
                echo json_encode($payload2);

       //$this->load->view('auth/register_API', $data);
    }

    public function logout()
    {
        // Hapus session user saat logout.
        $this->session->sess_destroy();
        redirect('login');
    }
}
