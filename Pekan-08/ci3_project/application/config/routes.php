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
// $route['default_controller'] = 'welcome';
// $route['beranda'] = 'welcome/beranda';
// $route['404_override'] = '';
// $route['translate_uri_dashes'] = FALSE;

// Mengatur route default controller ke halaman login untuk memastikan bahwa pengguna diarahkan ke halaman login saat mengakses root URL aplikasi, 
// sehingga pengguna dapat melakukan autentikasi sebelum mengakses halaman lain yang membutuhkan autentikasi 
$route['default_controller'] = 'auth/login';
// Mengatur route untuk halaman dashboard dan dompet agar dapat diakses dengan URL yang lebih sederhana, 
// serta memastikan bahwa hanya pengguna yang sudah login yang dapat mengakses halaman dashboard dan dompet dengan memeriksa session user_id di dalam controller masing-masing, 
// sehingga pengguna yang belum login akan diarahkan ke halaman login saat mencoba mengakses halaman dashboard atau dompet
$route['dashboard'] = 'dashboard';
// Mengatur route untuk halaman dompet agar dapat diakses dengan URL yang lebih sederhana, 
// serta memastikan bahwa hanya pengguna yang sudah login yang dapat mengakses halaman dompet dengan memeriksa session user_id di dalam controller Dompet, 
// sehingga pengguna yang belum login akan diarahkan ke halaman login saat mencoba mengakses halaman dompet
$route['dompet'] = 'dompet';
// Mengatur route untuk halaman 404 override, jika URL yang diakses tidak cocok dengan route yang sudah didefinisikan, 
// maka akan diarahkan ke halaman 404 yang sudah disiapkan untuk memberikan feedback kepada pengguna bahwa halaman yang diakses tidak ditemukan
$route['404_override'] = ''; 
// Mengatur opsi translate_uri_dashes ke FALSE untuk memastikan bahwa URL yang mengandung tanda hubung tidak diterjemahkan menjadi garis bawah, 
// sehingga URL yang digunakan tetap sesuai dengan nama controller dan method yang sebenarnya tanpa perlu khawatir tentang karakter yang tidak valid dalam nama controller atau method
$route['translate_uri_dashes'] = FALSE; 