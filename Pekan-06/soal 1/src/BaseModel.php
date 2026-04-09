<?php

/**
 * Class BaseModel
 * Menyediakan fungsionalitas CRUD dasar untuk semua model di aplikasi.
 */
abstract class BaseModel {
    protected $db;
    protected $table;
    protected $primaryKey = 'id';

    /**
     * Inisialisasi koneksi database saat objek dibuat.
     */
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Menentukan tipe data untuk binding parameter MySQLi (integer, double, atau string).
     */
    protected function getBindType($value) {
        if (is_int($value)) return 'i';
        if (is_float($value) || is_double($value)) return 'd';
        return 's';
    }

    /**
     * Mengambil semua data dari tabel, mendukung filter conditions.
     * @param array $conditions Array asosiatif ['kolom' => 'nilai']
     */
    public function all($conditions = []) {
        $sql = "SELECT * FROM {$this->table}";
        $types = '';
        $values = [];

        if (!empty($conditions)) {
            $clauses = [];
            foreach ($conditions as $key => $value) {
                $cleanKey = trim($key);
                if (strpos($cleanKey, ' ') !== false) {
                    // Mendukung operator custom (misal: 'id >')
                    $clauses[] = "$cleanKey ?";
                } else {
                    $clauses[] = "$cleanKey = ?";
                }
                $types .= $this->getBindType($value);
                $values[] = $value;
            }
            $sql .= " WHERE " . implode(' AND ', $clauses);
        }

        $stmt = $this->db->prepare($sql);
        if (!empty($values)) {
            $stmt->bind_param($types, ...$values);
        }
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Mencari satu record berdasarkan ID (Primary Key).
     */
    public function find($id) {
        $data = $this->all([$this->primaryKey => $id]);
        return (!empty($data)) ? $data[0] : null;
    }

    /**
     * Menyisipkan record baru ke tabel secara dinamis.
     * @param array $data Array asosiatif ['kolom' => 'nilai']
     */
    public function create($data) {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        $values = array_values($data);

        $types = '';
        foreach ($values as $value) {
            $types .= $this->getBindType($value);
        }

        $sql = "INSERT INTO {$this->table} ($columns) VALUES ($placeholders)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param($types, ...$values);
        return $stmt->execute();
    }

    /**
     * Memperbarui record berdasarkan ID secara dinamis.
     * @param mixed $id Nilai primary key
     * @param array $data Data baru yang akan diupdate
     */
    public function update($id, $data) {
        $cols = [];
        $types = '';
        $values = [];

        foreach ($data as $key => $value) {
            $cols[] = "$key = ?";
            $types .= $this->getBindType($value);
            $values[] = $value;
        }

        $types .= $this->getBindType($id);
        $values[] = $id;

        $setClause = implode(', ', $cols);
        $sql = "UPDATE {$this->table} SET $setClause WHERE {$this->primaryKey} = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param($types, ...$values);
        return $stmt->execute();
    }

    /**
     * Menghapus record berdasarkan ID.
     */
    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param($this->getBindType($id), $id);
        return $stmt->execute();
    }
}
