<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
File ini mengatur pemetaan URL ke controller yang sesuai.
Secara default CI3 memetakan URL ke controller dengan pola:
example.com/[controller]/[method]/[parameter]

Konfigurasi di bawah mengubah perilaku default tersebut.
*/

/*
$route['default_controller']
Controller yang dijalankan ketika URL tidak memiliki segmen apapun
(misalnya: http://localhost/Pekan-08/).

DIUBAH dari 'welcome' (default CI3) menjadi 'auth', sehingga
saat user membuka root URL, mereka langsung diarahkan ke halaman login,
bukan halaman welcome bawaan CI3.
*/
$route['default_controller'] = 'auth';

/*
$route['404_override']
Controller yang menangani jika URL tidak cocok dengan controller/method apapun.
Dikosongkan artinya CI3 akan menggunakan halaman 404 default-nya sendiri.
*/
$route['404_override'] = '';

/*
$route['translate_uri_dashes']
Jika TRUE, tanda '-' di URL akan otomatis diubah menjadi '_' (underscore)
sebelum dicocokkan ke nama controller/method.
Contoh: URL 'my-controller' - controller 'my_controller'
Dibiarkan FALSE karena nama controller/method di project ini tidak menggunakan tanda hubung.
*/
$route['translate_uri_dashes'] = FALSE;
