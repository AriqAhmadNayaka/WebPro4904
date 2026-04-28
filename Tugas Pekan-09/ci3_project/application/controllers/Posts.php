<?php
class Posts extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('Post_model');
        $this->load->helper(array('form', 'url'));
    }

    public function index(){
        $data['posts'] = $this->Post_model->get_posts();
        $this->load->view('posts/index', $data);
    }

    public function create(){
        $this->load->view('posts/create');
    }

    public function store(){
        $this->Post_model->insert_post();
        redirect('posts');
    }

    public function show($id){
        $data['post'] = $this->Post_model->get_posts($id);
        $this->load->view('posts/show', $data);
    }

    public function edit($id){
        $data['post'] = $this->Post_model->get_posts($id);
        $this->load->view('posts/edit', $data);
    }

    public function update($id){
        $this->Post_model->update_post($id);
        redirect('posts');
    }

    public function delete($id){
        $this->Post_model->delete_post($id);
        redirect('posts');
    }
}