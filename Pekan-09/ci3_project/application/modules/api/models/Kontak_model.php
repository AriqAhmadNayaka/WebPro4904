<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kontak_model extends CI_Model
{
    private $table = 'kontak';

    public function ensure_table()
    {
        $this->db->query("
            CREATE TABLE IF NOT EXISTS `kontak` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `nama` VARCHAR(100) NOT NULL,
                `nomor` VARCHAR(20) NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
        ");

        $this->seed('Bintang', '081222077960');
        $this->seed('WeBandoo Admin', '08123456789');
    }

    public function all()
    {
        return $this->db
            ->order_by('id', 'ASC')
            ->get($this->table)
            ->result_array();
    }

    public function find($id)
    {
        return $this->db
            ->where('id', (int) $id)
            ->get($this->table)
            ->row_array();
    }

    public function create(array $data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update($id, array $data)
    {
        return $this->db
            ->where('id', (int) $id)
            ->update($this->table, $data);
    }

    public function delete($id)
    {
        return $this->db
            ->where('id', (int) $id)
            ->delete($this->table);
    }

    private function seed($nama, $nomor)
    {
        $exists = $this->db
            ->where('nama', $nama)
            ->where('nomor', $nomor)
            ->count_all_results($this->table);

        if ((int) $exists === 0) {
            $this->db->insert($this->table, array(
                'nama' => $nama,
                'nomor' => $nomor,
            ));
        }
    }
}
