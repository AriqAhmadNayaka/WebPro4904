<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// default masuk ke halaman login dulu
$route['default_controller'] = 'Auth';

// kalau halaman ga ada
$route['404_override'] = '';

// setting default CI
$route['translate_uri_dashes'] = FALSE;

// ===== AUTH =====
$route['login'] = 'Auth/login';       // proses login
$route['register'] = 'Auth/register'; // daftar user
$route['logout'] = 'Auth/logout';     // keluar / logout

// ===== DASHBOARD =====
$route['dashboard'] = 'Dashboard/index'; // halaman utama setelah login

// ===== WISHLIST =====
$route['wishlist'] = 'Wishlist/index'; // tampil data

$route['wishlist/tambah'] = 'Wishlist/tambah'; // form tambah
$route['wishlist/simpan'] = 'Wishlist/simpan'; // simpan data

$route['wishlist/edit/(:num)'] = 'Wishlist/edit/$1'; // edit berdasarkan id
$route['wishlist/update/(:num)'] = 'Wishlist/update/$1'; // update data

$route['wishlist/hapus/(:num)'] = 'Wishlist/hapus/$1'; // hapus data