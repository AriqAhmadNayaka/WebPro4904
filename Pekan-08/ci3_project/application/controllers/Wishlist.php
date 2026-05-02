<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Wishlist extends CI_Controller {
    public function __construct() {
        parent::__construct();
        // cek login
        if (!$this->session->userdata('user_login')) { redirect('login'); }
        // load kebutuhan
        $this->load->model('Wishlist_model');
        $this->load->library(array('session','form_validation','upload'));
        $this->load->helper(array('url','form','file'));
    }
    // tampil data
    public function index() {
        $data['title']='Wishlist Wisata - WeBandoo+';
        $data['user']=$this->session->userdata('user_login');
        $data['wishlists']=$this->Wishlist_model->ambil_semua();
        $this->load->view('layouts/header',$data);
        $this->load->view('wishlist/index',$data);
        $this->load->view('layouts/footer');
    }
    // form tambah
    public function tambah() {
        $data['title']='Tambah Wishlist - WeBandoo+';
        $data['user']=$this->session->userdata('user_login');
        $this->load->view('layouts/header',$data);
        $this->load->view('wishlist/tambah',$data);
        $this->load->view('layouts/footer');
    }
    // simpan data + upload
    public function simpan() {
        // validasi
        $this->form_validation->set_rules('nama','Nama Tempat','required');
        $this->form_validation->set_rules('deskripsi','Deskripsi','required');
        $this->form_validation->set_rules('lokasi','Lokasi','required');
        $this->form_validation->set_rules('harga','Harga','required');
        if ($this->form_validation->run()===FALSE){
            $this->session->set_flashdata('error',validation_errors());
            redirect('wishlist/tambah');
        }
        // upload gambar
        $nama_gambar=$this->_upload_gambar();
        if ($nama_gambar===FALSE){
            $this->session->set_flashdata('error',$this->upload->display_errors('',''));
            redirect('wishlist/tambah');
        }
        // simpan
        $data=array(
            'nama'=>$this->input->post('nama'),
            'deskripsi'=>$this->input->post('deskripsi'),
            'lokasi'=>$this->input->post('lokasi'),
            'harga'=>$this->input->post('harga'),
            'gambar'=>$nama_gambar,
        );
        $this->Wishlist_model->tambah($data);
        $this->session->set_flashdata('success','Data berhasil ditambah');
        redirect('wishlist');
    }
    // form edit
    public function edit($id) {
        $wishlist=$this->Wishlist_model->ambil_by_id($id);
        if (!$wishlist){ show_404(); }
        $data['title']='Edit Wishlist - WeBandoo+';
        $data['user']=$this->session->userdata('user_login');
        $data['wishlist']=$wishlist;
        $this->load->view('layouts/header',$data);
        $this->load->view('wishlist/edit',$data);
        $this->load->view('layouts/footer');
    }
    // update data
    public function update($id) {
        $wishlist=$this->Wishlist_model->ambil_by_id($id);
        if (!$wishlist){ show_404(); }
        // validasi
        $this->form_validation->set_rules('nama','Nama Tempat','required');
        $this->form_validation->set_rules('deskripsi','Deskripsi','required');
        $this->form_validation->set_rules('lokasi','Lokasi','required');
        $this->form_validation->set_rules('harga','Harga','required');
        if ($this->form_validation->run()===FALSE){
            $this->session->set_flashdata('error',validation_errors());
            redirect('wishlist/edit/'.$id);
        }
        // data baru
        $data=array(
            'nama'=>$this->input->post('nama'),
            'deskripsi'=>$this->input->post('deskripsi'),
            'lokasi'=>$this->input->post('lokasi'),
            'harga'=>$this->input->post('harga'),
        );
        // upload gambar baru
        if (!empty($_FILES['gambar']['name'])){
            $nama_gambar=$this->_upload_gambar();
            if ($nama_gambar===FALSE){
                $this->session->set_flashdata('error',$this->upload->display_errors('',''));
                redirect('wishlist/edit/'.$id);
            }
            // hapus gambar lama
            $path_lama='./uploads/wishlist/'.$wishlist->gambar;
            if (file_exists($path_lama)){ unlink($path_lama); }
            $data['gambar']=$nama_gambar;
        }
        $this->Wishlist_model->update($id,$data);
        $this->session->set_flashdata('success','Data berhasil diupdate');
        redirect('wishlist');
    }
    // hapus data
    public function hapus($id) {
        $wishlist=$this->Wishlist_model->ambil_by_id($id);
        if (!$wishlist){ show_404(); }
        // hapus file
        $path='./uploads/wishlist/'.$wishlist->gambar;
        if (file_exists($path)){ unlink($path); }
        // hapus db
        $this->Wishlist_model->hapus($id);
        $this->session->set_flashdata('success','Data berhasil dihapus');
        redirect('wishlist');
    }
    // fungsi upload gambar
    private function _upload_gambar() {
        $config=array(
            'upload_path'=>'./uploads/wishlist/',
            'allowed_types'=>'jpg|jpeg|png|webp',
            'max_size'=>2048,
            'file_name'=>time().'_'.rand(1000,9999),
            'overwrite'=>FALSE,
        );
        $this->upload->initialize($config);
        // buat folder kalau belum ada
        if (!is_dir('./uploads/wishlist/')){
            mkdir('./uploads/wishlist/',0777,TRUE);
        }
        // proses upload
        if ($this->upload->do_upload('gambar')){
            return $this->upload->data('file_name');
        }
        return FALSE;
    }
}