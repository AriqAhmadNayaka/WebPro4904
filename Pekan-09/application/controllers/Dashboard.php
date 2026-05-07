<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->require_login();
        $this->load->model('User_model');
        $this->load->model('posts/Posts_model');
    }

    public function index()
    {
        $current_user = $this->current_user();
        $error = '';
        $success = '';

        if ($this->request_method() === 'POST' && $this->input->post('submit_action')) {
            if ($current_user['role'] === 'admin') {
                $result = $this->create_user_from_dashboard();
            } else {
                $result = $this->update_photo_from_dashboard($current_user['id']);
            }

            $error = $result['error'];
            $success = $result['success'];
        }

        $data = array(
            'user' => $current_user,
            'users' => $this->User_model->get_all(),
            'posts_count' => count($this->Posts_model->get_all()),
            'error' => $error,
            'success' => $success
        );

        $this->load->view('dashboard', $data);
    }

    public function edit_user($id = NULL)
    {
        $this->require_admin();

        $user = $this->User_model->get_by_id($id);
        if (!$user) {
            show_404();
        }

        $data = array(
            'user' => $user,
            'error' => '',
            'success' => ''
        );

        if ($this->request_method() === 'POST') {
            $name = trim($this->input->post('name', TRUE));
            $email = trim($this->input->post('email', TRUE));
            $role = $this->input->post('role', TRUE) === 'admin' ? 'admin' : 'user';
            $password = (string) $this->input->post('password');

            if ($name === '' || $email === '') {
                $data['error'] = 'Nama dan email wajib diisi.';
            } else {
                $existing = $this->User_model->get_by_email($email);
                if ($existing && (int) $existing['id'] !== (int) $user['id']) {
                    $data['error'] = 'Email sudah digunakan pengguna lain.';
                } else {
                    $update_data = array(
                        'name' => $name,
                        'email' => $email,
                        'role' => $role
                    );

                    if ($password !== '') {
                        $update_data['password'] = password_hash($password, PASSWORD_DEFAULT);
                    }

                    $upload = $this->handle_photo_upload('photo');
                    if ($upload['error'] !== '') {
                        $data['error'] = $upload['error'];
                    } else {
                        if ($upload['file_name'] !== '') {
                            $update_data['photo'] = $upload['file_name'];
                            $this->delete_photo_if_exists($user['photo']);
                        }

                        $this->User_model->update($user['id'], $update_data);
                        $data['success'] = 'Data user berhasil diperbarui.';
                        $user = $this->User_model->get_by_id($user['id']);
                        $data['user'] = $user;
                    }
                }
            }
        }

        $this->load->view('edit_user', $data);
    }

    public function delete_user($id = NULL)
    {
        $this->require_admin();

        $user = $this->User_model->get_by_id($id);
        if (!$user) {
            show_404();
        }

        if ($user['email'] === 'admin@cybervault.com') {
            $this->session->set_flashdata('dashboard_error', 'Admin utama tidak bisa dihapus.');
            redirect('dashboard');
        }

        $this->delete_photo_if_exists($user['photo']);
        $this->User_model->delete($user['id']);
        redirect('dashboard');
    }

    private function create_user_from_dashboard()
    {
        $name = trim($this->input->post('name', TRUE));
        $email = trim($this->input->post('email', TRUE));
        $password = (string) $this->input->post('password');

        if ($name === '' || $email === '' || $password === '') {
            return array('error' => 'Semua field wajib diisi untuk menambah user.', 'success' => '');
        }

        if ($this->User_model->get_by_email($email)) {
            return array('error' => 'Email sudah ada!', 'success' => '');
        }

        $upload = $this->handle_photo_upload('photo');
        if ($upload['error'] !== '') {
            return array('error' => $upload['error'], 'success' => '');
        }

        $this->User_model->insert(array(
            'name' => $name,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'role' => 'user',
            'photo' => $upload['file_name'] !== '' ? $upload['file_name'] : NULL
        ));

        return array('error' => '', 'success' => 'User berhasil ditambahkan!');
    }

    private function update_photo_from_dashboard($user_id)
    {
        $upload = $this->handle_photo_upload('photo');
        if ($upload['error'] !== '') {
            return array('error' => $upload['error'], 'success' => '');
        }

        if ($upload['file_name'] === '') {
            return array('error' => 'Silakan pilih file foto!', 'success' => '');
        }

        $user = $this->User_model->get_by_id($user_id);
        if (!$user) {
            return array('error' => 'User tidak ditemukan.', 'success' => '');
        }

        $this->User_model->update($user_id, array('photo' => $upload['file_name']));
        $this->delete_photo_if_exists($user['photo']);

        return array('error' => '', 'success' => 'Foto profil berhasil diperbarui!');
    }

    private function handle_photo_upload($field_name)
    {
        if (empty($_FILES[$field_name]['name'])) {
            return array('error' => '', 'file_name' => '');
        }

        $config['upload_path'] = FCPATH . 'uploads/profiles/';
        $config['allowed_types'] = 'jpg|jpeg|png|webp';
        $config['max_size'] = 2048;
        $config['encrypt_name'] = TRUE;

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload($field_name)) {
            return array('error' => strip_tags($this->upload->display_errors('', '')), 'file_name' => '');
        }

        $data = $this->upload->data();
        return array('error' => '', 'file_name' => $data['file_name']);
    }

    private function delete_photo_if_exists($photo)
    {
        if (!$photo) {
            return;
        }

        $path = FCPATH . 'uploads/profiles/' . $photo;
        if (is_file($path)) {
            @unlink($path);
        }
    }
}
