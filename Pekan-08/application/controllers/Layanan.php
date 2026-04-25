<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Layanan extends CI_Controller { //

    public function __construct() {
        parent::__construct(); // Panggil constructor CI_Controller
        $this->load->model('Layanan_model'); // Load model Layanan_model untuk akses database
        $this->load->library(array('session', 'upload')); // Load library session untuk manajemen login dan upload untuk proses file upload
        $this->load->helper('url'); // Load helper url untuk redirect

        // Cek login
        if (!$this->session->userdata('login')) {
            redirect('auth');
        }
    }

    // Tampil semua data + form tambah/edit (pengganti tampilSemua() + GET handler)
    public function index() {
        $data['list_layanan'] = $this->Layanan_model->get_all(); // Ambil semua data layanan untuk ditampilkan di tabel
        $data['edit_data']    = null; // Default null, akan diisi jika ada request edit (?edit=ID)
        $data['nama_dokter']  = $this->session->userdata('name'); // Ambil nama dari session untuk ditampilkan di dashboard
        $data['profile_pic']  = base_url('assets/img/calm pfp.jpg'); // Gambar profil statis untuk contoh

        // Jika ada request edit (?edit=ID)
        if ($this->input->get('edit')) {
            $data['edit_data'] = $this->Layanan_model->get_by_id($this->input->get('edit'));
        }

        $this->load->view('layanan/index', $data);
    }

    // Proses simpan data baru (pengganti bagian INSERT di simpan())
    public function store() {
        $file_name = '';

        // Proses upload file (pengganti move_uploaded_file())
        if (!empty($_FILES['file']['name'])) {
            $config_upload = array(
                'upload_path'   => './uploads/',
                'allowed_types' => 'jpg|jpeg|png|gif',
                'max_size'      => 2048,
                'file_name'     => time() . '_' . $_FILES['file']['name'],
                'overwrite'     => FALSE,
            );
            $this->upload->initialize($config_upload);

            if ($this->upload->do_upload('file')) {
                $file_name = $this->upload->data('file_name');
            } else {
                $this->session->set_flashdata('error', $this->upload->display_errors('', ''));
                redirect('layanan');
                return;
            }
        }

        $insert_data = array(
            'judul'     => $this->input->post('judul'),
            'kategori'  => $this->input->post('kategori'),
            'deskripsi' => $this->input->post('deskripsi'),
            'file'      => $file_name,
        );

        if ($this->Layanan_model->insert($insert_data)) {
            $this->session->set_flashdata('success', 'Informasi berhasil disimpan!');
        } else {
            $this->session->set_flashdata('error', 'Gagal menyimpan data.');
        }

        redirect('layanan');
    }

    // Proses update data (pengganti bagian UPDATE di simpan())
    public function update($id) {
        $update_data = array(
            'judul'     => $this->input->post('judul'),
            'kategori'  => $this->input->post('kategori'),
            'deskripsi' => $this->input->post('deskripsi'),
        );

        // Upload file baru jika ada (pengganti logika update file di simpan())
        if (!empty($_FILES['file']['name'])) {
            $config_upload = array(
                'upload_path'   => './uploads/',
                'allowed_types' => 'jpg|jpeg|png|gif',
                'max_size'      => 2048,
                'file_name'     => time() . '_' . $_FILES['file']['name'],
                'overwrite'     => FALSE,
            );
            $this->upload->initialize($config_upload);

            if ($this->upload->do_upload('file')) {
                $update_data['file'] = $this->upload->data('file_name');
            } else {
                $this->session->set_flashdata('error', $this->upload->display_errors('', ''));
                redirect('layanan');
                return;
            }
        }
        // Jika tidak ada file baru, field 'file' tidak diupdate (data lama tetap)

        if ($this->Layanan_model->update($id, $update_data)) {
            $this->session->set_flashdata('success', 'Informasi berhasil diperbarui!');
        } else {
            $this->session->set_flashdata('error', 'Gagal memperbarui data.');
        }

        redirect('layanan');
    }

    // Hapus data (pengganti hapus())
    public function hapus($id) {
        if ($this->Layanan_model->delete($id)) {
            $this->session->set_flashdata('success', 'Data berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus data.');
        }
        redirect('layanan');
    }
}