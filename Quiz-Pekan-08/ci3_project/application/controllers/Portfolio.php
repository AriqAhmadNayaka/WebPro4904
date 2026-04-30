<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Portfolio extends CI_Controller {

    // Constructor dipakai untuk memuat model dan library yang dibutuhkan semua method.
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Portfolio_model', 'portfolio');
        $this->load->library(array('form_validation', 'upload'));
    }

    // Menampilkan halaman publik portofolio.
    public function index()
    {
        $data = array(
            // Ambil data profil utama untuk header dan section tentang saya.
            'profile' => $this->portfolio->get_profile(),
            // Ambil semua daftar project portofolio.
            'projects' => $this->portfolio->get_projects(),
        );

        $this->load->view('portfolio/index', $data);
    }

    // Menampilkan dashboard admin untuk mengelola data portofolio.
    public function admin()
    {
        $data = array(
            'profile' => $this->portfolio->get_profile(),
            'projects' => $this->portfolio->get_projects(),
        );

        $this->load->view('portfolio/admin', $data);
    }

    // Menampilkan form edit profil utama.
    public function edit_profile()
    {
        $data['profile'] = $this->portfolio->get_profile();
        $this->load->view('portfolio/profile_form', $data);
    }

    // Menyimpan perubahan data profil, termasuk upload foto profil jika ada.
    public function update_profile()
    {
        $profile = $this->portfolio->get_profile();

        // Validasi input profil agar field penting tidak kosong.
        $this->form_validation->set_rules('full_name', 'Nama lengkap', 'required|trim');
        $this->form_validation->set_rules('profession', 'Profesi', 'required|trim');
        $this->form_validation->set_rules('about', 'Tentang saya', 'required|trim');
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email');
        $this->form_validation->set_rules('phone', 'Nomor telepon', 'required|trim');
        $this->form_validation->set_rules('address', 'Alamat', 'required|trim');
        $this->form_validation->set_rules('skills', 'Keahlian', 'required|trim');

        if ($this->form_validation->run() === FALSE) {
            $data['profile'] = $profile;
            return $this->load->view('portfolio/profile_form', $data);
        }

        // Upload foto baru jika user memilih file.
        $profile_photo = $this->handle_upload('profile_photo', 'profiles', $profile ? $profile->profile_photo : null);
        if ($profile_photo === FALSE) {
            $data['profile'] = $profile;
            $data['upload_error'] = $this->upload->display_errors('', '');
            return $this->load->view('portfolio/profile_form', $data);
        }

        // Data yang akan disimpan ke tabel profiles.
        $payload = array(
            'full_name' => $this->input->post('full_name', TRUE),
            'profession' => $this->input->post('profession', TRUE),
            'about' => $this->input->post('about', TRUE),
            'email' => $this->input->post('email', TRUE),
            'phone' => $this->input->post('phone', TRUE),
            'address' => $this->input->post('address', TRUE),
            'skills' => $this->input->post('skills', TRUE),
            'profile_photo' => $profile_photo,
        );

        $this->portfolio->save_profile($payload);
        $this->session->set_flashdata('success', 'Profil portofolio berhasil diperbarui.');
        redirect('portfolio/admin');
    }

    // Menampilkan form tambah project portofolio baru.
    public function create_project()
    {
        $data = array(
            'page_title' => 'Tambah Portofolio',
            'project' => null,
            'form_action' => site_url('portfolio/store'),
            'submit_label' => 'Simpan Portofolio',
        );

        $this->load->view('portfolio/project_form', $data);
    }

    // Menyimpan data project baru ke database.
    public function store_project()
    {
        $this->validate_project_form();

        // Jika validasi gagal, tampilkan kembali form beserta error.
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'page_title' => 'Tambah Portofolio',
                'project' => null,
                'form_action' => site_url('portfolio/store'),
                'submit_label' => 'Simpan Portofolio',
            );

            return $this->load->view('portfolio/project_form', $data);
        }

        // Upload gambar project bila user memilih file.
        $image = $this->handle_upload('image', 'projects');
        if ($image === FALSE) {
            $data = array(
                'page_title' => 'Tambah Portofolio',
                'project' => null,
                'form_action' => site_url('portfolio/store'),
                'submit_label' => 'Simpan Portofolio',
                'upload_error' => $this->upload->display_errors('', ''),
            );

            return $this->load->view('portfolio/project_form', $data);
        }

        // Gabungkan input form dengan path gambar hasil upload.
        $payload = $this->project_payload();
        $payload['image'] = $image;

        $this->portfolio->insert_project($payload);
        $this->session->set_flashdata('success', 'Data portofolio berhasil ditambahkan.');
        redirect('portfolio/admin');
    }

    // Menampilkan form edit untuk project berdasarkan id.
    public function edit_project($id)
    {
        $project = $this->portfolio->get_project($id);
        if (!$project) {
            show_404();
        }

        $data = array(
            'page_title' => 'Edit Portofolio',
            'project' => $project,
            'form_action' => site_url('portfolio/update/' . $project->id),
            'submit_label' => 'Update Portofolio',
        );

        $this->load->view('portfolio/project_form', $data);
    }

    // Memperbarui data project yang sudah ada.
    public function update_project($id)
    {
        $project = $this->portfolio->get_project($id);
        if (!$project) {
            show_404();
        }

        $this->validate_project_form();

        // Jika form tidak valid, kembalikan user ke form edit.
        if ($this->form_validation->run() === FALSE) {
            $data = array(
                'page_title' => 'Edit Portofolio',
                'project' => $project,
                'form_action' => site_url('portfolio/update/' . $project->id),
                'submit_label' => 'Update Portofolio',
            );

            return $this->load->view('portfolio/project_form', $data);
        }

        // Jika ada file gambar baru, file lama akan diganti.
        $image = $this->handle_upload('image', 'projects', $project->image);
        if ($image === FALSE) {
            $data = array(
                'page_title' => 'Edit Portofolio',
                'project' => $project,
                'form_action' => site_url('portfolio/update/' . $project->id),
                'submit_label' => 'Update Portofolio',
                'upload_error' => $this->upload->display_errors('', ''),
            );

            return $this->load->view('portfolio/project_form', $data);
        }

        // Susun ulang data sebelum disimpan ke database.
        $payload = $this->project_payload();
        $payload['image'] = $image;

        $this->portfolio->update_project($id, $payload);
        $this->session->set_flashdata('success', 'Data portofolio berhasil diperbarui.');
        redirect('portfolio/admin');
    }

    // Menghapus satu data project dan file gambarnya jika tersedia.
    public function delete_project($id)
    {
        $project = $this->portfolio->get_project($id);
        if (!$project) {
            show_404();
        }

        // Hapus file gambar dari folder uploads agar tidak menjadi file sampah.
        if (!empty($project->image)) {
            $image_path = FCPATH . $project->image;
            if (is_file($image_path)) {
                @unlink($image_path);
            }
        }

        $this->portfolio->delete_project($id);
        $this->session->set_flashdata('success', 'Data portofolio berhasil dihapus.');
        redirect('portfolio/admin');
    }

    // Aturan validasi khusus form project.
    private function validate_project_form()
    {
        $this->form_validation->set_rules('title', 'Judul portofolio', 'required|trim');
        $this->form_validation->set_rules('category', 'Kategori', 'required|trim');
        $this->form_validation->set_rules('description', 'Deskripsi', 'required|trim');
        $this->form_validation->set_rules('project_link', 'Link project', 'trim|valid_url');
    }

    // Mengambil input project dari form lalu menyusunnya dalam array.
    private function project_payload()
    {
        return array(
            'title' => $this->input->post('title', TRUE),
            'category' => $this->input->post('category', TRUE),
            'description' => $this->input->post('description', TRUE),
            'project_link' => $this->input->post('project_link', TRUE),
        );
    }

    // Fungsi umum untuk upload file profil maupun gambar project.
    private function handle_upload($field_name, $folder, $old_file = null)
    {
        // Jika user tidak memilih file baru, pakai file lama.
        if (empty($_FILES[$field_name]['name'])) {
            return $old_file;
        }

        // Konfigurasi upload file gambar.
        $config = array(
            'upload_path' => FCPATH . 'uploads/' . $folder . '/',
            'allowed_types' => 'jpg|jpeg|png|webp',
            'max_size' => 2048,
            'encrypt_name' => TRUE,
        );

        // Buat folder upload otomatis jika belum tersedia.
        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0777, TRUE);
        }

        $this->upload->initialize($config);

        // Jika upload gagal, kirim status FALSE agar ditangani caller.
        if (!$this->upload->do_upload($field_name)) {
            return FALSE;
        }

        // Jika ada file lama, hapus setelah file baru berhasil diupload.
        if (!empty($old_file)) {
            $old_path = FCPATH . $old_file;
            if (is_file($old_path)) {
                @unlink($old_path);
            }
        }

        // Simpan path relatif file hasil upload ke database.
        $uploaded = $this->upload->data();
        return 'uploads/' . $folder . '/' . $uploaded['file_name'];
    }
}
