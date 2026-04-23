<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller Proyek
 *
 * Menangani semua fitur dashboard dan CRUD proyek:
 * - index()    : Tampilkan daftar proyek + form tambah/edit
 * - simpan()   : POST → Create atau Update proyek
 * - hapus($id) : GET  → Hapus proyek berdasarkan ID
 *
 * URI: /proyek | /proyek/simpan | /proyek/hapus/{id}
 *
 * Dilindungi auth-guard di __construct():
 * user yang belum login akan di-redirect ke halaman login.
 */
class Proyek extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        // Auth Guard: pastikan user sudah login
        if ( ! $this->session->userdata('login')) {
            redirect('auth/login');
        }

        $this->load->model('Proyek_model');
    }

    /**
     * Dashboard — menampilkan form tambah/edit dan tabel daftar proyek.
     *
     * Jika ada ?edit=ID di URL, form akan terisi data proyek untuk diedit.
     */
    public function index()
    {
        // Data default untuk form kosong (mode Create)
        $edit_data = [
            'id'          => '',
            'nama_proyek' => '',
            'deskripsi'   => '',
            'nama_file'   => '',
        ];

        // Mode Edit: ambil data proyek berdasarkan ID
        $id = (int) $this->input->get('edit');
        if ($id > 0) {
            $edit_data = $this->Proyek_model->get_by_id($id);
        }

        $data = [
            'username'  => $this->session->userdata('username'),
            'proyek'    => $this->Proyek_model->get_all(),
            'edit_data' => $edit_data,
        ];

        $this->load->view('proyek/index', $data);
    }

    /**
     * Proses simpan — Create atau Update proyek.
     * Hanya menerima POST request.
     */
    public function simpan()
    {
        if ( ! $this->input->post('simpan')) {
            redirect('proyek');
        }

        $id         = (int)    $this->input->post('id');
        $nama       = $this->input->post('nama_proyek', TRUE);
        $deskripsi  = $this->input->post('deskripsi',   TRUE);
        $file_lama  = $this->input->post('file_lama',   TRUE);

        if ($id > 0) {
            // Mode Update
            $this->Proyek_model->update($id, $nama, $deskripsi, $file_lama);
        } else {
            // Mode Create
            $this->Proyek_model->create($nama, $deskripsi);
        }

        redirect('proyek');
    }

    /**
     * Hapus proyek berdasarkan ID.
     *
     * @param int $id
     */
    public function hapus(int $id = 0)
    {
        if ($id > 0) {
            $this->Proyek_model->delete($id);
        }
        redirect('proyek');
    }
}
?>
