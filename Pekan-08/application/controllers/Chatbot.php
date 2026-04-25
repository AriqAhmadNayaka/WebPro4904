<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Controller: Chatbot
// Bertugas menangani halaman chatbot InkluBot.
// Mendukung dua mode interaksi:
//   1. GET  : Pesan teks biasa via query string (?message=...)
//   2. POST : Pesan teks + upload file (gambar/PDF) via form
//
// Setelah memproses input, response bot dan info upload
// dikirimkan ke view untuk ditampilkan di UI chat.

class Chatbot extends CI_Controller {

    // __construct() memuat model dan menjaga akses hanya untuk user yang sudah login
    public function __construct() {
        parent::__construct();
        // Memuat Chatbot_model agar dapat diakses via $this->Chatbot_model
        $this->load->model('Chatbot_model');
        // Guard: jika belum login, redirect ke halaman auth
        if (!$this->session->userdata('current_user')) {
            redirect('auth');
        }
    }

    // METHOD: index()
    // URL Akses: /chatbot (GET/POST)
    // Halaman utama chatbot.
    // Menangani request GET (pesan teks) dan POST (upload + pesan teks).
    public function index() {
        // Inisialisasi variabel response dengan nilai kosong
        $botReply    = "";   // Balasan dari bot
        $userMessage = "";   // Pesan yang dikirim user
        $uploadInfo  = "";   // Nama file yang berhasil di-upload
        $uploadError = "";   // Pesan error jika upload gagal

        // BLOK POST: Menangani request dari form (dengan enctype multipart)
        // Dipicu ketika user mengirim pesan DAN/ATAU meng-upload file
        if ($this->input->server('REQUEST_METHOD') === 'POST' && isset($_FILES['chatFile'])) {
            // Ambil pesan teks dari form, bersihkan spasi awal/akhir, dan escape HTML
            $userMessage = htmlspecialchars(trim($this->input->post('message') ?? ''));

            // Proses upload file chatbot via model
            // uploadFile() mengembalikan: null (tidak ada file), nama file (sukses), atau 'ERROR:...' (gagal)
            $hasilUpload = $this->Chatbot_model->uploadFile($_FILES['chatFile']);

            if ($hasilUpload !== null && str_starts_with((string)$hasilUpload, 'ERROR:')) {
                // Upload gagal → simpan pesan error (dipotong prefix 'ERROR:')
                $uploadError = substr($hasilUpload, 6);
            } elseif ($hasilUpload !== null) {
                // Upload berhasil → simpan info file ke DB dan catat nama file-nya
                $this->Chatbot_model->simpanUpload($hasilUpload, $userMessage);
                $uploadInfo = $hasilUpload; // Nama file yang berhasil di-upload
            }

            // Tandai apakah ada file yang berhasil di-upload
            $adaUpload = !empty($uploadInfo);

            // Dapatkan balasan bot jika ada pesan atau file yang di-upload
            if (!empty($userMessage) || $adaUpload) {
                // getBotReply() mengembalikan respons berdasarkan kata kunci pesan
                // Parameter $adaUpload untuk memberi tahu bot ada file yang dikirim
                $botReply = $this->Chatbot_model->getBotReply($userMessage, $adaUpload);
            }

        // BLOK GET: Menangani request pesan teks biasa via URL
        // Contoh: /chatbot?message=status+siswa
        } elseif ($this->input->server('REQUEST_METHOD') === 'GET' && $this->input->get('message') !== '') {
            // Ambil dan bersihkan pesan dari query string
            $userMessage = htmlspecialchars(trim($this->input->get('message') ?? ''));
            // Dapatkan balasan bot berdasarkan pesan (tanpa upload)
            $botReply = $this->Chatbot_model->getBotReply($userMessage);
        }

        // Siapkan data untuk dikirim ke view chatbot
        $data = array(
            'botReply'     => $botReply,     // Balasan bot yang akan ditampilkan di bubble chat
            'userMessage'  => $userMessage,  // Pesan user untuk ditampilkan di bubble user
            'uploadInfo'   => $uploadInfo,   // Nama file upload (untuk render preview di view)
            'uploadError'  => $uploadError,  // Pesan error upload (jika gagal)
            'current_user' => $this->session->userdata('current_user'), // Info user login
        );

        $this->load->view('sekolah/chatbot/index', $data);
    }
}
