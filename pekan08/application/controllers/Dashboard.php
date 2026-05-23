<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_login();
        $this->load->model('Post_model');
        $this->load->library(array('form_validation', 'upload'));
    }

    public function index()
    {
        $edit_id = (int) $this->input->get('edit');
        $edit_data = $edit_id > 0 ? $this->Post_model->get_by_id($edit_id) : null;

        $data = array(
            'title' => 'Dashboard',
            'posts' => $this->Post_model->get_all(),
            'edit_data' => $edit_data,
            'total_posts' => $this->Post_model->count_all(),
        );

        $this->load->view('dashboard/index', $data);
    }

    public function save()
    {
        $id = (int) $this->input->post('id');
        $existing = $id > 0 ? $this->Post_model->get_by_id($id) : null;

        $this->form_validation->set_rules('title', 'Judul', 'trim|required');
        $this->form_validation->set_rules('author', 'Penulis', 'trim|required');
        $this->form_validation->set_rules('article', 'Deskripsi', 'trim|required');

        if (!$this->form_validation->run()) {
            $this->session->set_flashdata('error', validation_errors('<div>', '</div>'));
            redirect($id > 0 ? 'dashboard?edit=' . $id : 'dashboard');
        }

        $image_name = $existing ? $existing->image : null;

        if (!empty($_FILES['image']['name'])) {
            $upload_result = $this->do_upload();

            if (!$upload_result['status']) {
                $this->session->set_flashdata('error', $upload_result['message']);
                redirect($id > 0 ? 'dashboard?edit=' . $id : 'dashboard');
            }

            if ($existing && !empty($existing->image)) {
                $old_path = FCPATH . 'application/uploads/post/' . $existing->image;
                if (is_file($old_path)) {
                    unlink($old_path);
                }
            }

            $image_name = $upload_result['file_name'];
        }

        $payload = array(
            'title' => $this->input->post('title', TRUE),
            'author' => $this->input->post('author', TRUE),
            'article' => $this->input->post('article', TRUE),
            'image' => $image_name ?: '',
        );

        if ($existing) {
            $this->Post_model->update($id, $payload);
            $this->session->set_flashdata('success', 'Data berhasil diperbarui.');
        } else {
            $this->Post_model->insert($payload);
            $this->session->set_flashdata('success', 'Data berhasil disimpan.');
        }

        redirect('dashboard');
    }

    public function delete($id)
    {
        $post = $this->Post_model->get_by_id((int) $id);

        if ($post) {
            if (!empty($post->image)) {
                $image_path = FCPATH . 'application/uploads/post/' . $post->image;
                if (is_file($image_path)) {
                    unlink($image_path);
                }
            }

            $this->Post_model->delete((int) $id);
            $this->session->set_flashdata('success', 'Data berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Data tidak ditemukan.');
        }

        redirect('dashboard');
    }

    private function do_upload()
    {
        if (!is_dir(FCPATH . 'application/uploads/post')) {
            mkdir(FCPATH . 'application/uploads/post', 0777, TRUE);
        }

        $config = array(
            'upload_path' => FCPATH . 'application/uploads/post/',
            'allowed_types' => 'jpg|jpeg|png|webp',
            'max_size' => 2048,
            'encrypt_name' => TRUE,
        );

        $this->upload->initialize($config);

        if (!$this->upload->do_upload('image')) {
            return array(
                'status' => FALSE,
                'message' => $this->upload->display_errors('<div>', '</div>'),
            );
        }

        $uploaded = $this->upload->data();

        return array(
            'status' => TRUE,
            'file_name' => $uploaded['file_name'],
        );
    }
}
