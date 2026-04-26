<?php
define('ENVIRONMENT', isset($_SERVER['CI_ENV']) ? $_SERVER['CI_ENV'] : 'development'); //untuk menentukan environment aplikasi, bisa diatur melalui variabel server CI_ENV

if (isset($_SERVER['REQUEST_URI'])) //untuk memastikan bahwa REQUEST_URI tersedia, jika tidak, maka akan menggunakan PATH_INFO
{
    $_SERVER['REQUEST_URI'] = rawurldecode($_SERVER['REQUEST_URI']);
}

switch (ENVIRONMENT) //untuk mengatur level error reporting berdasarkan environment yang telah ditentukan
{
    case 'development': //jika environment adalah development, maka akan menampilkan semua error kecuali deprecated dan user deprecated
        error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);
        ini_set('display_errors', 1);
    break;

    case 'testing': //jika environment adalah testing, maka akan menampilkan semua error kecuali deprecated, strict, user deprecated, dan user notice
        error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT & ~E_USER_DEPRECATED & ~E_USER_NOTICE);
        ini_set('display_errors', 1);
    case 'production': //jika environment adalah production, maka akan menampilkan semua error kecuali notice, deprecated, strict, user deprecated, dan user notice
        ini_set('display_errors', 0);
        if (version_compare(PHP_VERSION, '5.3', '>='))
        {
            error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED);
        }
        else
        {
            error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_USER_NOTICE);
        }
    break;

    default: //jika environment tidak sesuai dengan yang telah ditentukan, maka akan menampilkan error 503 Service Unavailable
        header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
        echo 'The application environment is not set correctly.';
        exit(1);
}

$system_path = 'system';
$application_folder = 'application'; //path ke folder aplikasi, bisa diubah sesuai kebutuhan
$view_folder = '';

if (defined('STDIN'))
{
    chdir(dirname(__FILE__));
}

if (($_temp = realpath($system_path)) !== FALSE) //untuk mendapatkan path absolut dari folder sistem, jika berhasil, maka akan menggunakan path tersebut
{
    $system_path = $_temp.DIRECTORY_SEPARATOR;
}
else
{
    $system_path = strtr(rtrim($system_path, '/\\'), '/\\', DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
}

if (!is_dir($system_path)) //untuk memastikan bahwa folder sistem benar-benar ada
{
    header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
    echo 'Your system folder path does not appear to be set correctly.';
    exit(3);
}

define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME)); //untuk mendefinisikan nama file index.php sebagai konstanta SELF
define('BASEPATH', $system_path); //untuk mendefinisikan path ke folder sistem sebagai konstanta BASEPATH
define('FCPATH', dirname(__FILE__).DIRECTORY_SEPARATOR); //untuk mendefinisikan path ke folder root sebagai konstanta FCPATH
define('SYSDIR', basename(BASEPATH)); //untuk mendefinisikan nama folder sistem sebagai konstanta SYSDIR

if (is_dir($application_folder)) //untuk memastikan bahwa folder aplikasi benar-benar ada, jika berhasil, maka akan menggunakan path tersebut
{
    if (($_temp = realpath($application_folder)) !== FALSE) //untuk mendapatkan path absolut dari folder aplikasi, jika berhasil, maka akan menggunakan path tersebut
    {
        $application_folder = $_temp;
    }
    else
    {
        $application_folder = strtr(rtrim($application_folder, '/\\'), '/\\', DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR);
    }
}
elseif (is_dir(BASEPATH.$application_folder.DIRECTORY_SEPARATOR))
{
    $application_folder = BASEPATH.strtr(trim($application_folder, '/\\'), '/\\', DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR);
}
else
{
    header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
    echo 'Your application folder path does not appear to be set correctly.';
    exit(3);
}

define('APPPATH', $application_folder.DIRECTORY_SEPARATOR); //untuk mendefinisikan path ke folder aplikasi sebagai konstanta APPPATH 

if (!isset($view_folder[0]) && is_dir(APPPATH.'views'.DIRECTORY_SEPARATOR)) //untuk memastikan bahwa folder views benar-benar ada, jika berhasil, maka akan menggunakan path tersebut
{
    $view_folder = APPPATH.'views';
}
elseif (is_dir($view_folder))
{
    if (($_temp = realpath($view_folder)) !== FALSE) //untuk mendapatkan path absolut dari folder views, jika berhasil, maka akan menggunakan path tersebut
    {
        $view_folder = $_temp;
    }
    else
    {
        $view_folder = strtr(rtrim($view_folder, '/\\'), '/\\', DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR);
    }
}
elseif (is_dir(APPPATH.$view_folder.DIRECTORY_SEPARATOR)) //untuk memastikan bahwa folder views benar-benar ada di dalam folder aplikasi, jika berhasil, maka akan menggunakan path tersebut
{
    $view_folder = APPPATH.strtr(trim($view_folder, '/\\'), '/\\', DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR);
}
else
{
    header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
    echo 'Your view folder path does not appear to be set correctly.';
    exit(3);
}

define('VIEWPATH', $view_folder.DIRECTORY_SEPARATOR);

require_once BASEPATH.'core/CodeIgniter.php'; //untuk memuat file CodeIgniter.php yang berada di dalam folder core, file ini merupakan inti dari framework CodeIgniter yang akan memproses semua permintaan dan menjalankan aplikasi
