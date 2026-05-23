<?php
defined('BASEPATH') OR exit('No direct script access allowed'); //mengecek apakah dijalankan melalui framework

class Dashboard extends CI_Controller //class dashboard
{
    public function __construct() //fungsi constructor
    {
        parent::__construct(); //menjalankan constructor
        $this->load->model('Post_model'); //memanggil post model
        $this->load->library(array('form_validation', 'upload')); //memvalidasi form dan upload gambar
    }

    public function index() //method dari index
    {
        $data = array(
            'title' => 'Dashboard',
            'patients' => $this->Post_model->get_all(),
            'total_patients' => $this->Post_model->count_all(),
            'loggedInUser' => $this->session->userdata('email')
                ? $this->session->userdata('email')
                : ($this->session->userdata('nama_lengkap') ?: 'Pengguna'),
            'message' => $this->session->flashdata('message') ?: '',
            'messageType' => $this->session->flashdata('message_type') ?: 'success',
        );

        $this->load->view('dashboard/index', $data); //mengambil semua data pasien dari database
    }

    public function save() //method untuk menyimpan data yang ada di form
    {
        $this->form_validation->set_rules('name', 'Nama', 'trim|required|max_length[30]');
        $this->form_validation->set_rules('addres', 'Alamat', 'trim|required');
        $this->form_validation->set_rules('number', 'Nomor Telepon', 'trim|required|max_length[12]');
        $this->form_validation->set_rules('gender', 'Jenis Kelamin', 'trim|required');
        $this->form_validation->set_rules('date', 'Tanggal Lahir', 'trim|required');
        $this->form_validation->set_rules('weight', 'Berat Badan', 'trim|required|max_length[3]');
        $this->form_validation->set_rules('height', 'Tinggi Badan', 'trim|required|max_length[3]');
        $this->form_validation->set_rules('text', 'Jenis Kanker', 'trim|required|max_length[30]');

        if (!$this->form_validation->run()) { //menjalankan semua validasi di atas
            $this->session->set_flashdata('message', strip_tags(validation_errors(' ', ' '))); //jika validasi gagal
            $this->session->set_flashdata('message_type', 'error'); //jenis pesan error, sistem menyimpan pesan sementara
            redirect('dashboard'); //dan kembali ke dashboard
        }

        $filePasien = null; 

        if (!empty($_FILES['photo']['name'])) { //mencek apakah user upload file apa tidak
            $uploadResult = $this->do_upload(); //proses upload gambar

            if (!$uploadResult['status']) { //jika upload file gagal
                $this->session->set_flashdata('message', strip_tags($uploadResult['message'])); //sistem memberi pesan
                $this->session->set_flashdata('message_type', 'error');
                redirect('dashboard');
            }

            $filePasien = $uploadResult['file_name'];
            //jika berhasil, simpan nama file ke variabel
        }

        $payload = array( //membuat array data pasien yang akan di kirim ke db
            'nama' => $this->input->post('name', TRUE),
            'alamat' => $this->input->post('addres', TRUE),
            'notlp' => $this->input->post('number', TRUE),
            'jeniskelamin' => $this->input->post('gender', TRUE),
            'tanggallahir' => $this->input->post('date', TRUE),
            'beratbadan' => $this->input->post('weight', TRUE),
            'tinggibadan' => $this->input->post('height', TRUE),
            'jeniskanker' => $this->input->post('text', TRUE),
            'file_pasien' => $filePasien,
        );

        $saved = $this->Post_model->insert($payload); //memanggil fuction model, lalu di simpan

        $this->session->set_flashdata( //menyimpan pesan sementara ke session
            'message',
            $saved ? 'Data pasien berhasil disimpan.' : 'Data pasien gagal disimpan.'
        );
        $this->session->set_flashdata('message_type', $saved ? 'success' : 'error'); //tipe pesan

        redirect('dashboard');
    }

    public function delete($id) //function hapus data
    {
        $patient = $this->Post_model->get_by_id((int) $id); //mengambil data

        if ($patient) { //menemukan data pasien di db
            if (!empty($patient->file_pasien)) { //jika pasian punya file foto
                $filePath = FCPATH . 'uploads/patients/' . $patient->file_pasien;
                if (is_file($filePath)) {
                    unlink($filePath); //file akan di hapus
                }
            }

            $this->Post_model->delete((int) $id); //menghapus data psien dari db
            $this->session->set_flashdata('message', 'Data pasien berhasil dihapus.'); //mengirim pesan sementara
            $this->session->set_flashdata('message_type', 'success');
        } else {
            $this->session->set_flashdata('message', 'Data pasien tidak ditemukan.'); //jika data tidak ditemukan
            $this->session->set_flashdata('message_type', 'error'); //mengirim tipe pesan error
        }

        redirect('dashboard');
    }

    private function do_upload() //private function yang hanya bisa dipakai di controller ini saja, untuk 
    //menupload foto pasien 
    {
        $uploadPath = FCPATH . 'uploads/patients/'; //menentukan folder upload

        if (!is_dir($uploadPath)) { //cek apakah folder upload ada
            mkdir($uploadPath, 0777, true);
        }

        $config = array( //membuat config upload
            'upload_path' => $uploadPath, //folder menyimpan file
            'allowed_types' => 'jpg|jpeg|png|webp', //format file yang di izinkan
            'max_size' => 2048, //ukuran max file
            'encrypt_name' => true, //membuat nama file otomatis
        );

        $this->upload->initialize($config); //mengaktifkan library upload dengan config tadi

        if (!$this->upload->do_upload('photo')) { //upload file input
            return array(
                'status' => false, //status gagal
                'message' => $this->upload->display_errors('', ''), //pesan error upload
            );
        }

        $uploaded = $this->upload->data(); //info file berhasil upload

        return array(
            'status' => true, //status berhasil
            'file_name' => $uploaded['file_name'], //nama file hasil upload
        );
    }
}
