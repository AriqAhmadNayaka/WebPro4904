<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Produk_model extends CI_Model
{
    private $table = 'produk';
    private $schema_ready = FALSE;

    public function __construct()
    {
        parent::__construct();
        $this->ensure_schema();
    }

    public function get_all($filters = array())
    {
        $keyword = isset($filters['keyword']) ? trim((string) $filters['keyword']) : '';
        $status = isset($filters['status']) ? trim((string) $filters['status']) : '';

        $this->db->from($this->table);

        if ($keyword !== '') {
            $this->db
                ->group_start()
                ->like('kode_produk', $keyword)
                ->or_like('nama_produk', $keyword)
                ->or_like('kategori', $keyword)
                ->group_end();
        }

        if ($status !== '') {
            $this->db->where('status', $status);
        }

        return $this->db
            ->order_by('updated_at', 'DESC')
            ->order_by('id', 'DESC')
            ->get()
            ->result();
    }

    public function find($id)
    {
        return $this->db->get_where($this->table, array('id' => (int) $id))->row();
    }

    public function insert($data)
    {
        $now = date('Y-m-d H:i:s');
        $data['created_at'] = $now;
        $data['updated_at'] = $now;

        return $this->db->insert($this->table, $data);
    }

    public function update($id, $data)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');

        return $this->db
            ->where('id', (int) $id)
            ->update($this->table, $data);
    }

    public function delete($id)
    {
        return $this->db->delete($this->table, array('id' => (int) $id));
    }

    public function kode_exists($kode_produk, $ignore_id = 0)
    {
        $this->db->from($this->table)->where('kode_produk', $kode_produk);

        if ((int) $ignore_id > 0) {
            $this->db->where('id !=', (int) $ignore_id);
        }

        return $this->db->count_all_results() > 0;
    }

    public function get_categories()
    {
        $rows = $this->db
            ->select('kategori')
            ->distinct()
            ->order_by('kategori', 'ASC')
            ->get($this->table)
            ->result();

        $categories = array();

        foreach ($rows as $row) {
            $categories[] = $row->kategori;
        }

        return $categories;
    }

    public function get_summary()
    {
        $row = $this->db->query(
            "SELECT
                COUNT(*) AS total_produk,
                COALESCE(SUM(stok), 0) AS total_stok,
                COALESCE(SUM(harga * stok), 0) AS total_nilai,
                COALESCE(SUM(CASE WHEN stok <= 5 THEN 1 ELSE 0 END), 0) AS stok_menipis
            FROM {$this->table}"
        )->row_array();

        return array(
            'total_produk' => isset($row['total_produk']) ? (int) $row['total_produk'] : 0,
            'total_stok' => isset($row['total_stok']) ? (int) $row['total_stok'] : 0,
            'total_nilai' => isset($row['total_nilai']) ? (int) $row['total_nilai'] : 0,
            'stok_menipis' => isset($row['stok_menipis']) ? (int) $row['stok_menipis'] : 0,
        );
    }

    private function ensure_schema()
    {
        if ($this->schema_ready) {
            return;
        }

        $this->db->query(
            "CREATE TABLE IF NOT EXISTS {$this->table} (
                id INT UNSIGNED NOT NULL AUTO_INCREMENT,
                kode_produk VARCHAR(30) NOT NULL,
                nama_produk VARCHAR(120) NOT NULL,
                kategori VARCHAR(80) NOT NULL,
                harga INT UNSIGNED NOT NULL DEFAULT 0,
                stok INT UNSIGNED NOT NULL DEFAULT 0,
                status ENUM('Tersedia', 'Pre Order', 'Habis') NOT NULL DEFAULT 'Tersedia',
                deskripsi TEXT NULL,
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (id),
                UNIQUE KEY uniq_kode_produk (kode_produk)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci"
        );

        if ((int) $this->db->count_all($this->table) === 0) {
            $this->seed_default_data();
        }

        $this->schema_ready = TRUE;
    }

    private function seed_default_data()
    {
        $now = date('Y-m-d H:i:s');

        $this->db->insert_batch($this->table, array(
            array(
                'kode_produk' => 'PRD-001',
                'nama_produk' => 'Notebook Planner',
                'kategori' => 'ATK',
                'harga' => 45000,
                'stok' => 24,
                'status' => 'Tersedia',
                'deskripsi' => 'Buku catatan hardcover untuk kebutuhan sekolah dan kerja.',
                'created_at' => $now,
                'updated_at' => $now,
            ),
            array(
                'kode_produk' => 'PRD-002',
                'nama_produk' => 'Lampu Meja Minimalis',
                'kategori' => 'Elektronik',
                'harga' => 185000,
                'stok' => 6,
                'status' => 'Pre Order',
                'deskripsi' => 'Lampu meja dengan tiga mode cahaya untuk ruang belajar.',
                'created_at' => $now,
                'updated_at' => $now,
            ),
            array(
                'kode_produk' => 'PRD-003',
                'nama_produk' => 'Kotak Bekal Bento',
                'kategori' => 'Rumah Tangga',
                'harga' => 68000,
                'stok' => 4,
                'status' => 'Habis',
                'deskripsi' => 'Kotak bekal food grade dengan sekat praktis dan rapat.',
                'created_at' => $now,
                'updated_at' => $now,
            ),
        ));
    }
}
