<?php
// Class Pasien - turunan dari Model (inheritance)
require_once __DIR__ . "/Model.php";

class Pasien extends Model {
    private $uploadDir = "uploads/";
    private $allowedExt = ["jpg", "jpeg", "png", "gif", "webp", "pdf"];
    private $maxSize = 5242880; // 5MB

    public function __construct() {
        parent::__construct(); // panggil konstruktor parent
        $this->table = "data_pasien";
    }

    // proses upload file, return nama file atau null
    private function uploadFile($file) {
        if (!isset($file) || $file["error"] !== UPLOAD_ERR_OK) return null;

        if (!is_dir($this->uploadDir)) mkdir($this->uploadDir, 0755, true);

        $ext = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
        if ($file["size"] > $this->maxSize || !in_array($ext, $this->allowedExt)) {
            return null;
        }

        $nama = "doc_" . uniqid() . "_" . time() . "." . $ext;
        if (!move_uploaded_file($file["tmp_name"], $this->uploadDir . $nama)) {
            return null;
        }
        return $nama;
    }

    // hapus file dokumen dari folder uploads
    private function hapusFile($namaFile) {
        if ($namaFile && file_exists($this->uploadDir . $namaFile)) {
            unlink($this->uploadDir . $namaFile);
        }
    }

    // CREATE
    public function create($data, $file = null) {
        $dokNama = $this->uploadFile($file);
        $this->pdo->prepare(
            "INSERT INTO data_pasien (nama, usia, keluhan, dokumen, tanggal) VALUES (?, ?, ?, ?, ?)"
        )->execute([
            $data["nama"], $data["usia"], $data["keluhan"], $dokNama, date("Y-m-d")
        ]);
    }

    // UPDATE
    public function update($id, $data, $file = null) {
        $dokNama = $this->uploadFile($file);
        if ($dokNama) {
            // hapus file lama dulu
            $old = $this->find($id);
            if ($old) $this->hapusFile($old["dokumen"]);

            $this->pdo->prepare(
                "UPDATE data_pasien SET nama=?, usia=?, keluhan=?, dokumen=? WHERE id=?"
            )->execute([$data["nama"], $data["usia"], $data["keluhan"], $dokNama, $id]);
        } else {
            $this->pdo->prepare(
                "UPDATE data_pasien SET nama=?, usia=?, keluhan=? WHERE id=?"
            )->execute([$data["nama"], $data["usia"], $data["keluhan"], $id]);
        }
    }

    // override delete dari parent supaya sekalian hapus file (overriding)
    public function delete($id) {
        $row = $this->find($id);
        if ($row) {
            $this->hapusFile($row["dokumen"]);
            return parent::delete($id);
        }
        return false;
    }
}
?>
