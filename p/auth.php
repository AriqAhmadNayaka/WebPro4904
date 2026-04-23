<?php
session_start();

function is_logged_in(): bool
{
    return isset($_SESSION['user_id'], $_SESSION['user_name'], $_SESSION['user_email']);
}

function require_login(): void
{
    if (!is_logged_in()) {
        header('Location: login.php?error=' . urlencode('Silakan login terlebih dahulu.'));
        exit;
    }
}

function redirect_if_logged_in(): void
{
    if (is_logged_in()) {
        header('Location: dashboard.php');
        exit;
    }
}

function current_user_id(): int
{
    return (int) ($_SESSION['user_id'] ?? 0);
}

function current_user_name(): string
{
    return $_SESSION['user_name'] ?? 'Operator';
}

function set_flash(string $type, string $message): void
{
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message,
    ];
}

function get_flash(): ?array
{
    if (!isset($_SESSION['flash'])) {
        return null;
    }

    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);

    return $flash;
}
