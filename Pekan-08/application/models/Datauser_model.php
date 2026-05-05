<?php
class Datauser_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    private function upload_foto() {
        if (empty($_FILES['foto']['name'])) {
            return null;
        }

        // Buat folder jika belum ada
        $upload_path = './uploads/';
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0777, true);
        }

        $config['upload_path']   = $upload_path;
        $config['allowed_types'] = 'jpg|jpeg|png|gif|webp';
        $config['max_size']      = 5120; 
        $config['encrypt_name']  = TRUE;

        $this->load->library('upload', $config);

        if ($this->upload->do_upload('foto')) {
            $upload_data = $this->upload->data();
            return 'uploads/' . $upload_data['file_name'];
        } else {
            log_message('error', 'Upload Error: ' . $this->upload->display_errors());
            return null;
        }
    }

    public function create($nama, $email) {
        $foto = $this->upload_foto();

        $data = [
            'user_id'    => $this->session->userdata('user_id'),
            'nama'       => $nama,
            'email'      => $email,
            'foto'       => $foto,
            'created_at' => date('Y-m-d H:i:s')
        ];

        return $this->db->insert('data_user', $data);
    }

    public function get_all() {
        $this->db->where('user_id', $this->session->userdata('user_id'));
        $this->db->order_by('created_at', 'DESC');
        return $this->db->get('data_user')->result_array();
    }

    public function get_by_id($id) {
        $this->db->where('id', $id);
        $this->db->where('user_id', $this->session->userdata('user_id'));
        return $this->db->get('data_user')->row_array();
    }

    public function update($id, $nama, $email) {
        $foto_path = $this->upload_foto();
        $data = ['nama' => $nama, 'email' => $email];

        if ($foto_path) {
            // Hapus foto lama
            $old = $this->get_by_id($id);
            if ($old && !empty($old['foto']) && file_exists('./' . $old['foto'])) {
                unlink('./' . $old['foto']);
            }
            $data['foto'] = $foto_path;
        }

        $this->db->where('id', $id);
        $this->db->where('user_id', $this->session->userdata('user_id'));
        return $this->db->update('data_user', $data);
    }

    public function delete($id) {
        $data = $this->get_by_id($id);
        if ($data && !empty($data['foto']) && file_exists('./' . $data['foto'])) {
            unlink('./' . $data['foto']);
        }

        $this->db->where('id', $id);
        $this->db->where('user_id', $this->session->userdata('user_id'));
        return $this->db->delete('data_user');
    }
}