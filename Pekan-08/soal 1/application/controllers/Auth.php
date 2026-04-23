<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        // Semua kebutuhan autentikasi kita siapkan di constructor
        // supaya method login/register tidak perlu load berulang.
        $this->load->model('User_model');
        $this->load->model('Doctor_model');
        $this->load->library('File_service');
    }

    public function login()
    {
        // Kalau user sudah login, tidak perlu lihat halaman login lagi.
        if ($this->session->userdata('isLogin')) {
            redirect(base_url('index.php?c=admin&m=index'));
        }

        $data = ['error' => ''];

        // Halaman login juga menangani submit form POST.
        if ($this->input->method(TRUE) === 'POST') {
            $email = $this->input->post('email', TRUE);
            $password = (string) $this->input->post('password');

            // Cari user berdasarkan email terlebih dulu.
            $user = $this->User_model->find_by_email($email);

            if (!$user) {
                $data['error'] = 'Email tidak ditemukan!';
            } elseif (!password_verify($password, $user['password'])) {
                $data['error'] = 'Password salah!';
            } else {
                // Login sukses: simpan data penting ke session.
                $this->session->set_userdata([
                    'isLogin' => true,
                    'username' => $user['name'],
                    'user_id' => $user['id'] ?? null,
                ]);
                redirect(base_url('index.php?c=admin&m=index'));
            }
        }

        $this->load->view('auth/login', $data);
    }

    public function index()
    {
        // Saat user hit /index.php, arahkan ke endpoint login.
        redirect(base_url('index.php?c=auth&m=login'));
    }

    public function register()
    {
        // Sama seperti login, user aktif tidak perlu register ulang.
        if ($this->session->userdata('isLogin')) {
            redirect(base_url('index.php?c=admin&m=index'));
        }

        $data = ['error' => '', 'success' => ''];

        // Halaman register sekaligus proses simpan akun.
        if ($this->input->method(TRUE) === 'POST') {
            $name = $this->input->post('name', TRUE);
            $email = $this->input->post('email', TRUE);
            $password = (string) $this->input->post('password');
            $confirm_password = (string) $this->input->post('confirm_password');
            $role = $this->input->post('role', TRUE);

            // Cegah email ganda.
            $existing = $this->User_model->find_by_email($email);

            if ($existing) {
                $data['error'] = 'Email sudah terdaftar!';
            } elseif ($password !== $confirm_password) {
                $data['error'] = 'Password dan Konfirmasi Password tidak cocok!';
            } else {
                $doctor_data = null;

                // Role dokter wajib upload dokumen sertifikasi.
                if ($role === 'doctor') {
                    if (isset($_FILES['cert_file']) && (int) $_FILES['cert_file']['error'] === 0) {
                        $upload = $this->file_service->upload($_FILES['cert_file']);
                        if ($upload['status'] === 'success') {
                            $doctor_data = [
                                'nama' => $name,
                                'sertifikat' => $upload['filename'],
                                'email' => $email,
                            ];
                        } else {
                            $data['error'] = $upload['msg'];
                        }
                    } else {
                        $data['error'] = 'File sertifikasi (STR/SIP) wajib diupload untuk dokter!';
                    }
                }

                if ($data['error'] === '') {
                    // Simpan akun user dulu ke tabel users.
                    $saved = $this->User_model->register($name, $email, $password, $role);

                    if ($saved) {
                        // Kalau role dokter, simpan profil dokter juga.
                        if ($role === 'doctor' && $doctor_data !== null) {
                            $this->Doctor_model->create($doctor_data);
                        }
                        $data['success'] = 'Registrasi berhasil! Silakan login.';
                    } else {
                        $data['error'] = 'Terjadi kesalahan saat registrasi.';
                    }
                }
            }
        }

        $this->load->view('auth/register', $data);
    }

    public function logout()
    {
        // Bersihkan session lalu kirim user balik ke login.
        $this->session->sess_destroy();
        redirect(base_url('index.php?c=auth&m=login'));
    }
}
