<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        // Saat membuka module, tambahkan path module agar model dan view bisa dipanggil lokal.
        if (!empty($this->router->module))
        {
            $this->load->add_package_path(APPPATH.'modules/'.$this->router->module);
        }
    }
}
