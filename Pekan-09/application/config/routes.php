<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'posts';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['api/auth/register'] = 'Api/Auth/register';
$route['api/auth/login'] = 'Api/Auth/login';
$route['api/auth/logout'] = 'Api/Auth/logout';
$route['api/auth/me'] = 'Api/Auth/me';

$route['api/post/(:num)'] = 'Api/Post/handle/$1';
$route['api/post'] = 'Api/Post/handle';
?>