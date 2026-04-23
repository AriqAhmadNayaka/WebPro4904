<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        // Controller admin fokus ke manajemen data dokter.
        $this->load->model('Doctor_model');
        $this->load->library('File_service');

        // Semua halaman admin harus lewat login.
        if (!$this->session->userdata('isLogin')) {
            redirect(base_url('index.php?c=auth&m=login'));
        }
    }

    public function index()
    {
        // Ambil semua data dokter untuk ditampilkan di tabel dashboard.
        $data['dokter'] = $this->Doctor_model->all();
        $this->load->view('admin/index', $data);
    }

    public function create()
    {
        $data = ['success_msg' => '', 'error_msg' => ''];

        if ($this->input->method(TRUE) === 'POST') {
            $dokumen = $_FILES['sertifikat'] ?? null;
            $upload = $this->file_service->upload($dokumen);

            if ($upload['status'] === 'success') {
                // Ambil semua input form lalu tambahkan nama file upload.
                $post = $this->input->post(NULL, TRUE);
                unset($post['tambah']);
                $post['sertifikat'] = $upload['filename'];

                try {
                    $this->Doctor_model->create($post);
                    $this->session->set_flashdata('success_msg', 'Dokter berhasil ditambahkan!');
                    redirect(base_url('index.php?c=admin&m=create'));
                } catch (Exception $e) {
                    $data['error_msg'] = 'Error! pastikan email dan kode dokter belum terdaftar!';
                }
            } else {
                $data['error_msg'] = 'Gagal upload sertifikat: ' . $upload['msg'];
            }
        }

        $data['success_msg'] = $this->session->flashdata('success_msg');
        $this->load->view('admin/create', $data);
    }

    public function edit($id = null)
    {
        // Di mode query-string, id dikirim lewat ?id=...
        if ($id === null) {
            $id = (int) $this->input->get('id');
        }

        if (empty($id)) {
            redirect(base_url('index.php?c=admin&m=index'));
        }

        // Cari data lama dulu untuk isi default form edit.
        $old_data = $this->Doctor_model->find((int) $id);
        if (!$old_data) {
            redirect(base_url('index.php?c=admin&m=index'));
        }

        $data = [
            'oldData' => $old_data,
            'success_msg' => '',
            'error_msg' => '',
        ];

        if ($this->input->method(TRUE) === 'POST') {
            // Data text diambil dari form edit.
            $update_data = [
                'nama' => $this->input->post('nama', TRUE),
                'spesialisasi' => $this->input->post('spesialisasi', TRUE),
                'email' => $this->input->post('email', TRUE),
                'telepon' => $this->input->post('telepon', TRUE),
                'nomor_sip' => $this->input->post('nomor_sip', TRUE),
                'kode_dokter' => $this->input->post('kode_dokter', TRUE),
                'bio' => $this->input->post('bio', TRUE),
            ];

            // Kalau ada file baru, handle ganti file + hapus file lama.
            $file_update = $this->file_service->handle_update($_FILES['sertifikat'], $old_data['sertifikat'] ?? '');

            if ($file_update['status'] !== 'error') {
                $update_data['sertifikat'] = $file_update['filename'];

                try {
                    $this->Doctor_model->update_data((int) $id, $update_data);
                    $data['success_msg'] = 'Data dokter berhasil diperbarui!';
                    $data['oldData'] = array_merge($old_data, $update_data);
                } catch (Exception $e) {
                    $data['error_msg'] = 'Gagal memperbarui data!';
                }
            } else {
                $data['error_msg'] = 'Gagal update sertifikat: ' . $file_update['msg'];
            }
        }

        $this->load->view('admin/edit', $data);
    }

    public function delete($id = null)
    {
        // Di mode query-string, id dikirim lewat ?id=...
        if ($id === null) {
            $id = (int) $this->input->get('id');
        }

        if (empty($id)) {
            redirect(base_url('index.php?c=admin&m=index'));
        }

        $dokter = $this->Doctor_model->find((int) $id);
        if (!$dokter) {
            redirect(base_url('index.php?c=admin&m=index'));
        }

        // Rapikan file sertifikat fisik saat data dokter dihapus.
        if (!empty($dokter['sertifikat'])) {
            $this->file_service->delete($dokter['sertifikat']);
        }

        if ($this->Doctor_model->delete_data((int) $id)) {
            redirect(base_url('index.php?c=admin&m=index&deleted=success'));
        }

        redirect(base_url('index.php?c=admin&m=index&deleted=error'));
    }
}
