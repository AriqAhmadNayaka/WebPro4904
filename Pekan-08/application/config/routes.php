<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'auth/login';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

// Route Auth
$route['login']  = 'auth/login';
$route['register'] = 'auth/register';
$route['logout'] = 'auth/logout';

// Route Data User (CRUD)
$route['datauser'] = 'datauser';
$route['datauser/create'] = 'datauser/create';
$route['datauser/edit/(:any)'] = 'datauser/edit/$1';
$route['datauser/delete/(:any)'] = 'datauser/delete/$1';