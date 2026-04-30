<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Controller ini mengatur CRUD peserta dan proses upload foto.
class Peserta extends CI_Controller
{
    // Folder tujuan penyimpanan file upload.
    private $upload_path;
    // Batas ukuran upload foto dalam KB.
    private $max_upload_size_kb = 4096;

    public function __construct()
    {
        parent::__construct();
        // Halaman peserta hanya bisa diakses setelah login.
        $this->require_login();
        $this->load->model('Participant_model');
        $this->upload_path = FCPATH . 'uploads/';
    }

    public function index()
    {
        // Ambil data peserta dan daftar pelatihan untuk halaman kelola peserta.
        $data = array(
            'title' => 'Kelola Peserta | InkluSkill',
            'participants' => $this->Participant_model->get_all(),
            'trainings' => $this->Participant_model->training_options(),
        );

        $this->load->view('participants/index', $data);
    }

    public function save()
    {
        // Ambil id untuk membedakan proses tambah dan edit.
        $id = (int) $this->input->post('id');
        $old_photo = $this->input->post('foto_lama', true);

        // Validasi field utama form peserta.
        $this->form_validation->set_rules('nama', 'Nama Peserta', 'required|trim');
        $this->form_validation->set_rules('umur', 'Usia', 'required|integer|greater_than_equal_to[1]');
        $this->form_validation->set_rules('jenis_kelamin', 'Jenis Kelamin', 'required|trim');
        $this->form_validation->set_rules('pelatihan', 'Pelatihan', 'required|trim');

        if (!$this->form_validation->run()) {
            $this->session->unset_userdata('success');
            $this->session->set_flashdata('error', validation_errors('<div>', '</div>'));
            redirect('peserta');
        }

        // Susun data peserta yang akan disimpan ke database.
        $payload = array(
            'nama' => $this->input->post('nama', true),
            'umur' => (int) $this->input->post('umur', true),
            'jenis_kelamin' => $this->input->post('jenis_kelamin', true),
            'pelatihan' => $this->input->post('pelatihan', true),
        );

        $uploaded_photo = $old_photo;
        $has_new_photo = !empty($_FILES['foto']['name']);

        if ($has_new_photo) {
            // Upload file foto baru jika user memilih file.
            $result = $this->handle_upload();

            if (!$result['status']) {
                $this->session->unset_userdata('success');
                $this->session->set_flashdata('error', $result['message']);
                redirect('peserta');
            }

            $uploaded_photo = $result['file_name'];
        } elseif ($id === 0) {
            // Saat tambah data baru, foto wajib diisi.
            $this->session->unset_userdata('success');
            $this->session->set_flashdata('error', 'Foto peserta wajib diupload saat tambah data.');
            redirect('peserta');
        }

        $payload['foto'] = $uploaded_photo;

        if ($id > 0) {
            // Update data peserta jika id sudah ada.
            $this->Participant_model->update($id, $payload);

            if ($has_new_photo && $old_photo && $old_photo !== $uploaded_photo) {
                // Hapus foto lama jika diganti dengan file baru.
                $this->delete_photo_file($old_photo);
            }

            $message = 'Data peserta berhasil diperbarui.';
        } else {
            // Tambah data peserta baru ke database.
            $this->Participant_model->create($payload);
            $message = 'Data peserta berhasil ditambahkan.';
        }

        $this->session->unset_userdata('error');
        $this->session->set_flashdata('success', $message);
        redirect('peserta');
    }

    public function delete($id)
    {
        // Ambil data peserta untuk memastikan record tersedia.
        $participant = $this->Participant_model->find($id);

        if (!$participant) {
            $this->session->unset_userdata('success');
            $this->session->set_flashdata('error', 'Data peserta tidak ditemukan.');
            redirect('peserta');
        }

        // Hapus data peserta dan file fotonya.
        $this->Participant_model->delete($id);
        $this->delete_photo_file($participant->foto);

        $this->session->unset_userdata('error');
        $this->session->set_flashdata('success', 'Data peserta berhasil dihapus.');
        redirect('peserta');
    }

    private function handle_upload()
    {
        // Konfigurasi upload file gambar peserta.
        $config['upload_path'] = $this->upload_path;
        $config['allowed_types'] = 'jpg|jpeg|png|gif|webp';
        $config['max_size'] = $this->max_upload_size_kb;
        $config['encrypt_name'] = true;

        if (!is_dir($config['upload_path'])) {
            // Buat folder upload jika belum tersedia.
            mkdir($config['upload_path'], 0777, true);
        }

        $this->upload->initialize($config);

        if (!$this->upload->do_upload('foto')) {
            // Terjemahkan pesan error bawaan upload ke bahasa Indonesia.
            $raw_error = strip_tags($this->upload->display_errors('', ''));

            return array(
                'status' => false,
                'message' => $this->translate_upload_error($raw_error),
            );
        }

        $upload_data = $this->upload->data();

        return array(
            'status' => true,
            // Simpan nama file hasil upload untuk dicatat ke database.
            'file_name' => $upload_data['file_name'],
        );
    }

    private function translate_upload_error($message)
    {
        // Ubah batas upload dari KB ke MB agar mudah dibaca user.
        $max_size_mb = $this->max_upload_size_kb / 1024;
        $formatted_size = rtrim(rtrim(number_format($max_size_mb, 2, '.', ''), '0'), '.');

        if (stripos($message, 'larger than the permitted size') !== false) {
            return 'Ukuran foto melebihi batas maksimal ' . $formatted_size . ' MB.';
        }

        if (stripos($message, 'filetype you are attempting to upload is not allowed') !== false) {
            return 'Format foto tidak didukung. Gunakan file jpg, jpeg, png, gif, atau webp.';
        }

        if (stripos($message, 'did not select a file to upload') !== false) {
            return 'Silakan pilih file foto terlebih dahulu.';
        }

        return 'Upload foto gagal. ' . $message;
    }

    private function delete_photo_file($filename)
    {
        if (!$filename) {
            return;
        }

        // Hapus file fisik dari folder uploads jika file ditemukan.
        $path = $this->upload_path . $filename;
        if (is_file($path)) {
            unlink($path);
        }
    }

    private function require_login()
    {
        // Cegah akses langsung ke halaman peserta tanpa login.
        if (!$this->session->userdata('user_id')) {
            $this->session->set_flashdata('error', 'Silakan login terlebih dahulu.');
            redirect('login');
        }
    }
}
