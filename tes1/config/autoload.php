<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| AUTOLOAD — CI3
| -------------------------------------------------------------------------
| Library, helper, dan driver yang otomatis diload di semua controller.
*/

$autoload['packages'] = array();

// Library yang selalu diload: database, session, upload, form_validation
$autoload['libraries'] = array('database', 'session');

// Helper yang selalu diload: url (untuk base_url, site_url), form (untuk form_open, dll)
$autoload['helper'] = array('url', 'form');

$autoload['config'] = array();
$autoload['language'] = array();
$autoload['model'] = array();
$autoload['drivers'] = array();
