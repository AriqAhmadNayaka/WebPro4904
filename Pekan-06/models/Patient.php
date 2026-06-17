<?php
class Patient {
    private $conn;

    public $id, $nama, $umur, $penyakit, $file;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        return $this->conn->query("SELECT * FROM patients");
    }

    public function save() {
        if ($this->id) {
            $q = "UPDATE patients SET nama=?, umur=?, penyakit=?, file=? WHERE id=?";
            $stmt = $this->conn->prepare($q);
            $stmt->bind_param("sissi", $this->nama, $this->umur, $this->penyakit, $this->file, $this->id);
        } else {
            $q = "INSERT INTO patients (nama, umur, penyakit, file) VALUES (?, ?, ?, ?)";
            $stmt = $this->conn->prepare($q);
            $stmt->bind_param("siss", $this->nama, $this->umur, $this->penyakit, $this->file);
        }
        return $stmt->execute();
    }

    public function delete() {
        $stmt = $this->conn->prepare("DELETE FROM patients WHERE id=?");
        $stmt->bind_param("i", $this->id);
        return $stmt->execute();
    }
}
?>