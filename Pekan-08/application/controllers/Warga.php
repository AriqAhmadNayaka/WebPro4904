<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Warga extends CI_Controller
{
    // Simpan lokasi upload sekali di awal biar nanti tinggal dipakai.
    private $upload_path;

    public function __construct()
    {
        parent::__construct();
        // Kalau belum login, jangan kasih masuk ke halaman ini.
        $this->auth_check();
        $this->load->model('Warga_model');
        $this->upload_path = FCPATH . 'uploads/';
    }

    public function index($id = NULL)
    {
        // Ambil data utama buat ditampilin di halaman warga.
        $data = array(
            'title' => 'Data Warga',
            'edit_data' => NULL,
            'warga' => $this->Warga_model->get_all()
        );

        if ($id !== NULL) {
            // Kalau ada id, berarti halaman ini lagi mode edit.
            $data['edit_data'] = $this->Warga_model->get_by_id((int) $id);
            if (!$data['edit_data']) {
                $this->session->set_flashdata('error', 'Data warga tidak ditemukan.');
                redirect('warga');
            }
        }

$this->load->view('layouts/header', $data);
        $this->load->view('warga/index', $data);
$this->load->view('layouts/footer');
    }

    public function save()
    {
        // Biar method ini cuma nerima submit dari form.
        if ($this->input->method() !== 'post') {
            redirect('warga');
        }

        $id = (int) $this->input->post('id');
        // Rapihin dulu input dari user sebelum diproses.
        $data = array(
            'nama' => trim($this->input->post('nama', TRUE)),
            'alamat' => trim($this->input->post('alamat', TRUE)),
            'nohp' => trim($this->input->post('nohp', TRUE))
        );

        if ($data['nama'] === '' || $data['alamat'] === '' || $data['nohp'] === '') {
            $this->session->set_flashdata('error', 'Semua field wajib diisi.');
            redirect($id ? 'warga/index/' . $id : 'warga');
        }

        // Kalau ada id berarti kita cek data lamanya dulu.
        $existing = $id ? $this->Warga_model->get_by_id($id) : NULL;
        if ($id && !$existing) {
            $this->session->set_flashdata('error', 'Data warga tidak ditemukan.');
            redirect('warga');
        }

        $uploaded_file = $existing ? $existing->file : NULL;
        if (!empty($_FILES['file']['name'])) {
            // Kalau user upload file baru, kita proses di sini.
            $result = $this->do_upload();
            if (!$result['status']) {
                $this->session->set_flashdata('error', $result['message']);
                redirect($id ? 'warga/index/' . $id : 'warga');
            }

            $uploaded_file = $result['file_name'];
            if ($existing && $existing->file) {
                // File lama dibersihin biar nggak numpuk.
                $this->remove_file($existing->file);
            }
        } elseif (!$existing) {
            // Pas nambah data baru, file memang wajib ada.
            $this->session->set_flashdata('error', 'File wajib diupload saat menambah data.');
            redirect('warga');
        }

        $data['file'] = $uploaded_file;

        if ($id) {
            // Kalau ada id, berarti update data yang lama.
            $this->Warga_model->update($id, $data);
            $this->session->set_flashdata('success', 'Data warga berhasil diperbarui.');
        } else {
            // Kalau belum ada id, berarti simpan sebagai data baru.
            $this->Warga_model->insert($data);
            $this->session->set_flashdata('success', 'Data warga berhasil disimpan.');
        }

        redirect('warga');
    }

    public function delete($id)
    {
        // Ambil dulu datanya, soalnya kita butuh cek file juga.
        $warga = $this->Warga_model->get_by_id((int) $id);
        if (!$warga) {
            $this->session->set_flashdata('error', 'Data warga tidak ditemukan.');
            redirect('warga');
        }

        if ($warga->file) {
            // Sekalian hapus file yang nempel ke data ini.
            $this->remove_file($warga->file);
        }

        $this->Warga_model->delete((int) $id);
        $this->session->set_flashdata('success', 'Data warga berhasil dihapus.');
        redirect('warga');
    }

    private function do_upload()
    {
        if (!is_dir($this->upload_path)) {
            // Kalau folder upload belum ada, bikin dulu.
            mkdir($this->upload_path, 0777, TRUE);
        }

        $config = array(
            'upload_path'   => $this->upload_path,
            'allowed_types' => 'jpg|jpeg|png|pdf|doc|docx',
            'max_size'      => 0,
            'encrypt_name'  => TRUE
        );

        $this->upload->initialize($config);

        if (!$this->upload->do_upload('file')) {
            return array(
                'status' => FALSE,
                'message' => strip_tags($this->upload->display_errors('', ''))
            );
        }

        $upload_data = $this->upload->data();

        return array(
            'status' => TRUE,
            'file_name' => $upload_data['file_name']
        );
    }

    private function remove_file($file_name)
    {
        $file_path = $this->upload_path . $file_name;
        if (is_file($file_path)) {
            // Hapus file fisiknya kalau memang masih ada.
            unlink($file_path);
        }
    }

    private function auth_check()
    {
        // Simple aja: belum login ya balik ke halaman auth.
        if (!$this->session->userdata('login')) {
            redirect('auth');
        }
    }
}
