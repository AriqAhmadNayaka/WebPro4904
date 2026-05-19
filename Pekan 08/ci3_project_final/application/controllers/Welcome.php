<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller Welcome — redirect ke auth (login)
 * Menggantikan default welcome page CI3
 */
class Welcome extends CI_Controller
{
    public function index()
    {
        redirect('auth');
    }
}
