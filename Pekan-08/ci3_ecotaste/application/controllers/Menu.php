<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->_cek_login();
        $this->load->model('Menu_model');
        $this->load->library('upload');
    }

    public function index()
    {
        redirect('dashboard');
    }

    public function form($id = NULL)
    {
        $menu = NULL;

        if ($id !== NULL) {
            $menu = $this->Menu_model->get_by_id((int) $id);
            if (!$menu) {
                $this->session->set_flashdata('error', 'Data menu tidak ditemukan.');
                redirect('dashboard');
            }
        }

        $data['title'] = $menu ? 'Edit Menu' : 'Tambah Menu';
        $data['menu'] = $menu;

        $this->load->view('templates/header', $data);
        $this->load->view('menu/form', $data);
        $this->load->view('templates/footer');
    }

    public function simpan()
    {
        $id = (int) $this->input->post('id');
        $menu_lama = $id ? $this->Menu_model->get_by_id($id) : NULL;

        if ($id && !$menu_lama) {
            $this->session->set_flashdata('error', 'Data menu tidak ditemukan.');
            redirect('dashboard');
        }

        $this->form_validation->set_rules('nama', 'Nama Menu', 'required|trim');
        $this->form_validation->set_rules('deskripsi', 'Deskripsi', 'required|trim');
        $this->form_validation->set_rules('rating', 'Rating', 'required|numeric|greater_than_equal_to[0]|less_than_equal_to[5]');

        if ($this->form_validation->run() === FALSE) {
            $data['title'] = $id ? 'Edit Menu' : 'Tambah Menu';
            $data['menu'] = $menu_lama;

            $this->load->view('templates/header', $data);
            $this->load->view('menu/form', $data);
            $this->load->view('templates/footer');
            return;
        }

        $payload = array(
            'nama' => $this->input->post('nama', TRUE),
            'deskripsi' => $this->input->post('deskripsi', TRUE),
            'rating' => $this->input->post('rating', TRUE)
        );

        $uploaded_file = $menu_lama ? $menu_lama->gambar : NULL;

        if (!empty($_FILES['gambar']['name'])) {
            $config['upload_path'] = FCPATH . 'uploads/menu/';
            $config['allowed_types'] = 'jpg|jpeg|png|gif|webp';
            $config['max_size'] = 2048;
            $config['encrypt_name'] = TRUE;

            $this->upload->initialize($config);

            if (!$this->upload->do_upload('gambar')) {
                $data['title'] = $id ? 'Edit Menu' : 'Tambah Menu';
                $data['menu'] = $menu_lama;
                $data['upload_error'] = $this->upload->display_errors('', '');

                $this->load->view('templates/header', $data);
                $this->load->view('menu/form', $data);
                $this->load->view('templates/footer');
                return;
            }

            $upload_data = $this->upload->data();
            $uploaded_file = $upload_data['file_name'];

            if ($menu_lama && $menu_lama->gambar && file_exists(FCPATH . 'uploads/menu/' . $menu_lama->gambar)) {
                unlink(FCPATH . 'uploads/menu/' . $menu_lama->gambar);
            }
        }

        if (!$id && empty($uploaded_file)) {
            $data['title'] = 'Tambah Menu';
            $data['menu'] = NULL;
            $data['upload_error'] = 'Gambar wajib diunggah saat menambah data.';

            $this->load->view('templates/header', $data);
            $this->load->view('menu/form', $data);
            $this->load->view('templates/footer');
            return;
        }

        $payload['gambar'] = $uploaded_file;

        if ($id) {
            $this->Menu_model->update($id, $payload);
            $this->session->set_flashdata('success', 'Data menu berhasil diperbarui.');
        } else {
            $this->Menu_model->insert($payload);
            $this->session->set_flashdata('success', 'Data menu berhasil disimpan.');
        }

        redirect('dashboard');
    }

    public function hapus($id)
    {
        $menu = $this->Menu_model->get_by_id((int) $id);

        if (!$menu) {
            $this->session->set_flashdata('error', 'Data menu tidak ditemukan.');
            redirect('dashboard');
        }

        if ($menu->gambar && file_exists(FCPATH . 'uploads/menu/' . $menu->gambar)) {
            unlink(FCPATH . 'uploads/menu/' . $menu->gambar);
        }

        $this->Menu_model->delete((int) $id);
        $this->session->set_flashdata('success', 'Data menu berhasil dihapus.');
        redirect('dashboard');
    }

    private function _cek_login()
    {
        if (!$this->session->userdata('is_logged_in')) {
            redirect('login');
        }
    }
}
