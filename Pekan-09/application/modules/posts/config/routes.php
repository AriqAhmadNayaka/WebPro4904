<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['posts'] = 'posts';
$route['api/posts'] = 'api/posts';
$route['api/posts/(:num)'] = 'api/posts/index/$1';
