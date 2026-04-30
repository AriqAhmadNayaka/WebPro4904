<?php
// Mencegah file controller diakses langsung tanpa lewat CodeIgniter.
defined('BASEPATH') OR exit('No direct script access allowed');

// Controller API untuk mengelola data post dengan method GET, POST, PUT, dan DELETE.
class Post extends MX_Controller {

    // Constructor dijalankan otomatis saat controller dipanggil.
    public function __construct()
    {
        // Memanggil constructor bawaan MX_Controller.
        parent::__construct();
        // Memuat model Post_model untuk akses database post.
        $this->load->model('Post_model');
        // Memuat library JWT untuk validasi token API.
        $this->load->library('jwt');
    }

    // Method utama untuk mengarahkan request API berdasarkan HTTP method dan ID.
    public function handle($id = null)
    {
        // Mengambil HTTP method request lalu mengubahnya menjadi huruf besar.
        $method = strtoupper($this->input->method(TRUE));

        // Jika method GET tanpa ID, arahkan ke index untuk mengambil semua post.
        if ($method === 'GET' && $id === null) {
            // Memanggil method index.
            return $this->index();
        }
        // Jika method GET dengan ID, arahkan ke show untuk mengambil satu post.
        if ($method === 'GET' && $id !== null) {
            // Memanggil method show berdasarkan ID.
            return $this->show($id);
        }
        // Jika method POST, arahkan ke create untuk membuat post baru.
        if ($method === 'POST') {
            // Memanggil method create.
            return $this->create();
        }
        // Jika method PUT dengan ID, arahkan ke update untuk mengubah post.
        if ($method === 'PUT' && $id !== null) {
            // Memanggil method update berdasarkan ID.
            return $this->update($id);
        }
        // Jika method DELETE dengan ID, arahkan ke delete untuk menghapus post.
        if ($method === 'DELETE' && $id !== null) {
            // Memanggil method delete berdasarkan ID.
            return $this->delete($id);
        }

        // Mengirim response jika kombinasi method dan URL tidak diizinkan.
        return $this->_response(false, 'Method not allowed', null, 405);
    }

    // Endpoint untuk mengambil semua data post.
    public function index()
    {
        // Mengatur response menjadi JSON.
        $this->_json();
        // Mengecek token JWT sebelum mengakses data.
        if (!$this->_auth_payload()) return;

        // Mengambil semua data post dari model.
        $posts = $this->Post_model->get_all();
        // Mengirim response sukses berisi semua post.
        return $this->_response(true, 'Posts retrieved successfully', $posts);
    }

    // Endpoint untuk mengambil satu data post berdasarkan ID.
    public function show($id)
    {
        // Mengatur response menjadi JSON.
        $this->_json();
        // Mengecek token JWT sebelum mengakses data.
        if (!$this->_auth_payload()) return;

        // Mengambil data post berdasarkan ID.
        $post = $this->Post_model->get_by_id($id);
        // Mengecek apakah post tidak ditemukan.
        if (!$post) {
            // Mengirim response error jika post tidak ada.
            return $this->_response(false, 'Post not found', null, 404);
        }

        // Mengirim response sukses berisi detail post.
        return $this->_response(true, 'Post retrieved successfully', $post);
    }

    // Endpoint untuk membuat data post baru.
    public function create()
    {
        // Mengatur response menjadi JSON.
        $this->_json();
        // Mengecek token JWT sebelum membuat data.
        if (!$this->_auth_payload()) return;

        // Ambil input request dengan dukungan JSON dan form-data dari Postman.
        $input = $this->_request_input();
        // Mengecek apakah field wajib belum lengkap.
        if (empty($input['title']) || empty($input['author']) || empty($input['article'])) {
            // Mengirim response error validasi.
            return $this->_response(false, 'Title, author, and article are required', null, 400);
        }

        // Menyimpan post baru ke database melalui model.
        $post_id = $this->Post_model->create(array(
            // Mengisi judul post dari input JSON.
            'title' => $input['title'],
            // Mengisi nama author dari input JSON.
            'author' => $input['author'],
            // Mengisi artikel dari input JSON.
            'article' => $input['article']
        ));

        // Mengirim response sukses beserta data post yang baru dibuat.
        return $this->_response(true, 'Post created successfully', $this->Post_model->get_by_id($post_id), 201);
    }

    // Endpoint untuk mengubah data post berdasarkan ID.
    public function update($id)
    {
        // Mengatur response menjadi JSON.
        $this->_json();
        // Mengecek token JWT sebelum mengubah data.
        if (!$this->_auth_payload()) return;

        // Mengecek apakah post dengan ID tersebut ada.
        if (!$this->Post_model->exists($id)) {
            // Mengirim response error jika post tidak ditemukan.
            return $this->_response(false, 'Post not found', null, 404);
        }

        // Ambil input request dengan dukungan JSON dan form-data dari Postman.
        $input = $this->_request_input();
        // Menyiapkan array kosong untuk data yang akan diupdate.
        $data = array();
        // Mengulang field yang boleh diupdate.
        foreach (array('title', 'author', 'article') as $field) {
            // Mengecek apakah field tersebut dikirim pada input.
            if (isset($input[$field])) {
                // Memasukkan nilai field ke array update.
                $data[$field] = $input[$field];
            }
        }

        // Mengecek apakah tidak ada data yang dikirim untuk diupdate.
        if (empty($data)) {
            // Mengirim response error jika request tidak membawa data update.
            return $this->_response(false, 'No data to update', null, 400);
        }

        // Menjalankan update data melalui model.
        $this->Post_model->update($id, $data);
        // Mengirim response sukses beserta data terbaru.
        return $this->_response(true, 'Post updated successfully', $this->Post_model->get_by_id($id));
    }

    // Endpoint untuk menghapus data post berdasarkan ID.
    public function delete($id)
    {
        // Mengatur response menjadi JSON.
        $this->_json();
        // Mengecek token JWT sebelum menghapus data.
        if (!$this->_auth_payload()) return;

        // Mengecek apakah post dengan ID tersebut ada.
        if (!$this->Post_model->exists($id)) {
            // Mengirim response error jika post tidak ditemukan.
            return $this->_response(false, 'Post not found', null, 404);
        }

        // Menghapus data post dari database melalui model.
        $this->Post_model->delete($id);
        // Mengirim response sukses setelah data dihapus.
        return $this->_response(true, 'Post deleted successfully');
    }

    // Method private untuk mengambil dan memverifikasi token JWT.
    private function _auth_payload()
    {
        // Mengambil token dari header Authorization request.
        $token = $this->jwt->get_token_from_request();
        // Memverifikasi token dan mengambil payload-nya.
        $payload = $this->jwt->verify($token);
        // Mengecek apakah token tidak valid.
        if (!$payload) {
            // Mengirim response unauthorized jika token salah atau kosong.
            $this->_response(false, 'Unauthorized or invalid token', null, 401);
            // Mengembalikan false sebagai tanda autentikasi gagal.
            return false;
        }
        // Mengembalikan payload jika token valid.
        return $payload;
    }

    // Method private untuk mengatur response menjadi JSON.
    private function _json()
    {
        // Bersihkan buffer hanya jika memang ada buffer aktif agar tidak memicu notice.
        if (ob_get_level() > 0) {
            ob_clean();
        }

        // Gunakan output class CI supaya header JSON lebih konsisten.
        $this->output->set_content_type('application/json', 'utf-8');
    }

    // Method private untuk mengambil input request dari JSON, form-data, atau input stream.
    private function _request_input()
    {
        // Ambil data mentah request untuk mendukung JSON dan x-www-form-urlencoded.
        $raw_input = trim(file_get_contents('php://input'));

        // Prioritaskan body JSON jika valid.
        if ($raw_input !== '') {
            $decoded = json_decode($raw_input, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return $decoded;
            }
        }

        // POST form-data atau x-www-form-urlencoded.
        $post_input = $this->input->post(NULL, true);
        if (is_array($post_input) && !empty($post_input)) {
            return $post_input;
        }

        // PUT/PATCH/DELETE yang dibaca dari input stream.
        $stream_input = $this->input->input_stream(NULL, true);
        if (is_array($stream_input) && !empty($stream_input)) {
            return $stream_input;
        }

        // Fallback terakhir jika Postman mengirim body query-string style.
        if ($raw_input !== '') {
            parse_str($raw_input, $parsed_input);
            if (is_array($parsed_input) && !empty($parsed_input)) {
                return $parsed_input;
            }
        }

        // Jika tidak ada input yang valid, kembalikan array kosong.
        return array();
    }

    // Method private untuk mengirim response JSON standar.
    private function _response($status, $message, $data = null, $code = 200)
    {
        // Mengatur HTTP status code response.
        http_response_code($code);
        // Membuat struktur dasar response API.
        $response = array('status' => $status, 'message' => $message);
        // Mengecek apakah ada data tambahan yang perlu dikirim.
        if ($data !== null) {
            // Menambahkan data ke response.
            $response['data'] = $data;
        }
        // Mencetak response dalam format JSON.
        echo json_encode($response, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        // Menghentikan eksekusi agar tidak ada output tambahan.
        exit;
    }
}
