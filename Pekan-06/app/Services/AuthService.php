<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\UserRepository;
use App\Support\Response;
use App\Support\SessionManager;

// Service ini menangani alur autentikasi seperti login, register, dan logout.
final class AuthService
{
    // Constructor menerima object session, repository, dan remember me service.
    public function __construct(
        private SessionManager $session,
        private UserRepository $userRepository,
        private RememberMeService $rememberMe
    ) {
    }

    // Mengambil email user yang sedang login dari session.
    public function currentUser(): ?string
    {
        $user = $this->session->get("user");

        return $user === null || $user === "" ? null : (string)$user;
    }

    // Mengambil nama user yang sedang login untuk ditampilkan di halaman.
    public function username(): string
    {
        // Ambil username yang tersimpan di session.
        $username = trim((string)$this->session->get("username", ""));
        // Ambil email user yang sedang login sebagai acuan fallback.
        $currentEmail = trim((string)$this->session->get("user", ""));

        // Jika session username sudah ada dan bukan email, langsung gunakan.
        if ($username !== "" && $username !== $currentEmail) {
            return $username;
        }

        // Ambil ulang data user dari database untuk memastikan nama yang tampil adalah nama register.
        if ($currentEmail !== "") {
            $user = $this->userRepository->findByEmail($currentEmail);

            if ($user !== null) {
                $resolvedUsername = trim((string)($user["username"] ?? ($user["nama"] ?? "")));

                // Jika nama ditemukan, perbarui session lalu tampilkan nama tersebut.
                if ($resolvedUsername !== "") {
                    $this->session->set("username", $resolvedUsername);

                    return $resolvedUsername;
                }
            }
        }

        // Jika nama tetap tidak ditemukan, gunakan nilai session lama.
        return $username;
    }

    // Mencoba memulihkan login user dari cookie remember me.
    public function restoreUserFromCookie(): bool
    {
        if ($this->currentUser() !== null) {
            return true;
        }

        $user = $this->rememberMe->restore();
        if ($user === null) {
            return false;
        }

        $this->storeUserSession($user);

        return true;
    }

    // Menangani proses login user.
    public function login(string $email, string $password, string $role, bool $rememberMe): array
    {
        if ($email === "" || $password === "" || $role === "") {
            return [false, "Email, password, dan role wajib diisi."];
        }

        $user = $this->userRepository->findByCredentials($email, md5($password), $role);
        if ($user === null) {
            return [false, "Email, password, atau role tidak cocok."];
        }

        $this->storeUserSession($user);

        if ($rememberMe) {
            $this->rememberMe->set($user);
        } else {
            $this->rememberMe->clear();
        }

        return [true, ""];
    }

    // Menangani proses registrasi akun baru.
    public function register(string $username, string $email, string $password, string $role): array
    {
        if ($username === "" || $email === "" || $password === "") {
            return [false, "Username, email, dan password wajib diisi."];
        }

        return $this->userRepository->createUser($username, $email, md5($password), $role);
    }

    // Menjalankan proses logout user dari session dan cookie.
    public function logout(): void
    {
        $this->rememberMe->clear();
        $this->session->destroy();
    }

    // Memastikan user wajib login sebelum membuka halaman tertentu.
    public function requireLogin(string $redirectTo = "auth.php"): void
    {
        $this->restoreUserFromCookie();

        if ($this->currentUser() === null) {
            Response::redirect($redirectTo);
        }
    }

    // Menyimpan data penting user ke session setelah login berhasil.
    private function storeUserSession(array $user): void
    {
        $this->session->set("user", $user["email"] ?? "");
        $this->session->set("role", $user["role"] ?? "");
        $this->session->set("username", $user["username"] ?? ($user["nama"] ?? ""));
    }
}
