<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['monitoring'] = 'monitoring';
$route['api/monitoring'] = 'api/monitoring';
$route['api/monitoring/(:num)'] = 'api/monitoring/index/$1';
