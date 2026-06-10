<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->require_login();
        $this->load->model('User_model');
        $this->load->model('Category_model');
        $this->load->model('Catalog_item_model');
    }

    public function index()
    {
        $edit_category = NULL;
        $error = $this->session->flashdata('error');
        $success = $this->session->flashdata('success');

        if ($this->request_method() === 'POST') {
            $action = $this->input->post('action', TRUE);

            if ($action === 'save_category') {
                $result = $this->save_category();
                if ($result['status']) {
                    $this->session->set_flashdata('success', $result['message']);
                    redirect('dashboard');
                }
                $error = $result['message'];
                $edit_category = $result['category'];
            }
        }

        $edit_id = (int) $this->input->get('edit', TRUE);
        if ($edit_id > 0 && !$edit_category) {
            $edit_category = $this->Category_model->get_by_id($edit_id);
        }

        $data = array(
            'user' => $this->current_user(),
            'users' => $this->User_model->get_all(),
            'categories' => $this->Category_model->get_all(),
            'items_count' => $this->Catalog_item_model->count_all(),
            'error' => $error,
            'success' => $success,
            'edit_category' => $edit_category
        );

        $this->load->view('dashboard', $data);
    }

    public function delete_category($id = NULL)
    {
        $category = $this->Category_model->get_by_id($id);
        if (!$category) {
            show_404();
        }

        $this->Category_model->delete($id);
        $this->session->set_flashdata('success', 'Kategori berhasil dihapus.');
        redirect('dashboard');
    }

    public function catalog($category_id = NULL)
    {
        $category = $this->Category_model->get_by_id($category_id);
        if (!$category) {
            show_404();
        }

        $edit_item = NULL;
        $error = $this->session->flashdata('error');
        $success = $this->session->flashdata('success');

        if ($this->request_method() === 'POST') {
            $action = $this->input->post('action', TRUE);

            if ($action === 'save_item') {
                $result = $this->save_item($category);
                if ($result['status']) {
                    $this->session->set_flashdata('success', $result['message']);
                    redirect('dashboard/kategori/' . $category['id']);
                }
                $error = $result['message'];
                $edit_item = $result['item'];
            }
        }

        $edit_id = (int) $this->input->get('edit', TRUE);
        if ($edit_id > 0 && !$edit_item) {
            $edit_item = $this->Catalog_item_model->get_by_id($edit_id);
            if ($edit_item && (int) $edit_item['category_id'] !== (int) $category['id']) {
                $edit_item = NULL;
            }
        }

        $data = array(
            'user' => $this->current_user(),
            'category' => $category,
            'items' => $this->Catalog_item_model->get_by_category($category['id']),
            'error' => $error,
            'success' => $success,
            'edit_item' => $edit_item
        );

        $this->load->view('catalog', $data);
    }

    public function delete_item($id = NULL)
    {
        $item = $this->Catalog_item_model->get_by_id($id);
        if (!$item) {
            show_404();
        }

        $category_id = (int) $item['category_id'];
        $this->delete_item_photo_if_exists($item['example_photo']);
        $this->Catalog_item_model->delete($id);
        $this->session->set_flashdata('success', 'Barang katalog berhasil dihapus.');
        redirect('dashboard/kategori/' . $category_id);
    }

    private function save_category()
    {
        $id = (int) $this->input->post('category_id', TRUE);
        $name = trim($this->input->post('category_name', TRUE));
        $description = trim($this->input->post('description', TRUE));

        $category = array(
            'id' => $id,
            'category_name' => $name,
            'description' => $description
        );

        if ($name === '') {
            return array('status' => FALSE, 'message' => 'Nama kategori wajib diisi.', 'category' => $category);
        }

        $existing = $this->Category_model->get_by_name($name);
        if ($existing && (int) $existing['id'] !== $id) {
            return array('status' => FALSE, 'message' => 'Nama kategori sudah digunakan.', 'category' => $category);
        }

        $payload = array(
            'category_name' => $name,
            'category_slug' => $this->slugify($name),
            'description' => $description !== '' ? $description : NULL
        );

        if ($id > 0) {
            $this->Category_model->update($id, $payload);
            return array('status' => TRUE, 'message' => 'Kategori berhasil diperbarui.', 'category' => NULL);
        }

        $this->Category_model->insert($payload);
        return array('status' => TRUE, 'message' => 'Kategori berhasil ditambahkan.', 'category' => NULL);
    }

    private function save_item($category)
    {
        $id = (int) $this->input->post('item_id', TRUE);
        $item_name = trim($this->input->post('item_name', TRUE));
        $item_type = trim($this->input->post('item_type', TRUE));
        $item_condition = trim($this->input->post('item_condition', TRUE));
        $notes = trim($this->input->post('notes', TRUE));

        $item = array(
            'id' => $id,
            'category_id' => $category['id'],
            'item_name' => $item_name,
            'item_type' => $item_type,
            'item_condition' => $item_condition,
            'notes' => $notes
        );

        if ($item_name === '' || $item_type === '') {
            return array('status' => FALSE, 'message' => 'Nama barang dan jenis barang wajib diisi.', 'item' => $item);
        }

        $allowed_conditions = array('Layak Daur Ulang', 'Perlu Dibersihkan', 'Residu');
        if (!in_array($item_condition, $allowed_conditions, TRUE)) {
            $item_condition = 'Layak Daur Ulang';
        }

        $current_item = $id > 0 ? $this->Catalog_item_model->get_by_id($id) : NULL;
        if ($id > 0 && (!$current_item || (int) $current_item['category_id'] !== (int) $category['id'])) {
            return array('status' => FALSE, 'message' => 'Barang katalog tidak ditemukan.', 'item' => $item);
        }

        $upload = $this->handle_item_photo_upload('example_photo');
        if ($upload['error'] !== '') {
            return array('status' => FALSE, 'message' => $upload['error'], 'item' => $item);
        }

        $payload = array(
            'category_id' => $category['id'],
            'item_name' => $item_name,
            'item_type' => $item_type,
            'item_condition' => $item_condition,
            'notes' => $notes !== '' ? $notes : NULL
        );

        if ($upload['file_name'] !== '') {
            $payload['example_photo'] = $upload['file_name'];
        } elseif ($current_item) {
            $payload['example_photo'] = $current_item['example_photo'];
        }

        if ($current_item) {
            if ($upload['file_name'] !== '' && !empty($current_item['example_photo'])) {
                $this->delete_item_photo_if_exists($current_item['example_photo']);
            }

            $this->Catalog_item_model->update($current_item['id'], $payload);
            return array('status' => TRUE, 'message' => 'Barang katalog berhasil diperbarui.', 'item' => NULL);
        }

        $this->Catalog_item_model->insert($payload);
        return array('status' => TRUE, 'message' => 'Barang katalog berhasil ditambahkan.', 'item' => NULL);
    }

    private function handle_item_photo_upload($field_name)
    {
        if (empty($_FILES[$field_name]['name'])) {
            return array('error' => '', 'file_name' => '');
        }

        $config['upload_path'] = FCPATH . 'uploads/items/';
        $config['allowed_types'] = 'jpg|jpeg|png|webp';
        $config['max_size'] = 4096;
        $config['encrypt_name'] = TRUE;

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload($field_name)) {
            return array('error' => strip_tags($this->upload->display_errors('', '')), 'file_name' => '');
        }

        $data = $this->upload->data();
        return array('error' => '', 'file_name' => $data['file_name']);
    }

    private function delete_item_photo_if_exists($photo)
    {
        if (!$photo) {
            return;
        }

        $path = FCPATH . 'uploads/items/' . $photo;
        if (is_file($path)) {
            @unlink($path);
        }
    }

    private function slugify($text)
    {
        $text = strtolower(trim($text));
        $text = preg_replace('/[^a-z0-9]+/i', '-', $text);
        return trim($text, '-') ?: 'kategori';
    }
}
