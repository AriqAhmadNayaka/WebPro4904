<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'auth';
$route['dashboard'] = 'dashboard';
$route['login'] = 'auth';
$route['logout'] = 'auth/logout';

$route['menu'] = 'menu';
$route['menu/tambah'] = 'menu/form';
$route['menu/edit/(:num)'] = 'menu/form/$1';
$route['menu/simpan'] = 'menu/simpan';
$route['menu/hapus/(:num)'] = 'menu/hapus/$1';

$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
