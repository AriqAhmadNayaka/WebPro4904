<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'posts';
$route['api/test']['get'] = 'posts/api/test';
$route['api/test']['options'] = 'posts/api/test';
$route['api/posts']['get'] = 'posts/api/posts';
$route['api/posts']['options'] = 'posts/api/posts';
$route['api/posts/(:num)']['get'] = 'posts/api/posts/$1';
$route['api/posts/(:num)']['options'] = 'posts/api/posts/$1';
$route['api/posts']['post'] = 'posts/api/posts';
$route['api/posts/(:num)']['put'] = 'posts/api/posts/$1';
$route['api/posts/(:num)']['delete'] = 'posts/api/posts/$1';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;