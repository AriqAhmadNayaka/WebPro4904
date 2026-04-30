<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'Crudjs/Crudjs';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['login'] = 'auth';
$route['register'] = 'auth/register';
$route['logout'] = 'auth/logout';
$route['dashboard'] = 'dashboard';
$route['peserta'] = 'peserta';
$route['register_API'] = 'Register_API/register_API';
$route['peserta/simpan'] = 'peserta/save';
$route['peserta/hapus/(:num)'] = 'peserta/delete/$1';

// HMVC AJAX CRUD routes
$route['crudjs'] = 'Crudjs/Crudjs';
$route['crudjs/debug'] = 'Crudjs/Crudjs/debug';
$route['crudjs/test'] = 'Crudjs/Crudjs/test';
$route['crudjs/get_all'] = 'Crudjs/Crudjs/get_all';
$route['crudjs/get_record/(:num)'] = 'Crudjs/Crudjs/get_record/$1';
$route['crudjs/store'] = 'Crudjs/Crudjs/store';
$route['crudjs/update/(:num)'] = 'Crudjs/Crudjs/update/$1';
$route['crudjs/delete/(:num)'] = 'Crudjs/Crudjs/delete/$1';

// API Routes
$route['api/auth/register'] = 'Api/Auth/register'; // Endpoint API untuk mendaftarkan user baru melalui controller Api/Auth method register.
$route['api/auth/login'] = 'Api/Auth/login'; // Endpoint API untuk proses login user melalui controller Api/Auth method login.
$route['api/auth/logout'] = 'Api/Auth/logout'; // Endpoint API untuk proses logout user melalui controller Api/Auth method logout.
$route['api/auth/me'] = 'Api/Auth/me'; // Endpoint API untuk mengambil data user yang sedang login melalui controller Api/Auth method me.
$route['api/post/(:num)'] = 'Api/Post/handle/$1'; // Endpoint API untuk mengelola satu data post berdasarkan ID angka yang dikirim pada URL.
$route['api/post'] = 'Api/Post/handle'; // Endpoint API untuk mengelola data post tanpa ID, misalnya menampilkan semua data atau menambah post baru.
