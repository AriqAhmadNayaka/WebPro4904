<?php
// Mendefinisikan controller Dompet yang akan menangani operasi terkait dompet, seperti menampilkan laporan, menambah, mengubah, dan menghapus laporan
defined('BASEPATH') OR exit('No direct script access allowed');

// Dompet adalah controller yang menangani operasi terkait dompet, seperti menampilkan laporan, menambah, mengubah, dan menghapus laporan
class Dompet extends CI_Controller {

    // Konstruktor untuk memuat model Laporan_model dan melakukan proteksi halaman agar hanya pengguna yang sudah login yang dapat mengaksesnya
    public function __construct() {
        // Memanggil konstruktor parent untuk memastikan bahwa semua fungsi dasar dari CI_Controller berjalan dengan baik 
        parent::__construct();
        // Melakukan proteksi halaman dengan memeriksa apakah session 'user_id' sudah ada, jika belum, maka pengguna akan diarahkan ke halaman login
        if (!$this->session->userdata('user_id')) {
            // Jika session 'user_id' tidak ada, artinya pengguna belum login, maka arahkan ke halaman login
            redirect('auth/login');
        }
        // Memuat model Laporan_model yang akan digunakan untuk operasi terkait laporan, seperti mengambil, menambah, mengubah, dan menghapus laporan
        $this->load->model('Laporan_model');
    }

    // Fungsi index untuk menampilkan halaman utama dompet, menangani proses tambah, ubah, dan hapus laporan, 
    // serta menampilkan data laporan yang dimiliki oleh pengguna yang sedang login
    public function index() {
        // Mendapatkan user_id dari session untuk digunakan dalam operasi terkait laporan yang hanya boleh diakses oleh pengguna yang bersangkutan
        $user_id = $this->session->userdata('user_id');

        // Fingsi untuk menghapus laporan berdasarkan ID yang didapatkan dari parameter GET 'hapus', 
        // memastikan bahwa hanya laporan milik pengguna yang bersangkutan yang dapat dihapus
        if ($this->input->get('hapus')) {
            // Menghapus laporan berdasarkan ID yang didapatkan dari parameter GET 'hapus', 
            // memastikan bahwa hanya laporan milik pengguna yang bersangkutan yang dapat dihapus dengan memanggil fungsi hapus dari Laporan_model 
            // dan memberikan parameter ID laporan yang akan dihapus serta user_id untuk memastikan bahwa hanya laporan milik pengguna yang bersangkutan yang dapat dihapus
            $this->Laporan_model->hapus($this->input->get('hapus'), $user_id);
            // Setelah menghapus laporan, arahkan kembali ke halaman utama dompet
            redirect('dompet');
        }

        // Fungsi untuk menangani proses tambah dan ubah laporan berdasarkan data yang dikirim melalui metode POST, 
        // memastikan bahwa hanya laporan milik pengguna yang bersangkutan yang dapat ditambah atau diubah
        if ($this->input->post()) {
            // Mendapatkan ID laporan dari data POST, jika ID ada maka itu adalah proses ubah, jika tidak ada maka itu adalah proses tambah
            $id = $this->input->post('id');
            // Menyiapkan data laporan yang akan disimpan ke dalam database, 
            // termasuk user_id untuk memastikan bahwa laporan yang ditambah atau diubah hanya milik pengguna yang bersangkutan 
            $laporan_data = [
                'user_id'    => $user_id, 
                'tanggal'    => $this->input->post('tanggal'),
                'keterangan' => $this->input->post('keterangan'),
                'jenis'      => $this->input->post('jenis'),
                'jumlah'     => $this->input->post('jumlah')
            ];

            // Menangani upload foto jika ada file yang diunggah, memastikan bahwa hanya file dengan tipe jpg, jpeg, atau png yang diizinkan untuk diunggah
            if (!empty($_FILES['foto']['name'])) {
                $config['upload_path']   = './uploads/';
                $config['allowed_types'] = 'jpg|jpeg|png';
                // Menambahkan timestamp pada nama file untuk memastikan nama file yang unik dan menghindari konflik dengan file yang sudah ada
                $config['file_name']     = time() . '_' . $_FILES['foto']['name'];

                // Memuat library upload dengan konfigurasi yang sudah ditentukan untuk menangani proses upload file
                $this->upload->initialize($config);

                // Melakukan proses upload file, jika berhasil maka simpan nama file yang diunggah ke dalam data laporan, 
                // jika gagal maka tampilkan error dan hentikan eksekusi untuk memberikan kesempatan kepada pengguna untuk membaca error yang terjadi 
                if ($this->upload->do_upload('foto')) {
                    // Jika upload berhasil, dapatkan data file yang diunggah dan simpan nama file ke dalam data laporan untuk disimpan ke dalam database
                    $uploadData = $this->upload->data();
                    // Menyimpan nama file yang diunggah ke dalam data laporan untuk disimpan ke dalam database
                    $laporan_data['foto'] = $uploadData['file_name'];
                } else {
                    // Jika upload gagal, dapatkan pesan error dari library upload dan tampilkan dalam bentuk alert untuk memberikan informasi kepada pengguna tentang kegagalan upload, 
                    // kemudian hentikan eksekusi untuk memberikan kesempatan kepada pengguna untuk membaca error yang terjadi
                    $error = $this->upload->display_errors();
                    // Menampilkan pesan error dalam bentuk alert untuk memberikan informasi kepada pengguna tentang kegagalan upload dengan menggunakan fungsi strip_tags untuk menghilangkan tag HTML dari pesan error agar lebih aman dan mudah dibaca, 
                    // kemudian hentikan eksekusi untuk memberikan kesempatan kepada pengguna untuk membaca error yang terjadi
                    echo "<script>alert('Gagal Upload Foto: ".strip_tags($error)."');</script>";
                    // Hentikan eksekusi untuk memberikan kesempatan kepada pengguna untuk membaca error yang terjadi
                    die(); 
                }
            }

            // Menangani proses tambah atau ubah laporan berdasarkan apakah ID laporan ada atau tidak, 
            // memastikan bahwa hanya laporan milik pengguna yang bersangkutan yang dapat ditambah atau diubah dengan memanggil fungsi update untuk ubah dan insert untuk tambah dari Laporan_model dengan memberikan parameter data laporan yang sudah disiapkan, 
            // kemudian arahkan kembali ke halaman utama dompet setelah proses tambah atau ubah selesai
            if (!empty($id)) {
                // Jika ID laporan ada, itu adalah proses ubah, maka panggil fungsi update dari Laporan_model dengan memberikan parameter ID laporan yang akan diubah, 
                // user_id untuk memastikan bahwa hanya laporan milik pengguna yang bersangkutan yang dapat diubah, 
                // dan data laporan yang sudah disiapkan untuk disimpan ke dalam database
                $this->Laporan_model->update($id, $user_id, $laporan_data);
            } else {
                // Jika ID laporan tidak ada, itu adalah proses tambah, maka panggil fungsi insert dari Laporan_model dengan memberikan parameter data laporan yang sudah disiapkan untuk disimpan ke dalam database
                $this->Laporan_model->insert($laporan_data);
            }
            // Setelah proses tambah atau ubah selesai, arahkan kembali ke halaman utama dompet untuk menampilkan data laporan yang sudah diperbarui
            redirect('dompet');
        }

        // Menyiapkan data yang akan dikirim ke view, termasuk data laporan yang dimiliki oleh pengguna yang sedang login dan nama pengguna untuk ditampilkan di halaman utama dompet, 
        // serta data laporan yang akan diedit jika ada parameter GET 'edit' untuk memastikan bahwa hanya laporan milik pengguna yang bersangkutan yang dapat diedit 
        $data['editData'] = null;
        // Jika ada parameter GET 'edit', maka ambil data laporan yang akan diedit berdasarkan ID laporan yang didapatkan dari parameter GET 'edit' dan user_id untuk memastikan bahwa hanya laporan milik pengguna yang bersangkutan yang dapat diedit, 
        // kemudian simpan data laporan yang akan diedit ke dalam variabel 'editData' untuk dikirim ke view agar dapat ditampilkan di form edit laporan
        if ($this->input->get('edit')) {
            // Mengambil data laporan yang akan diedit berdasarkan ID laporan yang didapatkan dari parameter GET 'edit' dan user_id 
            // untuk memastikan bahwa hanya laporan milik pengguna yang bersangkutan yang dapat diedit dengan memanggil fungsi get_by_id dari Laporan_model dan memberikan parameter ID laporan yang akan diedit serta user_id, 
            // kemudian simpan data laporan yang akan diedit ke dalam variabel 'editData' untuk dikirim ke view agar dapat ditampilkan di form edit laporan
            $data['editData'] = $this->Laporan_model->get_by_id($this->input->get('edit'), $user_id);
        }
        // Mengambil data laporan yang dimiliki oleh pengguna yang sedang login dengan memanggil fungsi get_all dari Laporan_model dan memberikan parameter user_id untuk memastikan bahwa hanya laporan milik pengguna yang bersangkutan yang dapat diakses, 
        // kemudian simpan data laporan ke dalam variabel 'dataLaporan' untuk dikirim ke view agar dapat ditampilkan di halaman utama dompet
        $data['dataLaporan'] = $this->Laporan_model->get_all($user_id);
        // Mendapatkan nama pengguna dari session untuk ditampilkan di halaman utama dompet, kemudian simpan nama pengguna ke dalam variabel 'nama' untuk dikirim ke view agar dapat ditampilkan di halaman utama dompet
        $data['nama'] = $this->session->userdata('nama');

        // Memuat view 'dompet/index' dan mengirimkan data yang sudah disiapkan untuk ditampilkan di halaman utama dompet 
        // dengan menggunakan fungsi load->view dari CodeIgniter dan memberikan parameter nama view yang akan dimuat serta data yang akan dikirim ke view untuk ditampilkan di halaman utama dompet
        $this->load->view('dompet/index', $data);
    }
}
?>