<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MX_Controller
{
    private $status_options = array('Tersedia', 'Pre Order', 'Habis');

    public function __construct()
    {
        parent::__construct();
        $this->load->model('dashboard/Produk_model', 'produk');
    }

    public function index()
    {
        $edit_id = (int) $this->input->get('edit');
        $filters = array(
            'keyword' => trim((string) $this->input->get('q', TRUE)),
            'status' => trim((string) $this->input->get('status', TRUE)),
        );

        $data = array(
            'message' => $this->session->flashdata('message'),
            'summary' => $this->produk->get_summary(),
            'rows' => $this->produk->get_all($filters),
            'edit' => $edit_id > 0 ? $this->produk->find($edit_id) : NULL,
            'filters' => $filters,
            'status_options' => $this->status_options,
            'category_options' => $this->produk->get_categories(),
        );

        $this->load->view('dashboard/index', $data);
    }

    public function save()
    {
        $id = (int) $this->input->post('id');
        $mode = $this->input->post('mode') === 'update' ? 'update' : 'create';

        $payload = array(
            'kode_produk' => strtoupper(trim((string) $this->input->post('kode_produk', TRUE))),
            'nama_produk' => trim((string) $this->input->post('nama_produk', TRUE)),
            'kategori' => trim((string) $this->input->post('kategori', TRUE)),
            'harga' => (int) $this->input->post('harga', TRUE),
            'stok' => (int) $this->input->post('stok', TRUE),
            'status' => trim((string) $this->input->post('status', TRUE)),
            'deskripsi' => trim((string) $this->input->post('deskripsi', TRUE)),
        );

        if (
            $payload['kode_produk'] === '' ||
            $payload['nama_produk'] === '' ||
            $payload['kategori'] === '' ||
            $payload['status'] === '' ||
            $payload['harga'] < 0 ||
            $payload['stok'] < 0
        ) {
            $this->set_flash('error', 'Kode, nama, kategori, status wajib diisi. Harga dan stok tidak boleh negatif.');
            $this->redirect_to_form($mode, $id);
        }

        if (!in_array($payload['status'], $this->status_options, TRUE)) {
            $this->set_flash('error', 'Status produk tidak valid.');
            $this->redirect_to_form($mode, $id);
        }

        if ($this->produk->kode_exists($payload['kode_produk'], $id)) {
            $this->set_flash('error', 'Kode produk sudah dipakai. Gunakan kode lain yang unik.');
            $this->redirect_to_form($mode, $id);
        }

        if ($mode === 'update' && $id > 0) {
            $current = $this->produk->find($id);

            if (!$current) {
                $this->set_flash('error', 'Produk yang ingin diedit tidak ditemukan.');
                redirect('dashboard');
            }

            $this->produk->update($id, $payload);
            $this->set_flash('success', 'Produk berhasil diperbarui.');
            redirect('dashboard');
        }

        $this->produk->insert($payload);
        $this->set_flash('success', 'Produk berhasil ditambahkan.');
        redirect('dashboard');
    }

    public function delete($id = 0)
    {
        $id = (int) $id;
        $row = $this->produk->find($id);

        if ($row) {
            $this->produk->delete($id);
            $this->set_flash('success', 'Produk berhasil dihapus.');
        } else {
            $this->set_flash('error', 'Produk tidak ditemukan.');
        }

        redirect('dashboard');
    }

    private function redirect_to_form($mode, $id)
    {
        redirect($mode === 'update' && $id > 0 ? site_url('dashboard') . '?edit=' . $id : 'dashboard');
    }

    private function set_flash($type, $text)
    {
        $this->session->set_flashdata('message', array(
            'type' => $type,
            'text' => $text,
        ));
    }
}
