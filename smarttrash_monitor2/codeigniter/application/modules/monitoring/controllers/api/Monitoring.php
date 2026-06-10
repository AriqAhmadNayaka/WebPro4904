<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Monitoring extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('monitoring/Monitoring_model');
    }

    public function index($id = NULL)
    {
        $method = $this->request_method();

        if ($method === 'GET') {
            return $this->handle_get($id);
        }

        if ($method === 'POST') {
            return $this->handle_post();
        }

        if ($method === 'PUT') {
            return $this->handle_put($id);
        }

        if ($method === 'DELETE') {
            return $this->handle_delete($id);
        }

        return $this->json_response(array(
            'status' => FALSE,
            'message' => 'Method tidak didukung'
        ), 405);
    }

    private function handle_get($id = NULL)
    {
        if ($id !== NULL) {
            $bin = $this->Monitoring_model->get_by_id($id);

            if (!$bin) {
                return $this->json_response(array(
                    'status' => FALSE,
                    'message' => 'Data monitoring tidak ditemukan'
                ), 404);
            }

            return $this->json_response(array(
                'status' => TRUE,
                'data' => $bin
            ));
        }

        return $this->json_response(array(
            'status' => TRUE,
            'data' => $this->Monitoring_model->get_all()
        ));
    }

    private function handle_post()
    {
        $payload = $this->sanitize_payload($this->request_data());
        $validation = $this->validate_payload($payload);

        if ($validation !== TRUE) {
            return $this->json_response(array(
                'status' => FALSE,
                'message' => $validation
            ), 422);
        }

        if ($this->Monitoring_model->get_by_code($payload['bin_code'])) {
            return $this->json_response(array(
                'status' => FALSE,
                'message' => 'Kode bin sudah digunakan'
            ), 409);
        }

        $insert_id = $this->Monitoring_model->insert($payload);

        return $this->json_response(array(
            'status' => TRUE,
            'message' => 'Data monitoring berhasil ditambahkan',
            'data' => $this->Monitoring_model->get_by_id($insert_id)
        ), 201);
    }

    private function handle_put($id = NULL)
    {
        $bin = $id === NULL ? NULL : $this->Monitoring_model->get_by_id($id);

        if (!$bin) {
            return $this->json_response(array(
                'status' => FALSE,
                'message' => 'Data monitoring tidak ditemukan'
            ), 404);
        }

        $payload = $this->sanitize_payload($this->request_data());
        $validation = $this->validate_payload($payload, (int) $id);

        if ($validation !== TRUE) {
            return $this->json_response(array(
                'status' => FALSE,
                'message' => $validation
            ), 422);
        }

        $this->Monitoring_model->update($id, $payload);

        return $this->json_response(array(
            'status' => TRUE,
            'message' => 'Data monitoring berhasil diperbarui',
            'data' => $this->Monitoring_model->get_by_id($id)
        ));
    }

    private function handle_delete($id = NULL)
    {
        $bin = $id === NULL ? NULL : $this->Monitoring_model->get_by_id($id);

        if (!$bin) {
            return $this->json_response(array(
                'status' => FALSE,
                'message' => 'Data monitoring tidak ditemukan'
            ), 404);
        }

        $this->Monitoring_model->delete($id);

        return $this->json_response(array(
            'status' => TRUE,
            'message' => 'Data monitoring berhasil dihapus'
        ));
    }

    private function sanitize_payload($payload)
    {
        $last_collection = trim(isset($payload['last_collection']) ? $payload['last_collection'] : '');

        if ($last_collection !== '') {
            $last_collection = str_replace('T', ' ', $last_collection);
            if (preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}$/', $last_collection)) {
                $last_collection .= ':00';
            }
        } else {
            $last_collection = NULL;
        }

        return array(
            'bin_code' => strtoupper(trim(isset($payload['bin_code']) ? $payload['bin_code'] : '')),
            'location_name' => trim(isset($payload['location_name']) ? $payload['location_name'] : ''),
            'waste_level' => isset($payload['waste_level']) ? (int) $payload['waste_level'] : -1,
            'status' => strtoupper(trim(isset($payload['status']) ? $payload['status'] : '')),
            'last_collection' => $last_collection,
            'notes' => trim(isset($payload['notes']) ? $payload['notes'] : '')
        );
    }

    private function validate_payload($payload, $id = NULL)
    {
        $allowed_status = array('AMAN', 'WASPADA', 'PENUH', 'DIANGKUT');

        if ($payload['bin_code'] === '') {
            return 'Kode bin wajib diisi';
        }

        if ($payload['location_name'] === '') {
            return 'Lokasi wajib diisi';
        }

        if ($payload['waste_level'] < 0 || $payload['waste_level'] > 100) {
            return 'Waste level harus di antara 0 sampai 100';
        }

        if (!in_array($payload['status'], $allowed_status, TRUE)) {
            return 'Status harus salah satu dari AMAN, WASPADA, PENUH, atau DIANGKUT';
        }

        $existing = $this->Monitoring_model->get_by_code($payload['bin_code']);
        if ($existing && (int) $existing['id'] !== (int) $id) {
            return 'Kode bin sudah digunakan';
        }

        if ($payload['last_collection'] !== NULL) {
            $valid_datetime = preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $payload['last_collection']);
            if (!$valid_datetime) {
                return 'Format tanggal pengangkutan harus YYYY-MM-DD HH:MM:SS';
            }
        }

        return TRUE;
    }
}
