<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class REST_Controller extends MY_Controller {

    protected $request_method;
    protected $request_data;

    public function __construct()
    {
        parent::__construct();

        // Simpan method HTTP yang dipakai client: get, post, put, atau delete.
        $this->request_method = strtolower($this->input->method(TRUE));
        $this->request_data = $this->_parse_request_data();
    }

    public function _remap($method, $params = array())
    {
        // Mengubah method controller menjadi pola REST, contoh posts + GET = posts_get.
        $rest_method = $method.'_'.$this->request_method;

        if (method_exists($this, $rest_method))
        {
            return call_user_func_array(array($this, $rest_method), $params);
        }

        return $this->response(array(
            'status' => FALSE,
            'message' => 'Method not allowed'
        ), 405);
    }

    protected function response($data, $status = 200)
    {
        // Semua response API dikirim sebagai JSON dengan HTTP status yang sesuai.
        return $this->output
            ->set_status_header($status)
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }

    protected function input_data($key = NULL)
    {
        if ($key === NULL)
        {
            return $this->request_data;
        }

        return isset($this->request_data[$key]) ? $this->request_data[$key] : NULL;
    }

    private function _parse_request_data()
    {
        // Ambil body JSON dari Postman atau client REST lain.
        $json = json_decode($this->input->raw_input_stream, TRUE);

        if (json_last_error() === JSON_ERROR_NONE && is_array($json))
        {
            return $json;
        }

        if (!empty($_POST))
        {
            // Fallback untuk form-data atau x-www-form-urlencoded.
            return $this->input->post(NULL, TRUE);
        }

        // Fallback untuk PUT/DELETE yang dikirim sebagai input stream biasa.
        return $this->input->input_stream(NULL, TRUE);
    }
}
