<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class REST_Controller extends MX_Controller
{
    const HTTP_OK = 200;
    const HTTP_CREATED = 201;
    const HTTP_BAD_REQUEST = 400;
    const HTTP_NOT_FOUND = 404;
    const HTTP_METHOD_NOT_ALLOWED = 405;

    protected $method;

    public function __construct()
    {
        parent::__construct();
        $this->method = strtolower($this->input->method(TRUE));
    }

    public function _remap($object_called, $arguments = array())
    {
        $controller_method = $object_called . '_' . $this->method;

        if (method_exists($this, $controller_method)) {
            return call_user_func_array(array($this, $controller_method), $arguments);
        }

        if ($object_called === 'index' && method_exists($this, $this->method)) {
            return call_user_func_array(array($this, $this->method), $arguments);
        }

        return $this->response(array(
            'status' => FALSE,
            'message' => 'Method tidak diizinkan',
        ), self::HTTP_METHOD_NOT_ALLOWED);
    }

    protected function response($data, $http_code = self::HTTP_OK)
    {
        return $this->output
            ->set_status_header($http_code)
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    protected function request()
    {
        $input = array();

        if (in_array($this->method, array('post', 'put', 'patch', 'delete'), TRUE)) {
            $json = json_decode($this->input->raw_input_stream, TRUE);

            if (is_array($json)) {
                $input = $json;
            } elseif ($this->method === 'post') {
                $input = $this->input->post(NULL, TRUE);
            } else {
                parse_str($this->input->raw_input_stream, $input);
            }
        }

        return is_array($input) ? $input : array();
    }
}
