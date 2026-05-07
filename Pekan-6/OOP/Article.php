<?php
require_once "Database.php";

class Article {
    private $db;

    public function __construct() {
        $this->db = (new Database())->conn;
    }

    public function create($title, $content, $file) {
        $targetDir = "uploads/";
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
        $fileName = time() . "_" . basename($file["name"]);
        $targetFile = $targetDir . $fileName;

        if (move_uploaded_file($file["tmp_name"], $targetFile)) {
            $sql = "INSERT INTO articles (title, content, file_path) VALUES (?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("sss", $title, $content, $targetFile);
            return $stmt->execute();
        }
        return false;
    }

    public function readAll() {
        $sql = "SELECT * FROM articles";
        return $this->db->query($sql);
    }

    public function update($id, $title, $content, $file = null) {
        $filePath = null;
        if ($file && !empty($file["name"])) {
            $targetDir = "uploads/";
            $fileName = time() . "_" . basename($file["name"]);
            $targetFile = $targetDir . $fileName;
            if (move_uploaded_file($file["tmp_name"], $targetFile)) {
                $filePath = $targetFile;
            }
        }

        if ($filePath) {
            $sql = "UPDATE articles SET title=?, content=?, file_path=? WHERE id=?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("sssi", $title, $content, $filePath, $id);
        } else {
            $sql = "UPDATE articles SET title=?, content=? WHERE id=?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("ssi", $title, $content, $id);
        }
        return $stmt->execute();
    }

    public function delete($id) {
        $sql = "SELECT file_path FROM articles WHERE id=?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $file = $result->fetch_assoc()["file_path"];
        if (file_exists($file)) unlink($file);

        $sql = "DELETE FROM articles WHERE id=?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>
