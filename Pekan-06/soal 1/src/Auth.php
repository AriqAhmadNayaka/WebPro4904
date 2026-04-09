<?php

/**
 * Class Auth
 * Mengelola autentikasi user dan manajemen sesi.
 */
class Auth {
    /**
     * Memulai sesi jika belum dimulai.
     */
    public static function startSession() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Proses login user.
     * @return bool True jika login berhasil, false jika gagal.
     */
    public static function login($email, $password) {
        $userModel = new User();
        $user = $userModel->findByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            self::startSession();
            $_SESSION["isLogin"] = true;
            $_SESSION["username"] = $user["name"];
            return true;
        }
        return false;
    }

    /**
     * Proses logout user.
     */
    public static function logout() {
        self::startSession();
        session_destroy();
        header("Location: login.php");
        exit();
    }

    /**
     * Memastikan user sudah login. Jika belum, redirect ke halaman login.
     */
    public static function checkLogin() {
        self::startSession();
        if (!isset($_SESSION['isLogin']) || !$_SESSION['isLogin']) {
            header("Location: login.php");
            exit();
        }
    }

    /**
     * Redirect user ke dashboard jika sudah login (digunakan di halaman login/register).
     */
    public static function redirectIfLoggedIn() {
        self::startSession();
        if (isset($_SESSION['isLogin']) && $_SESSION['isLogin']) {
            header("Location: admin.php");
            exit();
        }
    }
}
