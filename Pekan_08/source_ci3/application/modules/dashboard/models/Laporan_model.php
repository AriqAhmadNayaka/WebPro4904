<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan_model extends CI_Model
{
    private $table = 'laporan';

    public function get_all()
    {
        return $this->db->order_by('id', 'DESC')->get($this->table)->result();
    }

    public function find($id)
    {
        return $this->db->get_where($this->table, array('id' => (int) $id))->row();
    }

    public function insert($data)
    {
        return $this->db->insert($this->table, $data);
    }

    public function update($id, $data)
    {
        return $this->db->where('id', (int) $id)->update($this->table, $data);
    }

    public function delete($id)
    {
        return $this->db->delete($this->table, array('id' => (int) $id));
    }

    public function get_summary()
    {
        $sql = "SELECT
                    COALESCE(SUM(CASE WHEN jenis = 'Pemasukan' THEN jumlah ELSE 0 END), 0) AS total_masuk,
                    COALESCE(SUM(CASE WHEN jenis = 'Pengeluaran' THEN jumlah ELSE 0 END), 0) AS total_keluar
                FROM {$this->table}";

        $row = $this->db->query($sql)->row_array();

        return array(
            'total_masuk' => isset($row['total_masuk']) ? (int) $row['total_masuk'] : 0,
            'total_keluar' => isset($row['total_keluar']) ? (int) $row['total_keluar'] : 0,
        );
    }
}
