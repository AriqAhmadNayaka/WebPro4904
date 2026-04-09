<?php

class Auth
{
    public function user(): ?array
    {
        return $_SESSION['current_user'] ?? null;
    }

    public function id(): int
    {
        return (int) ($this->user()['id'] ?? 0);
    }

    public function check(): bool
    {
        return $this->id() > 0;
    }

    public function requireLogin(string $redirectTo): void
    {
        if (!$this->check()) {
            header('Location: ' . $redirectTo);
            exit;
        }
    }
}
