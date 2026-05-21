<?php
/**
 * Produk OOP Class for CRUD & File Upload - Optimized Version
 */

class Produk {
    private $db;
    private $uploadDir = 'uploads/';

    public function __construct() {
        $this->db = Database::getInstance()->getPdo();
        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0777, true);
        }
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM produk WHERE nama IS NOT NULL AND nama != '' AND id IS NOT NULL ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM produk WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    private function uploadFile($file) {
        $allowed = ['jpg', 'jpeg', 'png'];
        $maxSize = 2 * 1024 * 1024; // 2MB

        // Validasi dasar file
        if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) return false;
        if (empty($file['name'])) return false;
        if ($file['size'] > $maxSize || $file['size'] == 0) return false;

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed)) return false;

        // Gunakan nama file unik
        $filename = uniqid('IMG_', true) . '.' . $ext;
        $target = $this->uploadDir . $filename;

        if (move_uploaded_file($file['tmp_name'], $target)) {
            return $filename;
        }
        return false;
    }

    public function save($data, $file = null, $id = null) {
        // 1. VALIDASI: Jangan proses jika nama produk kosong (Mencegah jejak kartu kosong)
        if (!isset($data['nama']) || empty(trim($data['nama']))) {
            throw new Exception('Nama produk wajib diisi!');
        }

        $nama = htmlspecialchars(trim($data['nama']));
        $harga = (int) ($data['harga'] ?? 0);
        $deskripsi = htmlspecialchars($data['deskripsi'] ?? '');
        $gambarBaru = null;
        $gambarExisting = null;

        if (!empty($data['gambar'])) {
            $candidate = basename($data['gambar']);
            if (file_exists($this->uploadDir . $candidate)) {
                $gambarExisting = $candidate;
            }
        }

        // 2. PROSES UPLOAD (Jika ada file baru)
        if ($file && !empty($file['name'])) {
            $gambarBaru = $this->uploadFile($file);
            if (!$gambarBaru) {
                throw new Exception('Format file tidak didukung atau ukuran terlalu besar!');
            }
        }

        if ($id) {
            // --- MODE UPDATE ---
            $produkLama = $this->getById($id);
            if (!$produkLama) throw new Exception('Data tidak ditemukan!');

            // Logika Gambar: Jika ada upload baru, hapus file lama. Jika tidak, pakai nama file lama.
            if ($gambarBaru) {
                if (!empty($produkLama['gambar']) && file_exists($this->uploadDir . $produkLama['gambar'])) {
                    unlink($this->uploadDir . $produkLama['gambar']);
                }
                q$gambarFinal = $gambarBaru;
            } else {
                $gambarFinal = $produkLama['gambar'];
            }

            $stmt = $this->db->prepare("UPDATE produk SET nama=?, harga=?, deskripsi=?, gambar=? WHERE id=?");
            $stmt->execute([$nama, $harga, $deskripsi, $gambarFinal, $id]);
        } else {
            // --- MODE CREATE ---
            $stmt = $this->db->prepare("INSERT INTO produk (nama, harga, deskripsi, gambar) VALUES (?, ?, ?, ?)");
            $stmt->execute([$nama, $harga, $deskripsi, $gambarBaru ?: $gambarExisting]);
        }
        return true;
    }

    public function delete($id) {
        $produk = $this->getById($id);
        if ($produk) {
            // Hapus file fisik dari folder
            if (!empty($produk['gambar']) && file_exists($this->uploadDir . $produk['gambar'])) {
                unlink($this->uploadDir . $produk['gambar']);
            }

            $stmt = $this->db->prepare("DELETE FROM produk WHERE id = ?");
            $stmt->execute([$id]);
            return true;
        }
        return false;
    }

public function getFeatured($limit = 6) {
        $stmt = $this->db->query("SELECT * FROM produk WHERE nama IS NOT NULL AND nama != '' ORDER BY RAND() LIMIT " . (int)$limit);
        return $stmt->fetchAll();
    }

    public function getByNama($nama) {
        $stmt = $this->db->prepare("SELECT * FROM produk WHERE nama = ?");
        $stmt->execute([$nama]);
        return $stmt->fetch();
    }
}
