<?php
// Mencegah file controller diakses langsung tanpa lewat framework CodeIgniter.
defined('BASEPATH') OR exit('No direct script access allowed');

// Controller HMVC untuk fitur CRUD with AJAX.
class Crudjs extends MX_Controller {

    // Constructor dijalankan otomatis saat controller dipanggil.
    public function __construct()
    {
        // Memanggil constructor dari MX_Controller.
        parent::__construct();
        // Memuat model Crudjs_model untuk akses database.
        $this->load->model('Crudjs_model');
        // Memuat helper URL dan form.
        $this->load->helper(array('url', 'form'));
        // Memuat library validasi form, upload file, dan session.
        $this->load->library(array('form_validation', 'upload', 'session'));
    }

    // Method untuk mengecek kondisi database, tabel, folder upload, dan sample data.
    public function debug()
    {
        // Menentukan folder upload gambar post.
        $upload_path = './uploads/posts/';
        // Menyusun informasi debug dalam bentuk array.
        $debug_info = array(
            // Informasi koneksi database.
            'database' => array(
                // Mengecek apakah database berhasil terkoneksi.
                'connected' => $this->db->conn_id ? 'Yes' : 'No',
                // Menampilkan nama database yang sedang digunakan.
                'database' => $this->db->database
            ),
            // Mengecek apakah tabel posts ada di database.
            'table_exists' => $this->db->table_exists('posts') ? 'Yes' : 'No',
            // Informasi folder upload.
            'upload_folder' => array(
                // Mengecek apakah folder upload tersedia.
                'exists' => is_dir($upload_path) ? 'Yes' : 'No',
                // Mengecek apakah folder upload bisa ditulis.
                'writable' => is_writable($upload_path) ? 'Yes' : 'No'
            ),
            // Tempat menyimpan contoh data dari database.
            'sample_records' => array()
        );

        // Mencoba mengambil data untuk keperluan debug.
        try {
            // Mengambil semua data post dari model.
            $records = $this->Crudjs_model->get_all();
            // Mengecek apakah data tidak kosong.
            if (!empty($records)) {
                // Menyimpan jumlah data dan data pertama sebagai contoh.
                $debug_info['sample_records'] = array(
                    // Menghitung jumlah record.
                    'count' => count($records),
                    // Mengambil record pertama untuk contoh.
                    'first_record' => $records[0]
                );
            }
        // Menangkap error jika proses pengambilan data gagal.
        } catch (Exception $e) {
            // Menyimpan pesan error ke array debug.
            $debug_info['sample_records']['error'] = $e->getMessage();
        }

        // Menampilkan informasi debug dalam format JSON yang rapi.
        echo '<pre>' . json_encode($debug_info, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . '</pre>';
    }

    // Method untuk menampilkan halaman utama CRUD with AJAX.
    public function index()
    {
        // Mengirim judul halaman ke view.
        $data['title'] = 'CRUD with AJAX';
        // Memuat view utama CRUD AJAX.
        $this->load->view('crudjs/index', $data);
    }

    // Method AJAX untuk mengambil semua data post.
    public function get_all()
    {
        // Mengatur response menjadi JSON.
        $this->_json();

        // Mencoba mengambil data dari database.
        try {
            // Mengambil semua data melalui model.
            $records = $this->Crudjs_model->get_all();
            // Mengirim response sukses beserta data dalam format JSON.
            echo json_encode(array('status' => 'success', 'data' => $records), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        // Menangkap error jika data gagal diambil.
        } catch (Exception $e) {
            // Mengirim response error dalam format JSON.
            echo json_encode(array('status' => 'error', 'message' => 'Failed to load records: ' . $e->getMessage()));
        }
        // Menghentikan eksekusi agar tidak ada output tambahan.
        exit;
    }

    // Method AJAX untuk mengambil satu data post berdasarkan ID.
    public function get_record($id)
    {
        // Mengatur response menjadi JSON.
        $this->_json();

        // Mencoba mengambil satu data dari database.
        try {
            // Mengecek apakah data dengan ID tersebut ada.
            if (!$this->Crudjs_model->exists($id)) {
                // Mengirim response error jika data tidak ditemukan.
                echo json_encode(array('status' => 'error', 'message' => 'Record not found'));
                // Menghentikan eksekusi setelah response dikirim.
                exit;
            }

            // Mengirim response sukses berisi detail data post.
            echo json_encode(array(
                // Status response berhasil.
                'status' => 'success',
                // Data post berdasarkan ID.
                'data' => $this->Crudjs_model->get_by_id($id)
            ), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        // Menangkap error jika proses ambil data gagal.
        } catch (Exception $e) {
            // Mengirim response error dalam format JSON.
            echo json_encode(array('status' => 'error', 'message' => 'Failed to load record: ' . $e->getMessage()));
        }
        // Menghentikan eksekusi agar output tetap bersih.
        exit;
    }

    // Method AJAX untuk menyimpan data post baru.
    public function store()
    {
        // Mengatur response menjadi JSON.
        $this->_json();

        // Mencoba menjalankan proses tambah data.
        try {
            // Validasi title wajib diisi dan maksimal 255 karakter.
            $this->form_validation->set_rules('title', 'Title', 'required|max_length[255]');
            // Validasi author wajib diisi dan maksimal 255 karakter.
            $this->form_validation->set_rules('author', 'Author', 'required|max_length[255]');
            // Validasi article wajib diisi.
            $this->form_validation->set_rules('article', 'Article', 'required');

            // Mengecek apakah validasi form gagal.
            if ($this->form_validation->run() === FALSE) {
                // Mengirim pesan error validasi dalam format JSON.
                echo json_encode(array('status' => 'error', 'message' => 'Validation error: ' . strip_tags(validation_errors())));
                // Menghentikan proses simpan data.
                exit;
            }

            // Menyusun data yang akan dimasukkan ke database.
            $data = array(
                // Mengambil input title dari form POST.
                'title' => $this->input->post('title'),
                // Mengambil input author dari form POST.
                'author' => $this->input->post('author'),
                // Mengambil input article dari form POST.
                'article' => $this->input->post('article'),
                // Mengisi waktu pembuatan data.
                'created_at' => date('Y-m-d H:i:s'),
                // Mengisi waktu update awal data.
                'updated_at' => date('Y-m-d H:i:s')
            );

            // Mengecek apakah user mengupload gambar.
            if (!empty($_FILES['image']['name'])) {
                // Memanggil method upload gambar.
                $upload_result = $this->_upload_image();
                // Mengecek apakah upload berhasil.
                if ($upload_result['status']) {
                    // Menyimpan path gambar ke data yang akan dimasukkan.
                    $data['image'] = 'posts/' . $upload_result['file_name'];
                // Jika upload gagal.
                } else {
                    // Mengirim response error upload.
                    echo json_encode(array('status' => 'error', 'message' => $upload_result['error']));
                    // Menghentikan proses simpan data.
                    exit;
                }
            }

            // Menyimpan data ke database dan mengambil ID data baru.
            $record_id = $this->Crudjs_model->insert($data);
            // Mengecek apakah proses insert berhasil.
            if ($record_id) {
                // Mengirim response sukses beserta data yang baru dibuat.
                echo json_encode(array(
                    // Status response berhasil.
                    'status' => 'success',
                    // Pesan sukses untuk ditampilkan di halaman.
                    'message' => 'Record created successfully!',
                    // Mengambil kembali data baru berdasarkan ID.
                    'data' => $this->Crudjs_model->get_by_id($record_id)
                ), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            // Jika insert gagal.
            } else {
                // Mengirim response error gagal membuat data.
                echo json_encode(array('status' => 'error', 'message' => 'Failed to create record'));
            }
        // Menangkap error jika ada exception saat tambah data.
        } catch (Exception $e) {
            // Mengirim response error exception.
            echo json_encode(array('status' => 'error', 'message' => 'Exception error: ' . $e->getMessage()));
        }
        // Menghentikan eksekusi setelah response JSON dikirim.
        exit;
    }

    // Method AJAX untuk mengubah data post berdasarkan ID.
    public function update($id)
    {
        // Mengatur response menjadi JSON.
        $this->_json();

        // Mencoba menjalankan proses update data.
        try {
            // Mengecek apakah data dengan ID tersebut ada.
            if (!$this->Crudjs_model->exists($id)) {
                // Mengirim response error jika data tidak ditemukan.
                echo json_encode(array('status' => 'error', 'message' => 'Record not found'));
                // Menghentikan proses update.
                exit;
            }

            // Validasi title jika dikirim, maksimal 255 karakter.
            $this->form_validation->set_rules('title', 'Title', 'max_length[255]');
            // Validasi author jika dikirim, maksimal 255 karakter.
            $this->form_validation->set_rules('author', 'Author', 'max_length[255]');

            // Mengecek apakah validasi gagal.
            if ($this->form_validation->run() === FALSE) {
                // Mengirim response error validasi.
                echo json_encode(array('status' => 'error', 'message' => 'Validation error: ' . strip_tags(validation_errors())));
                // Menghentikan proses update.
                exit;
            }

            // Membuat data awal update berisi waktu update terbaru.
            $data = array('updated_at' => date('Y-m-d H:i:s'));
            // Mengulang field yang boleh diupdate.
            foreach (array('title', 'author', 'article') as $field) {
                // Mengecek apakah field dikirim dari form.
                if ($this->input->post($field)) {
                    // Memasukkan nilai field ke array data update.
                    $data[$field] = $this->input->post($field);
                }
            }

            // Mengecek apakah ada gambar baru yang diupload.
            if (!empty($_FILES['image']['name'])) {
                // Mengambil data lama untuk mengecek gambar sebelumnya.
                $old_record = $this->Crudjs_model->get_by_id($id);
                // Mengecek apakah data lama memiliki gambar.
                if ($old_record && $old_record->image) {
                    // Menyusun path gambar lama.
                    $old_image_path = './uploads/' . $old_record->image;
                    // Mengecek apakah file gambar lama ada.
                    if (file_exists($old_image_path)) {
                        // Menghapus gambar lama agar tidak menumpuk di folder upload.
                        unlink($old_image_path);
                    }
                }

                // Mengupload gambar baru.
                $upload_result = $this->_upload_image();
                // Mengecek apakah upload gambar baru berhasil.
                if ($upload_result['status']) {
                    // Menyimpan path gambar baru ke data update.
                    $data['image'] = 'posts/' . $upload_result['file_name'];
                // Jika upload gambar baru gagal.
                } else {
                    // Mengirim response error upload.
                    echo json_encode(array('status' => 'error', 'message' => $upload_result['error']));
                    // Menghentikan proses update.
                    exit;
                }
            }

            // Menjalankan update data melalui model.
            if ($this->Crudjs_model->update($id, $data)) {
                // Mengirim response sukses beserta data terbaru.
                echo json_encode(array(
                    // Status response berhasil.
                    'status' => 'success',
                    // Pesan sukses update.
                    'message' => 'Record updated successfully!',
                    // Mengambil data terbaru berdasarkan ID.
                    'data' => $this->Crudjs_model->get_by_id($id)
                ), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            // Jika update gagal.
            } else {
                // Mengirim response error gagal update.
                echo json_encode(array('status' => 'error', 'message' => 'Failed to update record'));
            }
        // Menangkap error jika ada exception saat update.
        } catch (Exception $e) {
            // Mengirim response error exception.
            echo json_encode(array('status' => 'error', 'message' => 'Exception error: ' . $e->getMessage()));
        }
        // Menghentikan eksekusi setelah response JSON dikirim.
        exit;
    }

    // Method AJAX untuk menghapus data post berdasarkan ID.
    public function delete($id)
    {
        // Mengatur response menjadi JSON.
        $this->_json();

        // Mencoba menjalankan proses hapus data.
        try {
            // Mengambil data post berdasarkan ID.
            $record = $this->Crudjs_model->get_by_id($id);
            // Mengecek apakah data ditemukan.
            if (!$record) {
                // Mengirim response error jika data tidak ditemukan.
                echo json_encode(array('status' => 'error', 'message' => 'Record not found'));
                // Menghentikan proses hapus.
                exit;
            }

            // Mengecek apakah data memiliki gambar.
            if ($record->image) {
                // Menyusun path file gambar yang akan dihapus.
                $image_path = './uploads/' . $record->image;
                // Mengecek apakah file gambar benar-benar ada.
                if (file_exists($image_path)) {
                    // Menghapus file gambar dari folder upload.
                    unlink($image_path);
                }
            }

            // Menghapus data dari database melalui model.
            if ($this->Crudjs_model->delete($id)) {
                // Mengirim response sukses jika data berhasil dihapus.
                echo json_encode(array('status' => 'success', 'message' => 'Record deleted successfully!'));
            // Jika proses delete database gagal.
            } else {
                // Mengirim response error gagal hapus data.
                echo json_encode(array('status' => 'error', 'message' => 'Failed to delete record'));
            }
        // Menangkap error jika ada exception saat hapus data.
        } catch (Exception $e) {
            // Mengirim response error exception.
            echo json_encode(array('status' => 'error', 'message' => 'Exception error: ' . $e->getMessage()));
        }
        // Menghentikan eksekusi setelah response JSON dikirim.
        exit;
    }

    // Method untuk mengetes apakah endpoint AJAX/API CRUD berjalan.
    public function test()
    {
        // Mengatur response menjadi JSON.
        $this->_json();
        // Mengirim response sukses sederhana.
        echo json_encode(array('status' => 'success', 'message' => 'API is working'));
        // Menghentikan eksekusi setelah response dikirim.
        exit;
    }

    // Method private untuk mengupload gambar post.
    private function _upload_image()
    {
        // Menentukan folder tujuan upload.
        $upload_path = './uploads/posts/';
        // Mengecek apakah folder upload belum ada.
        if (!is_dir($upload_path)) {
            // Membuat folder upload beserta subfoldernya jika belum ada.
            mkdir($upload_path, 0777, true);
        }

        // Mengambil nama asli file gambar dari input upload.
        $original_name = $_FILES['image']['name'];
        // Membersihkan nama file dari karakter yang tidak aman.
        $sanitized_name = preg_replace('/[^A-Za-z0-9._-]/', '_', $original_name);
        // Membuat nama file unik dengan tambahan timestamp.
        $file_name = time() . '_' . $sanitized_name;

        // Menentukan folder tujuan upload.
        $config['upload_path'] = $upload_path;
        // Menentukan tipe file gambar yang diizinkan.
        $config['allowed_types'] = 'jpg|jpeg|png';
        // Menentukan ukuran maksimal file dalam KB.
        $config['max_size'] = 2048;
        // Menentukan nama file hasil upload.
        $config['file_name'] = $file_name;
        // Mencegah file lama ditimpa jika ada nama yang sama.
        $config['overwrite'] = FALSE;

        // Menginisialisasi library upload dengan konfigurasi di atas.
        $this->upload->initialize($config);

        // Menjalankan proses upload dari input bernama image.
        if ($this->upload->do_upload('image')) {
            // Mengambil informasi file yang berhasil diupload.
            $upload_data = $this->upload->data();
            // Mengembalikan status berhasil dan nama file.
            return array('status' => TRUE, 'file_name' => $upload_data['file_name']);
        }

        // Mengembalikan status gagal dan pesan error upload.
        return array('status' => FALSE, 'error' => $this->upload->display_errors('', ''));
    }

    // Method private untuk mengatur header response JSON.
    private function _json()
    {
        // Membersihkan output buffer agar JSON tidak tercampur output lain.
        ob_clean();
        // Mengatur content type response menjadi JSON UTF-8.
        header('Content-Type: application/json; charset=utf-8');
    }
}
