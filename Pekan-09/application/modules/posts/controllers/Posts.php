<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Posts extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->require_login();
        $this->load->model('posts/Posts_model');
    }

    public function index()
    {
        $data['title'] = 'Pekan 09 - HMVC REST API Posts';
        $data['base_api_url'] = site_url('api/posts');
        $this->load->view('index', $data);
    }
}
