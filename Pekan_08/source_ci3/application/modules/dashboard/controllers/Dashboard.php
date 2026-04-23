<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('dashboard/Laporan_model', 'laporan');
        $this->load->library('upload');

        if (!$this->session->userdata('logged_in')) {
            $this->session->set_flashdata('message', array(
                'type' => 'error',
                'text' => 'Silakan login terlebih dahulu.',
            ));
            redirect('login');
        }
    }

    public function index()
    {
        $edit_id = (int) $this->input->get('edit');
        $summary = $this->laporan->get_summary();

        $data = array(
            'username' => (string) $this->session->userdata('username'),
            'message' => $this->session->flashdata('message'),
            'summary' => $summary,
            'saldo' => (int) $summary['total_masuk'] - (int) $summary['total_keluar'],
            'rows' => $this->laporan->get_all(),
            'edit' => $edit_id > 0 ? $this->laporan->find($edit_id) : NULL,
        );

        $this->load->view('dashboard/index', $data);
    }

    public function save()
    {
        $id = (int) $this->input->post('id');
        $mode = $this->input->post('mode') === 'update' ? 'update' : 'create';
        $existing_file = trim((string) $this->input->post('existing_file'));

        $payload = array(
            'tanggal' => trim((string) $this->input->post('tanggal', TRUE)),
            'keterangan' => trim((string) $this->input->post('keterangan', TRUE)),
            'jenis' => trim((string) $this->input->post('jenis', TRUE)),
            'jumlah' => (int) $this->input->post('jumlah', TRUE),
        );

        if ($payload['tanggal'] === '' || $payload['keterangan'] === '' || $payload['jenis'] === '' || $payload['jumlah'] <= 0) {
            $this->set_flash('error', 'Semua field wajib diisi dan jumlah harus lebih dari 0.');
            redirect($mode === 'update' && $id > 0 ? site_url('dashboard') . '?edit=' . $id : 'dashboard');
        }

        if (!in_array($payload['jenis'], array('Pemasukan', 'Pengeluaran'), TRUE)) {
            $this->set_flash('error', 'Jenis transaksi tidak valid.');
            redirect($mode === 'update' && $id > 0 ? site_url('dashboard') . '?edit=' . $id : 'dashboard');
        }

        $uploaded_file = $existing_file;
        $upload_result = $this->handle_upload('file');

        if (!$upload_result['status']) {
            $this->set_flash('error', $upload_result['message']);
            redirect($mode === 'update' && $id > 0 ? site_url('dashboard') . '?edit=' . $id : 'dashboard');
        }

        if ($upload_result['filename'] !== '') {
            $uploaded_file = $upload_result['filename'];
        }

        $payload['foto'] = $uploaded_file !== '' ? $uploaded_file : NULL;

        if ($mode === 'update' && $id > 0) {
            $current = $this->laporan->find($id);

            if (!$current) {
                $this->set_flash('error', 'Data yang ingin diedit tidak ditemukan.');
                redirect('dashboard');
            }

            if ($upload_result['filename'] !== '' && !empty($current->foto)) {
                $this->delete_upload($current->foto);
            }

            $this->laporan->update($id, $payload);
            $this->set_flash('success', 'Data berhasil diperbarui.');
            redirect('dashboard');
        }

        $this->laporan->insert($payload);
        $this->set_flash('success', 'Data berhasil disimpan.');
        redirect('dashboard');
    }

    public function delete($id = 0)
    {
        $id = (int) $id;
        $row = $this->laporan->find($id);

        if ($row) {
            if (!empty($row->foto)) {
                $this->delete_upload($row->foto);
            }

            $this->laporan->delete($id);
            $this->set_flash('success', 'Data berhasil dihapus.');
        } else {
            $this->set_flash('error', 'Data tidak ditemukan.');
        }

        redirect('dashboard');
    }

    private function handle_upload($field_name)
    {
        if (empty($_FILES[$field_name]['name'])) {
            return array(
                'status' => TRUE,
                'filename' => '',
                'message' => '',
            );
        }

        $config = array(
            'upload_path' => FCPATH . 'uploads/',
            'allowed_types' => 'jpg|jpeg|png|pdf|doc|docx',
            'max_size' => 4096,
            'encrypt_name' => TRUE,
        );

        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0777, TRUE);
        }

        $this->upload->initialize($config);

        if (!$this->upload->do_upload($field_name)) {
            return array(
                'status' => FALSE,
                'filename' => '',
                'message' => strip_tags($this->upload->display_errors('', '')),
            );
        }

        $data = $this->upload->data();

        return array(
            'status' => TRUE,
            'filename' => $data['file_name'],
            'message' => '',
        );
    }

    private function delete_upload($filename)
    {
        $path = FCPATH . 'uploads/' . $filename;

        if ($filename !== '' && is_file($path)) {
            @unlink($path);
        }
    }

    private function set_flash($type, $text)
    {
        $this->session->set_flashdata('message', array(
            'type' => $type,
            'text' => $text,
        ));
    }
}
