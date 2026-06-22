<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Router extends CI_Router {

    // Menyimpan nama module aktif, misalnya "posts" dari URL /posts.
    public $module;

    protected function _set_default_controller()
    {
        if (empty($this->default_controller))
        {
            show_error('Unable to determine what should be displayed. A default route has not been specified in the routing file.');
        }

        if (sscanf($this->default_controller, '%[^/]/%s', $class, $method) !== 2)
        {
            $method = 'index';
        }

        $module_path = APPPATH.'modules/'.$class.'/controllers/'.ucfirst($class).'.php';

        // Jika default_controller ada di folder modules, arahkan CI ke controller module.
        if (file_exists($module_path))
        {
            $this->module = $class;
            $this->directory = '../modules/'.$class.'/controllers/';
            $this->set_class($class);
            $this->set_method($method);
            $this->uri->rsegments = array(1 => $class, 2 => $method);

            log_message('debug', 'No URI present. Default HMVC controller set.');
            return;
        }

        parent::_set_default_controller();
    }

    protected function _validate_request($segments)
    {
        if (empty($segments))
        {
            return parent::_validate_request($segments);
        }

        $module = $segments[0];
        $module_path = APPPATH.'modules/'.$module.'/controllers/';

        // Cek apakah segment pertama adalah nama module, misalnya /posts atau /posts/api.
        if (is_dir($module_path))
        {
            // Format URL: /module/controller/method, contoh /posts/api/posts.
            if (isset($segments[1]) && file_exists($module_path.ucfirst($segments[1]).'.php'))
            {
                $this->module = $module;
                $this->directory = '../modules/'.$module.'/controllers/';
                array_shift($segments);

                return $segments;
            }

            // Format URL: /module/method, contoh /posts/create.
            if (file_exists($module_path.ucfirst($module).'.php'))
            {
                $this->module = $module;
                $this->directory = '../modules/'.$module.'/controllers/';

                return $segments;
            }
        }

        return parent::_validate_request($segments);
    }
}
