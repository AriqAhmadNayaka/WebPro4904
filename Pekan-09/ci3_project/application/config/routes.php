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
$route['default_controller'] = 'webandoo';
$route['login'] = 'webandoo/login';
$route['register'] = 'webandoo/register';
$route['dashboard'] = 'webandoo/dashboard';
$route['profil'] = 'webandoo/profile';
$route['logout'] = 'webandoo/logout';
$route['warisan'] = 'webandoo/warisan';
$route['peta'] = 'webandoo/peta';
$route['event'] = 'webandoo/event';
$route['belajar'] = 'webandoo/belajar';
$route['proses-login'] = 'webandoo/process_login';
$route['proses-register'] = 'webandoo/process_register';
$route['proses-update-profil'] = 'webandoo/update_profile';
$route['posts'] = 'posts/posts/index';
$route['posts/create'] = 'posts/posts/create';
$route['posts/store']['post'] = 'posts/posts/store';
$route['posts/edit/(:num)'] = 'posts/posts/edit/$1';
$route['posts/update/(:num)']['post'] = 'posts/posts/update/$1';
$route['posts/delete/(:num)']['post'] = 'posts/posts/delete/$1';
$route['api/auth/register']['post'] = 'api/auth/register';
$route['api/auth/login']['post'] = 'api/auth/login';
$route['api/auth/logout']['post'] = 'api/auth/logout';
$route['api/auth/me']['get'] = 'api/auth/me';
$route['api/auth/me']['post'] = 'api/auth/me';
$route['api/posts']['get'] = 'api/post/index';
$route['api/posts']['post'] = 'api/post/store';
$route['api/posts/(:num)']['get'] = 'api/post/show/$1';
$route['api/posts/(:num)']['post'] = 'api/post/update/$1';
$route['api/posts/(:num)']['put'] = 'api/post/update/$1';
$route['api/posts/(:num)']['delete'] = 'api/post/delete/$1';
$route['api/posts/(:num)/delete']['post'] = 'api/post/delete/$1';
$route['api/post']['get'] = 'api/post/index';
$route['api/post']['post'] = 'api/post/store';
$route['api/post/(:num)']['get'] = 'api/post/show/$1';
$route['api/post/(:num)']['post'] = 'api/post/update/$1';
$route['api/post/(:num)']['put'] = 'api/post/update/$1';
$route['api/post/(:num)']['delete'] = 'api/post/delete/$1';
$route['api/post/(:num)/delete']['post'] = 'api/post/delete/$1';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
