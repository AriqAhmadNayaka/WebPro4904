<?php
// Parent class Model - jadi induk untuk class CRUD
require_once __DIR__ . "/Database.php";

class Model {
    protected $pdo;
    protected $table;

    public function __construct() {
        $db = new Database();
        $this->pdo = $db->getPdo();
    }

    public function all() {
        $stmt = $this->pdo->query("SELECT * FROM $this->table ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    public function find($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM $this->table WHERE id = ?");
        $stmt->execute([(int)$id]);
        return $stmt->fetch();
    }

    public function delete($id) {
        return $this->pdo->prepare("DELETE FROM $this->table WHERE id = ?")
                         ->execute([(int)$id]);
    }
}
?>