<?php
// Mendefinisikan controller Dashboard yang akan digunakan untuk menampilkan halaman dashboard setelah pengguna berhasil login, serta memastikan bahwa hanya pengguna yang sudah login yang dapat mengakses halaman dashboard dengan memeriksa session user_id, 
// dan jika tidak ada maka akan diarahkan ke halaman login
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    // Konstruktor untuk melakukan proteksi halaman agar hanya pengguna yang sudah login yang dapat mengaksesnya, serta memuat model Laporan_model untuk digunakan dalam operasi terkait laporan di halaman dashboard
    public function __construct() {
        // Memanggil konstruktor parent untuk memastikan bahwa semua fungsi dasar dari CI_Controller berjalan dengan baik
        parent::__construct();
        // Melakukan proteksi halaman dengan memeriksa apakah session 'user_id' sudah ada, jika belum, maka pengguna akan diarahkan ke halaman login
        if (!$this->session->userdata('user_id')) {
            // Jika session 'user_id' tidak ada, artinya pengguna belum login, maka arahkan ke halaman login
            redirect('auth/login');
        }
        // Memuat model Laporan_model yang akan digunakan untuk operasi terkait laporan di halaman dashboard, seperti mengambil data laporan yang dimiliki oleh pengguna yang sedang login untuk ditampilkan di halaman dashboard
        $this->load->model('Laporan_model');
    }

    // Fungsi index untuk menampilkan halaman dashboard dengan data laporan yang dimiliki oleh pengguna yang sedang login, serta nama pengguna untuk ditampilkan di halaman dashboard
    public function index() {
        // Menyiapkan data yang akan dikirim ke view, termasuk data laporan yang dimiliki oleh pengguna yang sedang login dan nama pengguna untuk ditampilkan di halaman dashboard, dengan memanggil fungsi get_all dari Laporan_model untuk mendapatkan data laporan berdasarkan user_id dari session, 
        // serta mendapatkan nama pengguna dari session untuk ditampilkan di halaman dashboard
        $data['nama'] = $this->session->userdata('nama') ?? 'Pengguna';
        // Mendapatkan data laporan yang dimiliki oleh pengguna yang sedang login dengan memanggil fungsi get_all dari Laporan_model dan memberikan parameter user_id dari session untuk memastikan bahwa hanya laporan milik pengguna yang bersangkutan yang dapat diakses, 
        // kemudian simpan data laporan ke dalam variabel 'laporan' untuk dikirim ke view agar dapat ditampilkan di halaman dashboard
        $data['laporan'] = $this->Laporan_model->get_all($this->session->userdata('user_id'));
        // Memuat view 'dashboard/index' dan mengirimkan data yang sudah disiapkan untuk ditampilkan di halaman dashboard dengan menggunakan fungsi load->view dari CodeIgniter dan memberikan parameter nama view yang akan dimuat serta data yang akan dikirim ke view untuk ditampilkan di halaman dashboard
        $this->load->view('dashboard/index', $data);
    }
}
?>