<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CONTROLLER: Proyek
 * Modul: application/modules/proyek/controllers/Proyek.php
 *
 * Menggabungkan logika dari: pekan8/index.php
 *
 * Di pekan8: semua logika (create/update/delete/upload + tampilan HTML) ada
 * di satu file index.php.
 * Di CI3-MVC:
 *   - Controller → terima request, panggil model, kirim data ke view
 *   - Model      → Proyek_model (semua query DB & upload)
 *   - View       → proyek/index.php & proyek/form.php (hanya HTML)
 */
class Proyek extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();

        // Cek login — setara: if (!isset($_SESSION['login'])) di pekan8/index.php
        if (!$this->session->userdata('login')) {
            redirect('auth');
        }

        $this->load->model('Proyek_model');
        $this->load->helper('url');
    }

    /**
     * Tampilkan daftar semua proyek.
     * Setara: bagian tabel di pekan8/index.php (Proyek::getAll())
     */
    public function index()
    {
        $data = [
            'page_title'    => 'Manajemen Proyek',
            'page_subtitle' => 'Daftar semua proyek yang telah didaftarkan',
            'breadcrumb'    => 'Proyek',
            'daftar_proyek' => $this->Proyek_model->getAll(),
        ];
        $this->load->view('proyek/index', $data);
    }

    /**
     * Tampilkan form tambah proyek baru.
     * Setara: form kosong di pekan8/index.php (editData kosong)
     */
    public function tambah()
    {
        $data = [
            'page_title'  => 'Tambah Proyek',
            'breadcrumb'  => 'Proyek / Tambah',
            'proyek'      => ['id' => '', 'nama_proyek' => '', 'deskripsi' => '', 'nama_file' => ''],
            'form_action' => site_url('proyek/simpan'),
        ];
        $this->load->view('proyek/form', $data);
    }

    /**
     * Tampilkan form edit proyek.
     * Setara: bagian if (isset($_GET['edit'])) di pekan8/index.php (getById)
     *
     * @param int $id
     */
    public function edit($id)
    {
        $proyek = $this->Proyek_model->getById((int) $id);
        if (empty($proyek)) {
            $this->session->set_flashdata('error', 'Data proyek tidak ditemukan.');
            redirect('proyek');
        }

        $data = [
            'page_title'  => 'Edit Proyek',
            'breadcrumb'  => 'Proyek / Edit',
            'proyek'      => $proyek,
            'form_action' => site_url('proyek/update/' . $id),
        ];
        $this->load->view('proyek/form', $data);
    }

    /**
     * Simpan proyek baru (POST).
     * Setara: bagian create() di pekan8/index.php (Proyek::create())
     */
    public function simpan()
    {
        $nama      = $this->input->post('nama_proyek', TRUE);
        $deskripsi = $this->input->post('deskripsi', TRUE);
        $file      = $_FILES['berkas'] ?? ['name' => '', 'tmp_name' => ''];

        $this->Proyek_model->create($nama, $deskripsi, $file);

        $this->session->set_flashdata('success', 'Proyek berhasil ditambahkan!');
        redirect('proyek');
    }

    /**
     * Update proyek (POST).
     * Setara: bagian update() di pekan8/index.php (Proyek::update())
     *
     * @param int $id
     */
    public function update($id)
    {
        $nama      = $this->input->post('nama_proyek', TRUE);
        $deskripsi = $this->input->post('deskripsi', TRUE);
        $fileLama  = $this->input->post('file_lama', TRUE);
        $file      = $_FILES['berkas'] ?? ['name' => '', 'tmp_name' => ''];

        $this->Proyek_model->update((int) $id, $nama, $deskripsi, $file, $fileLama);

        $this->session->set_flashdata('success', 'Proyek berhasil diperbarui!');
        redirect('proyek');
    }

    /**
     * Hapus proyek.
     * Setara: bagian if (isset($_GET['hapus'])) di pekan8/index.php (Proyek::delete())
     *
     * @param int $id
     */
    public function hapus($id)
    {
        $this->Proyek_model->delete((int) $id);
        $this->session->set_flashdata('success', 'Proyek berhasil dihapus!');
        redirect('proyek');
    }
}
