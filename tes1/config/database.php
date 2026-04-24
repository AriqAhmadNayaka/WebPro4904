<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| KONFIGURASI DATABASE NAVIBIZ — CI3
| -------------------------------------------------------------------------
| Sesuaikan dengan database lokal kamu.
| Nama database TIDAK BERUBAH: tetap 'navibiz'
*/

$active_group = 'default';
$query_builder = TRUE;

$db['default'] = array(
    'dsn'          => '',
    'hostname'     => 'localhost',
    'username'     => 'root',
    'password'     => '',          // password MySQL kamu (default XAMPP: kosong)
    'database'     => 'navibiz',   // nama database TIDAK BERUBAH
    'dbdriver'     => 'mysqli',
    'dbprefix'     => '',
    'pconnect'     => FALSE,
    'db_debug'     => (ENVIRONMENT !== 'production'),
    'cache_on'     => FALSE,
    'cachedir'     => '',
    'char_set'     => 'utf8',
    'dbcollat'     => 'utf8_general_ci',
    'swap_pre'     => '',
    'encrypt'      => FALSE,
    'compress'     => FALSE,
    'stricton'     => FALSE,
    'failover'     => array(),
    'save_queries' => TRUE,
);
