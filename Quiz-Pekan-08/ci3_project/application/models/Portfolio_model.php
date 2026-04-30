<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Portfolio_model extends CI_Model {

    // Nama tabel profil utama.
    private $profile_table = 'profiles';

    // Nama tabel daftar project portofolio.
    private $project_table = 'projects';

    // Mengambil satu data profil utama.
    public function get_profile()
    {
        $profile = $this->db->get($this->profile_table)->row();

        if ($profile) {
            return $profile;
        }

        // Jika profil belum ada, buat data default agar halaman tetap bisa tampil.
        $default_profile = array(
            'full_name' => 'Nama Anda',
            'profession' => 'Web Developer',
            'about' => 'Tuliskan ringkasan singkat tentang diri Anda, pengalaman, dan tujuan karier di sini.',
            'email' => 'email@contoh.com',
            'phone' => '08xxxxxxxxxx',
            'address' => 'Kota Anda, Indonesia',
            'skills' => 'HTML, CSS, JavaScript, PHP, CodeIgniter',
            'profile_photo' => '',
        );

        $this->db->insert($this->profile_table, $default_profile);
        return $this->db->get_where($this->profile_table, array('id' => $this->db->insert_id()))->row();
    }

    // Menyimpan profil: update jika sudah ada, insert jika belum ada.
    public function save_profile($data)
    {
        $profile = $this->db->get($this->profile_table)->row();

        if ($profile) {
            $this->db->where('id', $profile->id);
            return $this->db->update($this->profile_table, $data);
        }

        return $this->db->insert($this->profile_table, $data);
    }

    // Mengambil semua project dan mengurutkannya dari data terbaru.
    public function get_projects()
    {
        return $this->db->order_by('id', 'DESC')->get($this->project_table)->result();
    }

    // Mengambil satu project berdasarkan id.
    public function get_project($id)
    {
        return $this->db->get_where($this->project_table, array('id' => $id))->row();
    }

    // Menambahkan data project baru.
    public function insert_project($data)
    {
        return $this->db->insert($this->project_table, $data);
    }

    // Memperbarui data project berdasarkan id.
    public function update_project($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update($this->project_table, $data);
    }

    // Menghapus data project berdasarkan id.
    public function delete_project($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete($this->project_table);
    }
}
