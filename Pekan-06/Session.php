<?php
class Session { // class untuk mengatur session user login

    public function checkLogin(){ // fungsi untuk cek apakah user sudah login
        if(!isset($_SESSION['username'])){ // jika session username tidak tersedia
            header("Location: login2.php"); // redirect ke halaman login
            exit; // hentikan eksekusi setelah redirect
        }
    }

    public function getUser(){ // fungsi untuk ambil username login
        return $_SESSION['username']; // kembalikan nilai username dari session
    }

    public function checkCookie(){ // fungsi untuk cek login dari cookie
        if(isset($_COOKIE['username']) && !isset($_SESSION['username'])){ // jika cookie ada tapi session belum
            $_SESSION['username'] = $_COOKIE['username']; // set session dari cookie yang tersimpan
        }
    }
}
?>