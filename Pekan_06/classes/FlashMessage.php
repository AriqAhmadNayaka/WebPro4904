<?php

class FlashMessage
{
    public function set(string $message, string $type = 'success'): void
    {
        $_SESSION['flash'] = [
            'message' => $message,
            'type' => $type === 'error' ? 'error' : 'success',
        ];
    }

    public function get(): array
    {
        $flash = $_SESSION['flash'] ?? ['message' => '', 'type' => 'success'];
        unset($_SESSION['flash']);

        return $flash;
    }
}
