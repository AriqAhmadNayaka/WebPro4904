<?php
// Mendefinisikan controller Auth yang akan digunakan untuk menangani proses autentikasi pengguna, seperti login, register, dan logout, 
// serta memastikan bahwa hanya pengguna yang sudah login yang dapat mengakses halaman tertentu dengan menggunakan session untuk menyimpan informasi login pengguna
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    // Konstruktor untuk memuat model User_model yang akan digunakan untuk operasi terkait pengguna, seperti registrasi dan login
    public function __construct() {
        // Memanggil konstruktor parent untuk memastikan bahwa semua fungsi dasar dari CI_Controller berjalan dengan baik
        parent::__construct();
        // Memuat model User_model yang akan digunakan untuk operasi terkait pengguna, seperti registrasi dan login
        $this->load->model('User_model');
    }

    // Fungsi login untuk menangani proses login pengguna, memeriksa kredensial pengguna, menyimpan informasi login ke dalam session jika login berhasil, 
    // dan mengarahkan pengguna ke halaman dashboard setelah berhasil login, serta menampilkan pesan error jika login gagal
    public function login() {
        // Jika pengguna sudah login, arahkan ke halaman dashboard untuk mencegah akses ke halaman login bagi pengguna yang sudah login
        if ($this->session->userdata('user_id')) {
            // Jika session 'user_id' sudah ada, artinya pengguna sudah login, maka arahkan ke halaman dashboard
            redirect('dashboard');
        }

        // Jika form login disubmit, maka proses login dengan memeriksa kredensial pengguna yang dikirim melalui metode POST, jika login berhasil maka simpan informasi login ke dalam session dan arahkan ke halaman dashboard, 
        // jika login gagal maka tampilkan pesan error menggunakan flashdata untuk memberikan feedback kepada pengguna tentang hasil login yang gagal
        if ($this->input->post()) {
            // Mendapatkan username dan password dari data POST yang dikirim oleh form login, kemudian memanggil fungsi login dari User_model untuk memverifikasi kredensial pengguna, 
            // jika ditemukan pengguna yang cocok maka simpan informasi login ke dalam session dan arahkan ke halaman dashboard, jika tidak ditemukan pengguna yang cocok maka tampilkan pesan error menggunakan flashdata untuk memberikan feedback kepada pengguna tentang hasil login yang gagal
            $username = $this->input->post('username');
            $password = $this->input->post('password');
            // Memanggil fungsi login dari User_model untuk memverifikasi kredensial pengguna, jika ditemukan pengguna yang cocok maka simpan informasi login ke dalam session dan arahkan ke halaman dashboard, 
            // jika tidak ditemukan pengguna yang cocok maka tampilkan pesan error menggunakan flashdata untuk memberikan feedback kepada pengguna tentang hasil login yang gagal
            $user = $this->User_model->login($username, $password);

            // Jika ditemukan pengguna yang cocok, simpan informasi login ke dalam session dan arahkan ke halaman dashboard, 
            // jika tidak ditemukan pengguna yang cocok maka tampilkan pesan error menggunakan flashdata untuk memberikan feedback kepada pengguna tentang hasil login yang gagal
            if ($user) {
                // Menyimpan informasi login pengguna ke dalam session, termasuk user_id dan nama pengguna untuk digunakan di halaman lain yang membutuhkan informasi login pengguna, 
                // serta untuk menampilkan nama pengguna di halaman dashboard setelah berhasil login
                $this->session->set_userdata([
                    'user_id' => $user['id'],
                    'nama'    => $user['nama']
                ]);
                // Setelah berhasil login dan menyimpan informasi login ke dalam session, arahkan pengguna ke halaman dashboard untuk menampilkan halaman utama setelah berhasil login
                redirect('dashboard');
            } else {
                // Jika login gagal, tampilkan pesan error menggunakan flashdata untuk memberikan feedback kepada pengguna tentang hasil login yang gagal, sehingga pengguna dapat mengetahui bahwa login yang dilakukan tidak berhasil dan dapat mencoba lagi dengan memasukkan kredensial yang benar
                $this->session->set_flashdata('error', 'Username atau Password salah!');
            }
        }
        // Tampilkan halaman form login, jika pengguna belum login atau jika login gagal maka akan tetap berada di halaman login dengan menampilkan pesan error yang sudah disiapkan menggunakan flashdata untuk memberikan feedback kepada pengguna tentang hasil login yang gagal
        $this->load->view('auth/login');
    }

    // Fungsi register untuk menangani proses registrasi pengguna baru, menyimpan data pengguna baru ke dalam database melalui model User_model, dan mengarahkan pengguna ke halaman login setelah berhasil
    public function register() {
        // Jika form registrasi disubmit, maka proses registrasi dengan mendapatkan data pengguna baru dari data POST yang dikirim oleh form registrasi, kemudian memanggil fungsi register dari User_model untuk menyimpan data pengguna baru ke dalam database, 
        // jika registrasi berhasil maka tampilkan pesan sukses menggunakan flashdata dan arahkan ke halaman login, jika registrasi gagal maka tampilkan pesan error menggunakan flashdata untuk memberikan feedback kepada pengguna tentang hasil registrasi yang gagal
        if ($this->input->post()) {
            $data = [
                'nama'     => $this->input->post('nama'),
                'email'    => $this->input->post('email'),
                'username' => $this->input->post('username'),
                'password' => $this->input->post('password'),
                'telepon'  => $this->input->post('telepon'),
                'alamat'   => $this->input->post('alamat')
            ];
            
            // Memanggil fungsi register dari User_model untuk menyimpan data pengguna baru ke dalam database, 
            // jika registrasi berhasil maka tampilkan pesan sukses menggunakan flashdata dan arahkan ke halaman login, 
            // jika registrasi gagal maka tampilkan pesan error menggunakan flashdata untuk memberikan feedback kepada pengguna tentang hasil registrasi yang gagal 
            if ($this->User_model->register($data)) {
                // Jika registrasi berhasil, tampilkan pesan sukses menggunakan flashdata untuk memberikan feedback kepada pengguna tentang hasil registrasi yang berhasil, 
                // kemudian arahkan ke halaman login untuk memungkinkan pengguna yang baru saja mendaftar untuk login dengan menggunakan kredensial yang sudah dibuat
                $this->session->set_flashdata('success', 'Registrasi berhasil! Silakan login.');
                // Setelah berhasil registrasi dan menampilkan pesan sukses, arahkan pengguna ke halaman login untuk memungkinkan pengguna yang baru saja mendaftar untuk login dengan menggunakan kredensial yang sudah dibuat 
                redirect('auth/login');
            } else {
                // Jika registrasi gagal, tampilkan pesan error menggunakan flashdata untuk memberikan feedback kepada pengguna tentang hasil registrasi yang gagal, 
                // sehingga pengguna dapat mengetahui bahwa registrasi yang dilakukan tidak berhasil dan dapat mencoba lagi dengan memasukkan data yang benar
                $this->session->set_flashdata('error', 'Gagal mendaftar, silakan coba lagi.');
            }
        }
        
        // Tampilkan halaman form registrasi, jika pengguna belum melakukan registrasi atau jika registrasi gagal maka akan tetap berada di halaman registrasi 
        // dengan menampilkan pesan error yang sudah disiapkan menggunakan flashdata untuk memberikan feedback kepada pengguna tentang hasil registrasi yang gagal
        $this->load->view('auth/register');
    }

    // Fungsi logout untuk menangani proses logout pengguna dengan menghancurkan session yang menyimpan informasi login pengguna, kemudian mengarahkan pengguna ke halaman login setelah berhasil logout
    public function logout() {
        // Menghancurkan session yang menyimpan informasi login pengguna untuk melakukan proses logout, sehingga pengguna tidak lagi memiliki akses ke halaman yang membutuhkan autentikasi setelah logout, 
        // kemudian arahkan pengguna ke halaman login untuk memungkinkan pengguna untuk login kembali jika ingin mengakses halaman yang membutuhkan autentikasi
        $this->session->sess_destroy();
        // Setelah berhasil logout dan menghancurkan session, arahkan pengguna ke halaman login untuk
        redirect('auth/login');
    }
}
?>