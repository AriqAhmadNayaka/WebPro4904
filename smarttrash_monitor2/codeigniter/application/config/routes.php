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
$route['default_controller'] = 'auth';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
$route['dashboard/kategori/(:num)'] = 'dashboard/catalog/$1';
$route['api/monitoring'] = 'monitoring/api/monitoring/index';
$route['api/monitoring/(:num)'] = 'monitoring/api/monitoring/index/$1';
$route['api/auth/register'] = 'api/auth/register';
$route['api/auth/login'] = 'api/auth/login';
$route['api/auth/me'] = 'api/auth/me';
$route['api/auth/logout'] = 'api/auth/logout';
$route['api/auth/request-reset'] = 'api/auth/request_reset';
$route['api/auth/reset-password'] = 'api/auth/reset_password';
$route['api/portal/dashboard'] = 'api/portal/dashboard';
$route['api/portal/timeline'] = 'api/portal/timeline';
$route['api/portal/notifications'] = 'api/portal/notifications';
$route['api/portal/account'] = 'api/portal/account';
