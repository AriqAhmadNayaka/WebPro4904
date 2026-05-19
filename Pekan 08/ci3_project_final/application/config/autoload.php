<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 | Auto-load Libraries & Helpers
 | Session dan database di-load otomatis di setiap request
 */

$autoload['packages']   = array();
$autoload['libraries']  = array('database', 'session');
$autoload['drivers']    = array();
$autoload['helper']     = array('url', 'form');
$autoload['config']     = array();
$autoload['language']   = array();
$autoload['model']      = array();
