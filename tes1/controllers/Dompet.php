<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dompet extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Laporan_model');
        $this->load->library(array('session', 'upload'));
        $this->load->helper(array('url', 'form'));

        // Cek cookie → set session jika ada
        if (isset($_COOKIE['username']) && !$this->session->userdata('username')) {
            $this->session->set_userdata('username', $_COOKIE['username']);
        }

        // Cek login
        if (!$this->session->userdata('username')) {
            redirect('auth');
        }
    }

    // =====================
    // INDEX — tampil semua data
    // =====================
    public function index()
    {
        $username = $this->session->userdata('username');
        $data['dataLaporan'] = $this->Laporan_model->get_all($username);
        $data['editData']    = null;

        // Mode edit
        if ($this->input->get('edit')) {
            $data['editData'] = $this->Laporan_model->get_by_id($this->input->get('edit'));
        }

        $this->load->view('dompet/index', $data);
    }

    // =====================
    // SIMPAN — insert atau update dalam 1 tombol
    // =====================
    public function simpan()
    {
        $username = $this->session->userdata('username');
        $id       = $this->input->post('id');

        $post_data = array(
            'tanggal'    => $this->input->post('tanggal'),
            'keterangan' => $this->input->post('keterangan'),
            'jenis'      => $this->input->post('jenis'),
            'jumlah'     => $this->input->post('jumlah'),
            'username'   => $username,
        );

        // Handle upload file
        $file_name = '';
        if (!empty($_FILES['file']['name'])) {
            $upload_result = $this->_upload_file();
            if ($upload_result['status']) {
                $file_name = $upload_result['file_name'];
            } else {
                $this->session->set_flashdata('error', $upload_result['error']);
                redirect('dompet');
                return;
            }
        }

        if ($id && $id != '') {
            // ===== UPDATE =====
            $old_data = $this->Laporan_model->get_by_id($id);

            // Jika tidak ada file baru, pakai file lama
            if ($file_name == '') {
                $file_name = $old_data['file'];
            }

            $post_data['file'] = $file_name;
            $this->Laporan_model->update($id, $post_data, $username);
            $this->session->set_flashdata('success', 'Data berhasil diupdate');
        } else {
            // ===== INSERT =====
            $post_data['file'] = $file_name;
            $this->Laporan_model->insert($post_data);
            $this->session->set_flashdata('success', 'Data berhasil ditambahkan');
        }

        redirect('dompet');
    }

    // =====================
    // HAPUS
    // =====================
    public function hapus($id)
    {
        $username = $this->session->userdata('username');
        $this->Laporan_model->delete($id, $username);
        $this->session->set_flashdata('success', 'Data berhasil dihapus');
        redirect('dompet');
    }

    // =====================
    // Private: Upload file
    // =====================
    private function _upload_file()
    {
        $upload_path = './uploads/';

        // Buat folder jika belum ada
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0777, true);
        }

        $config['upload_path']   = $upload_path;
        $config['allowed_types'] = 'jpg|jpeg|png|pdf';
        $config['max_size']      = 2048; // 2MB
        $config['file_name']     = time() . '_' . $_FILES['file']['name'];
        $config['overwrite']     = FALSE;

        $this->upload->initialize($config);

        if ($this->upload->do_upload('file')) {
            $upload_data = $this->upload->data();
            return array(
                'status'    => TRUE,
                'file_name' => $upload_data['file_name'],
            );
        } else {
            return array(
                'status' => FALSE,
                'error'  => $this->upload->display_errors('', ''),
            );
        }
    }
}
