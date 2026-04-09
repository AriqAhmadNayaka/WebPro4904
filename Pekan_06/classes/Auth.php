<?php

require_once __DIR__ . '/AppConfig.php';

class Auth
{
    public function __construct()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    public function login(string $username, string $password): bool
    {
        if ($username !== AppConfig::LOGIN_USERNAME || $password !== AppConfig::LOGIN_PASSWORD) {
            return false;
        }

        $_SESSION['login'] = true;
        $_SESSION['user'] = $username;

        return true;
    }

    public function logout(): void
    {
        $_SESSION = [];
        session_destroy();
    }

    public function check(): bool
    {
        return isset($_SESSION['login']) && $_SESSION['login'] === true;
    }

    public function user(): string
    {
        return (string) ($_SESSION['user'] ?? '');
    }
}
