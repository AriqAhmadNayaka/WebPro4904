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
$route['default_controller'] = 'posts/posts/index';
$route['posts'] = 'posts/posts/index';
$route['posts/create'] = 'posts/posts/create';
$route['posts/tambah'] = 'posts/posts/create';
$route['posts/store'] = 'posts/posts/store';
$route['posts/simpan'] = 'posts/posts/store';
$route['posts/show/(:num)'] = 'posts/posts/show/$1';
$route['posts/edit/(:num)'] = 'posts/posts/edit/$1';
$route['posts/update/(:num)'] = 'posts/posts/update/$1';
$route['posts/delete/(:num)'] = 'posts/posts/delete/$1';
$route['posts/hapus/(:num)'] = 'posts/posts/delete/$1';

$route['crudjs'] = 'crudjs/crudjs/index';
$route['crudjs/list']['get'] = 'crudjs/crudjs/list';
$route['crudjs/get/(:num)']['get'] = 'crudjs/crudjs/get/$1';
$route['crudjs/store']['post'] = 'crudjs/crudjs/store';
$route['crudjs/update/(:num)']['post'] = 'crudjs/crudjs/update/$1';
$route['crudjs/delete/(:num)']['post'] = 'crudjs/crudjs/delete/$1';

$route['api/auth/register']['post'] = 'api/auth/register';
$route['api/auth/login']['post'] = 'api/auth/login';
$route['api/auth/logout']['post'] = 'api/auth/logout';
$route['api/auth/me']['post'] = 'api/auth/me';
$route['api/post']['get'] = 'api/post/handle';
$route['api/post']['post'] = 'api/post/handle';
$route['api/post/(:num)']['get'] = 'api/post/handle/$1';
$route['api/post/(:num)']['put'] = 'api/post/handle/$1';
$route['api/post/(:num)']['delete'] = 'api/post/handle/$1';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
