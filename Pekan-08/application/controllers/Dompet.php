<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dompet extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        // inisialisasi koneksi database ke class (seperti $this->conn = $db di native)
        $this->load->database();
        // load model Laporan_model untuk akses data laporan
        $this->load->model('Laporan_model');
        // load helper url dan form
        $this->load->helper(array('url', 'form'));
        // load library session dan upload
        $this->load->library(array('session', 'upload'));

        // ambil session dari cookie jika belum ada (checkCookie dari class Session native)
        if ($this->input->cookie('username') && !$this->session->userdata('username')) {
            // set session dari cookie yang tersimpan
            $this->session->set_userdata('username', $this->input->cookie('username'));
        }

        // cek jika user belum login (checkLogin dari class Session native)
        if (!$this->session->userdata('username')) {
            redirect('auth'); // redirect ke halaman login
        }
    }

    // HALAMAN UTAMA DOMPET 
    // menggantikan bagian tampil data dan render HTML di dompet.php native
    public function index()
    {
        $username = $this->session->userdata('username'); // ambil username dari session

        // default data edit kosong
        $data['editData'] = null;

        // ================= EDIT =================
        // cek apakah mode edit aktif (isset($_GET['edit']) di native)
        if ($this->input->get('edit')) {
            // ambil data berdasarkan id (getById dari class Laporan native)
            $data['editData'] = $this->Laporan_model->getById($this->input->get('edit'));
        }

        // ================= TAMPIL DATA =================
        // ambil semua data milik user (getAll dari class Laporan native)
        $data['dataLaporan'] = $this->Laporan_model->getAll($username);
        $data['username']    = $username;

        $this->load->view('dompet/index', $data); // tampilkan halaman dompet
    }

    // ================= DELETE =================
    // cek apakah ada parameter hapus (isset($_GET['hapus']) di native)
    public function hapus($id)
    {
        $username = $this->session->userdata('username'); // ambil username dari session
        // hapus data berdasarkan id (delete dari class Laporan native)
        $this->Laporan_model->delete($id, $username);

        $this->session->set_flashdata('success', 'Data berhasil dihapus');
        redirect('dompet'); // reload halaman
    }

    // ================= INSERT / UPDATE (SIMPAN) =================
    // menggantikan blok if(isset($_POST['tanggal'])) di dompet.php native
    // Satu tombol "Simpan" menangani INSERT sekaligus UPDATE + upload file
    public function simpan()
    {
        $username = $this->session->userdata('username'); // ambil username dari session

        // cek apakah form dikirim (isset($_POST['tanggal']) di native)
        if ($this->input->post('tanggal')) {

            $id = $this->input->post('id'); // id data yang akan di update

            // cek apakah update ada id (isset($_POST['id']) && $_POST['id'] != "" di native)
            if ($id && $id != '') {

                // ambil data lama untuk keperluan file lama
                $editData = $this->Laporan_model->getById($id);

                // proses upload file (uploadFile dari class Laporan native)
                $fileName = $this->Laporan_model->uploadFile(
                    $_FILES['file'],          // file yang diupload dari form
                    $editData['file'] ?? ''   // file lama jika tidak diganti
                );

                // data yang akan diupdate
                $postData              = $this->input->post(); // data form yang dikirim
                $postData['file']      = $fileName;
                $postData['username']  = $username;

                // update data (update dari class Laporan native)
                $this->Laporan_model->update($id, $postData, $username);

                $this->session->set_flashdata('success', 'Data berhasil diupdate');
                redirect('dompet'); // notif update berhasil

            } else {

                // proses upload file untuk insert baru (uploadFile dari class Laporan native)
                $fileName = $this->Laporan_model->uploadFile($_FILES['file']); // proses upload file terlebih dahulu

                // data yang akan diinsert
                $postData             = $this->input->post(); // data form yang dikirim
                $postData['file']     = $fileName;
                $postData['username'] = $username;

                // proses insert baru (insert dari class Laporan native)
                $this->Laporan_model->insert($postData);

                $this->session->set_flashdata('success', 'Data berhasil ditambahkan');
                redirect('dompet'); // notif insert berhasil
            }
        } else {
            redirect('dompet'); // kembali ke halaman dompet
        }
    }
}