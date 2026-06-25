<?php

class Auth
{
    public static function userId(): ?int
    {
        return empty($_SESSION["user_id"]) ? null : (int) $_SESSION["user_id"];
    }

    public static function requireLogin(): int
    {
        $userId = self::userId();
        if (!$userId) {
            Response::json([
                "success" => false,
                "message" => "Kamu harus login dulu."
            ], 401);
        }

        return $userId;
    }

    public static function requireAdmin(mysqli $conn): array
    {
        $userId = self::requireLogin();
        $user = User::findById($conn, $userId);

        if (!$user || ($user["role"] ?? "user") !== "admin") {
            Response::json([
                "success" => false,
                "message" => "Akses ini hanya untuk admin."
            ], 403);
        }

        return $user;
    }
}
