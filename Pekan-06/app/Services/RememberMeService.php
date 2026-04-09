<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\UserRepository;

// Service ini menangani fitur remember me berbasis cookie.
final class RememberMeService
{
    // Nama cookie yang dipakai untuk menyimpan data remember me.
    private const COOKIE_NAME = "inkluskill_remember";
    // Lama aktif cookie remember me dalam hari.
    private const COOKIE_DAYS = 7;
    // Secret key untuk membuat signature cookie.
    private const SECRET_KEY = "inkluskill_pekan5_secret_key";

    // Constructor menerima repository user untuk validasi data dari cookie.
    public function __construct(private UserRepository $userRepository)
    {
    }

    // Menyimpan cookie remember me saat user memilih tetap login.
    public function set(array $user): void
    {
        $identifier = $this->getIdentifier($user);
        if ($identifier === "") {
            return;
        }

        $expiresAt = time() + (self::COOKIE_DAYS * 24 * 60 * 60);
        $payload = json_encode([
            "identifier" => $identifier,
            "expires_at" => $expiresAt,
            "signature" => $this->buildSignature($user, $expiresAt),
        ]);

        if ($payload === false) {
            return;
        }

        setcookie(
            self::COOKIE_NAME,
            base64_encode($payload),
            [
                "expires" => $expiresAt,
                "path" => "/",
                "httponly" => true,
                "samesite" => "Lax",
            ]
        );
    }

    // Menghapus cookie remember me dari browser.
    public function clear(): void
    {
        setcookie(
            self::COOKIE_NAME,
            "",
            [
                "expires" => time() - 3600,
                "path" => "/",
                "httponly" => true,
                "samesite" => "Lax",
            ]
        );
    }

    // Memulihkan data user dari cookie remember me yang masih valid.
    public function restore(): ?array
    {
        if (empty($_COOKIE[self::COOKIE_NAME])) {
            return null;
        }

        $decoded = base64_decode($_COOKIE[self::COOKIE_NAME], true);
        if ($decoded === false) {
            $this->clear();

            return null;
        }

        $data = json_decode($decoded, true);
        if (!is_array($data)) {
            $this->clear();

            return null;
        }

        $identifier = trim((string)($data["identifier"] ?? ""));
        $expiresAt = (int)($data["expires_at"] ?? 0);
        $signature = (string)($data["signature"] ?? "");

        if ($identifier === "" || $expiresAt < time() || $signature === "") {
            $this->clear();

            return null;
        }

        $user = $this->userRepository->findByEmail($identifier);
        if ($user === null) {
            $this->clear();

            return null;
        }

        if (!hash_equals($this->buildSignature($user, $expiresAt), $signature)) {
            $this->clear();

            return null;
        }

        return $user;
    }

    // Mengambil identifier user yang dipakai di cookie, yaitu email.
    private function getIdentifier(array $user): string
    {
        return (string)($user["email"] ?? "");
    }

    // Membuat signature untuk memastikan cookie tidak dimanipulasi.
    private function buildSignature(array $user, int $expiresAt): string
    {
        $identifier = $this->getIdentifier($user);
        $passwordHash = (string)($user["password"] ?? "");
        $role = (string)($user["role"] ?? "");

        return hash_hmac("sha256", $identifier . "|" . $role . "|" . $expiresAt, self::SECRET_KEY . $passwordHash);
    }
}
