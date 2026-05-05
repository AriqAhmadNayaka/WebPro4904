<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Datauser extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Datauser_model');
        $this->load->library('session');
        $this->load->helper('form');   // Tambahan

        if (!$this->session->userdata('user_id')) {
            redirect('auth/login');
        }
    }

    public function index() {
        $data['users'] = $this->Datauser_model->get_all();
        $this->load->view('templates/header');
        $this->load->view('datauser/index', $data);
        $this->load->view('templates/footer');
    }

    public function create() {
    if ($this->input->post()) {
        $nama  = trim($this->input->post('nama'));
        $email = trim($this->input->post('email'));

        if (empty($nama) || empty($email)) {
            $this->session->set_flashdata('error', 'Nama dan Email wajib diisi!');
            redirect('datauser/create');
        }

        if ($this->Datauser_model->create($nama, $email)) {
            $this->session->set_flashdata('success', 'Data berhasil ditambahkan!');
            redirect('datauser');
        } else {
            $this->session->set_flashdata('error', 'Gagal menyimpan data!');
            redirect('datauser/create');
        }
    }

    $this->load->view('datauser/create');
}

    public function edit($id) {
    $data['row'] = $this->Datauser_model->get_by_id($id);
    if (!$data['row']) {
        redirect('datauser');
    }

    if ($this->input->post()) {
        $nama  = trim($this->input->post('nama'));
        $email = trim($this->input->post('email'));

        if ($this->Datauser_model->update($id, $nama, $email)) {
            $this->session->set_flashdata('success', 'Data berhasil diupdate!');
            redirect('datauser');
        } else {
            $this->session->set_flashdata('error', 'Gagal mengupdate data!');
        }
    }

    $this->load->view('datauser/edit', $data);
}

    public function delete($id) {
        if ($this->Datauser_model->delete($id)) {
            $this->session->set_flashdata('success', 'Data berhasil dihapus!');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus data!');
        }
        redirect('datauser');
    }
}