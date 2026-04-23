<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Wishlist extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Proteksi — hanya bisa diakses kalau sudah login
        if (!$this->session->userdata('user_login')) {
            redirect('login');
        }
        $this->load->model('Wishlist_model');
        $this->load->library(array('session', 'form_validation', 'upload'));
        $this->load->helper(array('url', 'form', 'file'));
    }

    // READ — tampilkan semua wishlist
    public function index() {
        $data['title']     = 'Wishlist Wisata - WeBandoo+';
        $data['user']      = $this->session->userdata('user_login');
        $data['wishlists'] = $this->Wishlist_model->ambil_semua();
        $this->load->view('layouts/header', $data);
        $this->load->view('wishlist/index', $data);
        $this->load->view('layouts/footer');
    }

    // CREATE — form tambah data
    public function tambah() {
        $data['title'] = 'Tambah Wishlist - WeBandoo+';
        $data['user']  = $this->session->userdata('user_login');
        $this->load->view('layouts/header', $data);
        $this->load->view('wishlist/tambah', $data);
        $this->load->view('layouts/footer');
    }

    // CREATE — proses simpan (CRUD + upload dalam satu tombol Simpan)
    public function simpan() {
        // Validasi form
        $this->form_validation->set_rules('nama',      'Nama Tempat', 'required');
        $this->form_validation->set_rules('deskripsi', 'Deskripsi',   'required');
        $this->form_validation->set_rules('lokasi',    'Lokasi',      'required');
        $this->form_validation->set_rules('harga',     'Harga',       'required');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('wishlist/tambah');
        }

        // Proses upload gambar
        $nama_gambar = $this->_upload_gambar();
        if ($nama_gambar === FALSE) {
            $this->session->set_flashdata('error', $this->upload->display_errors('', ''));
            redirect('wishlist/tambah');
        }

        // Simpan ke database
        $data = array(
            'nama'      => $this->input->post('nama'),
            'deskripsi' => $this->input->post('deskripsi'),
            'lokasi'    => $this->input->post('lokasi'),
            'harga'     => $this->input->post('harga'),
            'gambar'    => $nama_gambar,
        );

        $this->Wishlist_model->tambah($data);
        $this->session->set_flashdata('success', 'Data wishlist berhasil ditambahkan!');
        redirect('wishlist');
    }

    // UPDATE — form edit data
    public function edit($id) {
        $wishlist = $this->Wishlist_model->ambil_by_id($id);
        if (!$wishlist) {
            show_404();
        }
        $data['title']    = 'Edit Wishlist - WeBandoo+';
        $data['user']     = $this->session->userdata('user_login');
        $data['wishlist'] = $wishlist;
        $this->load->view('layouts/header', $data);
        $this->load->view('wishlist/edit', $data);
        $this->load->view('layouts/footer');
    }

    // UPDATE — proses simpan edit (satu tombol Simpan untuk CRUD + upload)
    public function update($id) {
        $wishlist = $this->Wishlist_model->ambil_by_id($id);
        if (!$wishlist) {
            show_404();
        }

        $this->form_validation->set_rules('nama',      'Nama Tempat', 'required');
        $this->form_validation->set_rules('deskripsi', 'Deskripsi',   'required');
        $this->form_validation->set_rules('lokasi',    'Lokasi',      'required');
        $this->form_validation->set_rules('harga',     'Harga',       'required');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('wishlist/edit/' . $id);
        }

        $data = array(
            'nama'      => $this->input->post('nama'),
            'deskripsi' => $this->input->post('deskripsi'),
            'lokasi'    => $this->input->post('lokasi'),
            'harga'     => $this->input->post('harga'),
        );

        // Upload gambar baru kalau ada
        if (!empty($_FILES['gambar']['name'])) {
            $nama_gambar = $this->_upload_gambar();
            if ($nama_gambar === FALSE) {
                $this->session->set_flashdata('error', $this->upload->display_errors('', ''));
                redirect('wishlist/edit/' . $id);
            }
            // Hapus gambar lama
            $path_lama = './uploads/wishlist/' . $wishlist->gambar;
            if (file_exists($path_lama)) {
                unlink($path_lama);
            }
            $data['gambar'] = $nama_gambar;
        }

        $this->Wishlist_model->update($id, $data);
        $this->session->set_flashdata('success', 'Data wishlist berhasil diperbarui!');
        redirect('wishlist');
    }

    // DELETE — hapus data + gambar
    public function hapus($id) {
        $wishlist = $this->Wishlist_model->ambil_by_id($id);
        if (!$wishlist) {
            show_404();
        }
        // Hapus file gambar dari folder
        $path = './uploads/wishlist/' . $wishlist->gambar;
        if (file_exists($path)) {
            unlink($path);
        }
        $this->Wishlist_model->hapus($id);
        $this->session->set_flashdata('success', 'Data wishlist berhasil dihapus!');
        redirect('wishlist');
    }

    // Helper private — proses upload gambar
    private function _upload_gambar() {
        $config = array(
            'upload_path'   => './uploads/wishlist/',
            'allowed_types' => 'jpg|jpeg|png|webp',
            'max_size'      => 2048, // 2MB
            'file_name'     => time() . '_' . rand(1000, 9999),
            'overwrite'     => FALSE,
        );
        $this->upload->initialize($config);

        if (!is_dir('./uploads/wishlist/')) {
            mkdir('./uploads/wishlist/', 0777, TRUE);
        }

        if ($this->upload->do_upload('gambar')) {
            return $this->upload->data('file_name');
        }
        return FALSE;
    }
}
