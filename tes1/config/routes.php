<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| ROUTING NAVIBIZ — CI3
| -------------------------------------------------------------------------
| Default controller saat buka http://localhost/ci3_navibiz/
| diarahkan ke Auth (halaman login)
*/

$route['default_controller'] = 'auth';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
