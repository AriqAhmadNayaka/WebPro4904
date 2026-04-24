<?php
class Database {
    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $db_name = "webpro";
    public $conn;

    public function __construct() {
        $this->conn = mysqli_connect($this->host, $this->username, $this->password, $this->db_name);
        if (!$this->conn) {
            die("Koneksi gagal: " . mysqli_connect_error());
        }
    }

    // --- FUNGSI BARU UNTUK REGISTRASI ---
    public function register($username, $password) {
        $u = mysqli_real_escape_string($this->conn, trim($username));
        
        // 1. Cek apakah username sudah digunakan
        $checkQuery = "SELECT * FROM users WHERE username = '$u' LIMIT 1";
        $checkResult = mysqli_query($this->conn, $checkQuery);
        
        if (mysqli_num_rows($checkResult) > 0) {
            return "Username sudah terdaftar! Silakan gunakan nama lain.";
        }

        // 2. Enkripsi password (Hashing)
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        // 3. Masukkan ke database
        $query = "INSERT INTO users (username, password) VALUES ('$u', '$hashedPassword')";
        
        if (mysqli_query($this->conn, $query)) {
            return true;
        } else {
            return "Gagal menyimpan data ke database.";
        }
    }

    public function login($username, $password) {
        $u = mysqli_real_escape_string($this->conn, $username);
        $query = "SELECT * FROM users WHERE username = '$u' LIMIT 1";
        $result = mysqli_query($this->conn, $query);
        $user = mysqli_fetch_assoc($result);

        if (!$user) {
            return null;
        }

        // Mendukung pengecekan teks biasa (untuk data lama) atau hash (untuk registrasi baru)
        if ($password === $user['password'] || password_verify($password, $user['password'])) {
            return $user;
        }

        return null;
    }

    public function getAllMenus() {
        $query = "SELECT * FROM menus ORDER BY id DESC";
        return mysqli_query($this->conn, $query);
    }

    public function getMenuById($id) {
        $id = (int) $id;
        $query = "SELECT * FROM menus WHERE id = $id LIMIT 1";
        $result = mysqli_query($this->conn, $query);
        return $result ? mysqli_fetch_assoc($result) : null;
    }

    public function saveMenu($post, $files) {
        $id = isset($post['id']) ? (int) $post['id'] : 0;
        $nama = mysqli_real_escape_string($this->conn, trim($post['nama'] ?? ''));
        $deskripsi = mysqli_real_escape_string($this->conn, trim($post['deskripsi'] ?? ''));
        $rating = (float) ($post['rating'] ?? 0);

        if ($nama === '' || $deskripsi === '' || $rating < 0 || $rating > 5) {
            return false;
        }

        $folder = __DIR__ . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR;
        if (!is_dir($folder)) {
            mkdir($folder, 0777, true);
        }

        $gambar = null;
        $menuLama = $id ? $this->getMenuById($id) : null;

        if (isset($files['gambar']) && ($files['gambar']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
            if (($files['gambar']['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
                return false;
            }

            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            $ext = strtolower(pathinfo($files['gambar']['name'], PATHINFO_EXTENSION));

            if (!in_array($ext, $allowed, true)) {
                return false;
            }

            $gambar = uniqid('menu_', true) . '.' . $ext;
            $target = $folder . $gambar;

            if (!move_uploaded_file($files['gambar']['tmp_name'], $target)) {
                return false;
            }

            if ($menuLama && !empty($menuLama['gambar'])) {
                $oldFile = $folder . $menuLama['gambar'];
                if (is_file($oldFile)) {
                    unlink($oldFile);
                }
            }
        } else {
            $gambar = $menuLama['gambar'] ?? '';
        }

        if ($id > 0) {
            $query = "UPDATE menus SET nama='$nama', deskripsi='$deskripsi', rating='$rating', gambar='" . mysqli_real_escape_string($this->conn, $gambar) . "' WHERE id=$id";
        } else {
            if ($gambar === '') {
                return false;
            }
            $query = "INSERT INTO menus (nama, deskripsi, rating, gambar) VALUES ('$nama', '$deskripsi', '$rating', '" . mysqli_real_escape_string($this->conn, $gambar) . "')";
        }

        return mysqli_query($this->conn, $query);
    }

    public function deleteMenu($id) {
        $id = (int) $id;
        $menu = $this->getMenuById($id);

        if (!$menu) {
            return false;
        }

        $query = "DELETE FROM menus WHERE id = $id";
        $deleted = mysqli_query($this->conn, $query);

        if ($deleted && !empty($menu['gambar'])) {
            $file = __DIR__ . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . $menu['gambar'];
            if (is_file($file)) {
                unlink($file);
            }
        }

        return $deleted;
    }

    public function imageExists($imageName) {
        if (empty($imageName)) return false;
        return file_exists("img/" . $imageName);
    }

    public function getImageWebPath($imageName) {
        return "img/" . $imageName;
    }
}