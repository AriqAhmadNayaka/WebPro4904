<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'libraries/REST_Controller.php';

class Kontak extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('api/Kontak_model', 'kontak');
        $this->kontak->ensure_table();
    }

    public function index_get()
    {
        $id = $this->input->get('id', TRUE);

        if ($id !== NULL && $id !== '') {
            $kontak = $this->kontak->find($id);

            if (!$kontak) {
                return $this->response(array(
                    'status' => FALSE,
                    'message' => 'Kontak tidak ditemukan',
                ), self::HTTP_NOT_FOUND);
            }

            return $this->response(array(
                'status' => TRUE,
                'data' => $kontak,
            ));
        }

        return $this->response(array(
            'status' => TRUE,
            'data' => $this->kontak->all(),
        ));
    }

    public function index_post()
    {
        $data = $this->request();
        $payload = $this->validated_payload($data);

        if ($payload === FALSE) {
            return $this->response(array(
                'status' => FALSE,
                'message' => 'Nama dan nomor wajib diisi',
            ), self::HTTP_BAD_REQUEST);
        }

        $id = $this->kontak->create($payload);

        return $this->response(array(
            'status' => TRUE,
            'message' => 'Kontak berhasil ditambahkan',
            'data' => $this->kontak->find($id),
        ), self::HTTP_CREATED);
    }

    public function index_put()
    {
        $data = $this->request();
        $id = isset($data['id']) ? $data['id'] : $this->input->get('id', TRUE);
        $payload = $this->validated_payload($data);

        if ($id === NULL || $id === '' || $payload === FALSE) {
            return $this->response(array(
                'status' => FALSE,
                'message' => 'ID, nama, dan nomor wajib diisi',
            ), self::HTTP_BAD_REQUEST);
        }

        if (!$this->kontak->find($id)) {
            return $this->response(array(
                'status' => FALSE,
                'message' => 'Kontak tidak ditemukan',
            ), self::HTTP_NOT_FOUND);
        }

        $this->kontak->update($id, $payload);

        return $this->response(array(
            'status' => TRUE,
            'message' => 'Kontak berhasil diperbarui',
            'data' => $this->kontak->find($id),
        ));
    }

    public function index_delete()
    {
        $data = $this->request();
        $id = isset($data['id']) ? $data['id'] : $this->input->get('id', TRUE);

        if ($id === NULL || $id === '') {
            return $this->response(array(
                'status' => FALSE,
                'message' => 'ID wajib diisi',
            ), self::HTTP_BAD_REQUEST);
        }

        if (!$this->kontak->find($id)) {
            return $this->response(array(
                'status' => FALSE,
                'message' => 'Kontak tidak ditemukan',
            ), self::HTTP_NOT_FOUND);
        }

        $this->kontak->delete($id);

        return $this->response(array(
            'status' => TRUE,
            'message' => 'Kontak berhasil dihapus',
        ));
    }

    private function validated_payload($data)
    {
        $nama = isset($data['nama']) ? trim($data['nama']) : '';
        $nomor = isset($data['nomor']) ? trim($data['nomor']) : '';

        if ($nama === '' || $nomor === '') {
            return FALSE;
        }

        return array(
            'nama' => $nama,
            'nomor' => $nomor,
        );
    }
}
