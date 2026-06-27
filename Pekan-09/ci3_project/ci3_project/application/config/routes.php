<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'posts';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['posts'] = 'posts/posts/index';
$route['posts/create'] = 'posts/posts/create';
$route['posts/store'] = 'posts/posts/store';
$route['posts/show/(:num)'] = 'posts/posts/show/$1';
$route['posts/edit/(:num)'] = 'posts/posts/edit/$1';
$route['posts/update/(:num)'] = 'posts/posts/update/$1';
$route['posts/delete/(:num)'] = 'posts/posts/delete/$1';

$route['crudjs'] = 'crudjs/crudjs/index';
$route['crudjs/get_posts'] = 'crudjs/crudjs/get_posts';
$route['crudjs/get_post/(:num)'] = 'crudjs/crudjs/get_post/$1';
$route['crudjs/create'] = 'crudjs/crudjs/create';
$route['crudjs/update/(:num)'] = 'crudjs/crudjs/update/$1';
$route['crudjs/delete/(:num)'] = 'crudjs/crudjs/delete/$1';

$route['api/auth/register'] = 'api/auth/register';
$route['api/auth/login'] = 'api/auth/login';
$route['api/auth/logout'] = 'api/auth/logout';
$route['api/auth/me'] = 'api/auth/me';

$route['api/post']['GET'] = 'api/post/index';
$route['api/post/(:num)']['GET'] = 'api/post/show/$1';
$route['api/post']['POST'] = 'api/post/create';
$route['api/post/(:num)']['PUT'] = 'api/post/update/$1';
$route['api/post/(:num)']['DELETE'] = 'api/post/delete/$1';
