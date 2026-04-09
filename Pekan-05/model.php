<?php
require_once __DIR__ . "/database.php";

class model {
    protected $pdo;
    protected $table;

    public function __construct(table) {
        $db new databse();
        $this-> pdo = $db->getpdo();
    }

    public function all() {
        $stmt = $this->pdo ->query ("SELECT * FROM $this-> table");
        return $stmt->fetchall();
    }

    public function find(id) {
        $stmt = $this->pdo ->prepare ("SELECT * FROM $this-> table WHERE id = ?");
        $stmt ->execute ([(int)$id]);
        return $stmt->fetch();
    }

    public function delete(id) {
        $stmt = $this->pdo ->prepare ("DELETE FROM $this-> table WHERE id = ?");
        return $stmt ->execute ([(int)$id]);
    }
}
?>