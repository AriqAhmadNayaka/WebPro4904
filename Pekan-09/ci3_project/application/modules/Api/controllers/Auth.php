<?php
// Mencegah file controller diakses langsung tanpa lewat CodeIgniter.
defined('BASEPATH') OR exit('No direct script access allowed');

// Controller API untuk fitur autentikasi user seperti register, login, logout, dan data user login.
class Auth extends MX_Controller {

    // Constructor dijalankan otomatis saat controller dipanggil.
    public function __construct()
    {
        // Memanggil constructor bawaan MX_Controller.
        parent::__construct();
        // Memuat model User_model dari module Api dengan alias User_model.
        $this->load->model('Api/User_model', 'User_model');
        // Memuat library JWT untuk membuat dan memverifikasi token.
        $this->load->library('jwt');
        // Memuat library form_validation untuk kebutuhan validasi input.
        $this->load->library('form_validation');
    }

    // Endpoint API untuk mendaftarkan user baru.
    public function register()
    {
        // Mengatur response menjadi JSON.
        $this->_json();

        // Menjalankan proses register dalam blok try agar error bisa ditangkap.
        try {
            // Mengambil input request dari JSON body atau form POST.
            $input = $this->_request_input();

            $username = isset($input['username']) ? trim($input['username']) : '';
            if ($username === '' && isset($input['name'])) {
                $username = trim($input['name']);
            }
            $email = isset($input['email']) ? strtolower(trim($input['email'])) : '';
            $password = isset($input['password']) ? $input['password'] : '';

            // Mengecek apakah field username/name, email, dan password kosong.
            if ($username === '' || $email === '' || $password === '') {
                // Mengirim response error jika input wajib belum lengkap.
                return $this->_response(false, 'Username/name, email, and password are required', null, 400);
            }

            // Mengecek apakah format email valid.
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                // Mengirim response error jika format email salah.
                return $this->_response(false, 'Invalid email format', null, 400);
            }

            // Mengecek apakah email sudah terdaftar.
            if ($this->User_model->email_exists($email)) {
                // Mengirim response conflict jika email sudah digunakan.
                return $this->_response(false, 'Email already registered', null, 409);
            }

            // Mengecek apakah panjang password minimal 5 karakter, sama seperti form register web.
            if (strlen($password) < 5) {
                // Mengirim response error jika password terlalu pendek.
                return $this->_response(false, 'Password must be at least 5 characters', null, 400);
            }

            // Menyiapkan data user yang akan disimpan ke database.
            $user_data = array(
                // Membersihkan nama dari karakter HTML.
                'username' => htmlspecialchars($username),
                // Mengubah email menjadi huruf kecil agar konsisten.
                'email' => $email,
                // Mengenkripsi password menggunakan bcrypt sebelum disimpan.
                'password' => password_hash($password, PASSWORD_BCRYPT),
                // Role default sama seperti register web.
                'role' => 'sekolah'
            );

            // Menyimpan user baru melalui model dan mengambil ID user.
            $user_id = $this->User_model->create($user_data);
            // Mengecek apakah proses pembuatan user gagal.
            if (!$user_id) {
                // Mengirim response error server jika user gagal dibuat.
                return $this->_response(false, 'Failed to create user', null, 500);
            }

            // Mengambil kembali data user yang baru dibuat.
            $user = $this->User_model->get_by_id($user_id);
            // Membuat token JWT berisi data dasar user.
            $token = $this->jwt->create(array(
                // Menyimpan ID user ke payload token.
                'id' => $user->id,
                // Menyimpan nama user ke payload token.
                'username' => $user->username,
                // Menyimpan email user ke payload token.
                'email' => $user->email
            ));

            // Mengirim response sukses register beserta data user dan token.
            return $this->_response(true, 'User registered successfully', array(
                // ID user yang baru terdaftar.
                'id' => $user->id,
                // Nama user yang baru terdaftar.
                'username' => $user->username,
                // Email user yang baru terdaftar.
                'email' => $user->email,
                // Role user.
                'role' => $user->role,
                // Waktu user dibuat.
                'created_at' => $user->created_at,
                // Token akses untuk autentikasi request berikutnya.
                'access_token' => $token,
                // Jenis token yang digunakan pada header Authorization.
                'token_type' => 'Bearer'
            ), 201);
        // Menangkap exception jika terjadi error server.
        } catch (Exception $e) {
            // Mengirim response error server beserta pesan exception.
            return $this->_response(false, 'Server error: ' . $e->getMessage(), null, 500);
        }
    }

    // Endpoint API untuk login user.
    public function login()
    {
        // Mengatur response menjadi JSON.
        $this->_json();

        // Menjalankan proses login dalam blok try.
        try {
            // Mengambil input request dari JSON body atau form POST.
            $input = $this->_request_input();

            // Mengecek apakah email dan password sudah dikirim.
            if (empty($input['email']) || empty($input['password'])) {
                // Mengirim response error jika email/password kosong.
                return $this->_response(false, 'Email and password are required', null, 400);
            }

            // Mengambil data user berdasarkan email.
            $user = $this->User_model->get_by_email($input['email']);
            // Mengecek apakah user tidak ditemukan atau password salah.
            if (!$user || !password_verify($input['password'], $user->password)) {
                // Mengirim response unauthorized jika kredensial salah.
                return $this->_response(false, 'Invalid email or password', null, 401);
            }

            // Membuat token JWT untuk user yang berhasil login.
            $token = $this->jwt->create(array(
                // Menyimpan ID user ke payload token.
                'id' => $user->id,
                // Menyimpan nama user ke payload token.
                'username' => $user->username,
                // Menyimpan email user ke payload token.
                'email' => $user->email
            ));

            // Mengirim response sukses login beserta token.
            return $this->_response(true, 'Login successful', array(
                // ID user yang login.
                'id' => $user->id,
                // Nama user yang login.
                'username' => $user->username,
                // Email user yang login.
                'email' => $user->email,
                // Role user.
                'role' => $user->role,
                // Token akses untuk autentikasi API.
                'access_token' => $token,
                // Jenis token untuk header Authorization.
                'token_type' => 'Bearer'
            ));
        // Menangkap exception jika terjadi error server.
        } catch (Exception $e) {
            // Mengirim response error server.
            return $this->_response(false, 'Server error: ' . $e->getMessage(), null, 500);
        }
    }

    // Endpoint API logout.
    public function logout()
    {
        // Mengatur response menjadi JSON.
        $this->_json();
        // Mengirim response logout berhasil, token biasanya dihapus di sisi client.
        return $this->_response(true, 'Logout successful');
    }

    // Endpoint API untuk mengambil data user yang sedang login.
    public function me()
    {
        // Mengatur response menjadi JSON.
        $this->_json();
        // Memverifikasi token dan mengambil payload user.
        $payload = $this->_auth_payload();
        // Jika token tidak valid, proses dihentikan karena response error sudah dikirim.
        if (!$payload) {
            return;
        }

        // Mengambil data user berdasarkan ID dari payload token.
        $user = $this->User_model->get_by_id($payload->id);
        // Mengecek apakah user masih ada di database.
        if (!$user) {
            // Mengirim response error jika user tidak ditemukan.
            return $this->_response(false, 'User not found', null, 404);
        }

        // Mengirim response sukses berisi detail user.
        return $this->_response(true, 'User detail', array(
            // ID user.
            'id' => $user->id,
            // Nama user.
            'username' => $user->username,
            // Email user.
            'email' => $user->email,
            // Role user.
            'role' => $user->role,
            // Waktu user dibuat.
            'created_at' => $user->created_at
        ));
    }

    // Method private untuk mengambil dan memverifikasi token JWT dari request.
    private function _auth_payload()
    {
        // Mengambil token dari header Authorization request.
        $token = $this->jwt->get_token_from_request();
        // Memverifikasi token dan mengambil payload-nya.
        $payload = $this->jwt->verify($token);
        // Mengecek apakah token tidak valid atau kosong.
        if (!$payload) {
            // Mengirim response unauthorized jika token gagal diverifikasi.
            $this->_response(false, 'Unauthorized or invalid token', null, 401);
            // Mengembalikan false sebagai penanda autentikasi gagal.
            return false;
        }
        // Mengembalikan payload token jika valid.
        return $payload;
    }

    // Method private untuk mengatur response sebagai JSON.
    private function _json()
    {
        // Membersihkan output buffer agar JSON tidak tercampur output lain.
        if (ob_get_level() > 0) {
            ob_clean();
        }
        // Mengatur header response menjadi JSON UTF-8.
        header('Content-Type: application/json; charset=utf-8');
    }

    // Method private untuk mengambil input request.
    private function _request_input()
    {
        // Membaca request body JSON lalu mengubahnya menjadi array.
        $input = json_decode(file_get_contents('php://input'), true);
        // Jika body bukan JSON valid, gunakan data form POST.
        if (!is_array($input)) {
            // Mengambil data dari request POST CodeIgniter.
            $input = $this->input->post();
        }
        // Mengembalikan input jika array, jika bukan array mengembalikan array kosong.
        return is_array($input) ? $input : array();
    }

    // Method private untuk mengirim response JSON standar.
    private function _response($status, $message, $data = null, $code = 200)
    {
        // Mengatur HTTP status code response.
        http_response_code($code);
        // Membuat struktur dasar response.
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
