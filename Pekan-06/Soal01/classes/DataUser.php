<?php
require_once 'Database.php';

class DataUser {
    private $db;
    private $user_id;

    public function __construct($user_id) {
        $this->db = new Database();
        $this->user_id = (int)$user_id;
    }

    // Upload Foto
    private function uploadFoto($file) {
    if (isset($file) && $file['error'] === UPLOAD_ERR_OK) {
        $target_dir = __DIR__ . '/../uploads/';   // path lebih aman
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        $file_name = uniqid() . '_' . basename($file['name']);
        $target_file = $target_dir . $file_name;

        if (move_uploaded_file($file['tmp_name'], $target_file)) {
            return 'uploads/' . $file_name;   // simpan path relatif
        } else {
            error_log("Upload gagal: " . $file['name'] . " -> " . $target_file);
            return null;
        }
    }
    return null;
}

    public function create($nama, $email, $foto = null) {
        $foto_path = $this->uploadFoto($foto);
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("INSERT INTO data_user (user_id, nama, email, foto) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $this->user_id, $nama, $email, $foto_path);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function readAll() {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("SELECT * FROM data_user WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->bind_param("i", $this->user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result;
    }

    public function getById($id) {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("SELECT * FROM data_user WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ii", $id, $this->user_id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $result;
    }

    public function update($id, $nama, $email, $foto = null) {
        $foto_path = $this->uploadFoto($foto);
        $conn = $this->db->getConnection();

        if ($foto_path) {
            $old = $this->getById($id);
            if ($old && $old['foto'] && file_exists($old['foto'])) {
                unlink($old['foto']);
            }
            $stmt = $conn->prepare("UPDATE data_user SET nama=?, email=?, foto=? WHERE id=? AND user_id=?");
            $stmt->bind_param("ssssi", $nama, $email, $foto_path, $id, $this->user_id);
        } else {
            $stmt = $conn->prepare("UPDATE data_user SET nama=?, email=? WHERE id=? AND user_id=?");
            $stmt->bind_param("ssii", $nama, $email, $id, $this->user_id);
        }
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function delete($id) {
        $old = $this->getById($id);
        if ($old && $old['foto'] && file_exists($old['foto'])) {
            unlink($old['foto']);
        }
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("DELETE FROM data_user WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ii", $id, $this->user_id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
}
?>