<?php

class AuthController
{
    private mysqli $conn;

    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;
        User::ensureTable($this->conn);
    }

    public function me(): void
    {
        $this->restoreRememberedUser();
        $user = Auth::userId() ? User::findById($this->conn, Auth::userId()) : null;
        Response::json([
            "success" => true,
            "authenticated" => $user !== null,
            "user" => $user
        ]);
    }

    public function register(): void
    {
        $data = Request::jsonBody();
        $nama = trim((string) ($data["nama_lengkap"] ?? ""));
        $kota = trim((string) ($data["kota"] ?? ""));
        $email = trim((string) ($data["email"] ?? ""));
        $password = (string) ($data["password"] ?? "");
        $confirmPassword = (string) ($data["confirm_password"] ?? "");
        $kebutuhan = trim((string) ($data["kebutuhan_utama"] ?? ""));
        $minat = is_array($data["minat"] ?? null) ? implode(", ", $data["minat"]) : "";

        if ($nama === "" || $kota === "" || $email === "" || $password === "") {
            Response::json(["success" => false, "message" => "Semua field utama wajib diisi."], 422);
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Response::json(["success" => false, "message" => "Format email tidak valid."], 422);
        }

        if (strlen($password) < 6) {
            Response::json(["success" => false, "message" => "Password minimal 6 karakter."], 422);
        }

        if ($password !== $confirmPassword) {
            Response::json(["success" => false, "message" => "Konfirmasi password tidak sama."], 422);
        }

        if (User::emailExists($this->conn, $email)) {
            Response::json(["success" => false, "message" => "Email sudah terdaftar."], 409);
        }

        $userId = User::create($this->conn, [
            "username" => strtok($email, "@") ?: $nama,
            "email" => $email,
            "password" => password_hash($password, PASSWORD_DEFAULT),
            "role" => "user",
            "nama_lengkap" => $nama,
            "kota" => $kota,
            "kebutuhan_utama" => $kebutuhan,
            "minat" => $minat
        ]);

        $_SESSION["user_id"] = $userId;
        Response::json(["success" => true, "message" => "Registrasi berhasil."], 201);
    }

    public function login(): void
    {
        $data = Request::jsonBody();
        $email = trim((string) ($data["email"] ?? ""));
        $password = (string) ($data["password"] ?? "");
        $remember = !empty($data["remember"]);

        if ($email === "" || $password === "") {
            Response::json(["success" => false, "message" => "Email dan password wajib diisi."], 422);
        }

        $user = User::findCredentialsByEmail($this->conn, $email);
        if (!$user || !password_verify($password, $user["password"])) {
            Response::json(["success" => false, "message" => "Email atau password salah."], 401);
        }

        $_SESSION["user_id"] = (int) $user["id"];
        if ($remember) {
            $this->issueRememberCookie((int) $user["id"]);
        }
        Response::json(["success" => true, "message" => "Login berhasil."]);
    }

    public function updateProfile(): void
    {
        $id = Auth::requireLogin();
        $data = Request::jsonBody();
        $nama = trim((string) ($data["nama_lengkap"] ?? ""));
        $email = trim((string) ($data["email"] ?? ""));
        $kota = trim((string) ($data["kota"] ?? ""));

        if ($nama === "" || $email === "" || $kota === "") {
            Response::json(["success" => false, "message" => "Nama, email, dan kota wajib diisi."], 422);
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Response::json(["success" => false, "message" => "Format email tidak valid."], 422);
        }

        if (User::emailExists($this->conn, $email, $id)) {
            Response::json(["success" => false, "message" => "Email sudah dipakai akun lain."], 409);
        }

        User::updateProfile($this->conn, $id, strtok($email, "@") ?: $nama, $email, $nama, $kota);
        Response::json(["success" => true, "message" => "Profil berhasil diperbarui."]);
    }

    public function logout(): void
    {
        if (!empty($_SESSION["user_id"])) {
            User::clearRememberToken($this->conn, (int) $_SESSION["user_id"]);
        }
        $this->forgetRememberCookie();
        $_SESSION = [];
        session_destroy();
        Response::json(["success" => true, "message" => "Logout berhasil."]);
    }

    private function issueRememberCookie(int $userId): void
    {
        $selector = bin2hex(random_bytes(12));
        $token = bin2hex(random_bytes(32));
        $expiresAt = time() + (60 * 60 * 24 * 30);
        $expiresSql = date("Y-m-d H:i:s", $expiresAt);

        User::saveRememberToken($this->conn, $userId, $selector, password_hash($token, PASSWORD_DEFAULT), $expiresSql);
        setcookie("webandoo_remember", $selector . ":" . $token, [
            "expires" => $expiresAt,
            "path" => "/",
            "secure" => !empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] !== "off",
            "httponly" => true,
            "samesite" => "Lax"
        ]);
    }

    private function restoreRememberedUser(): void
    {
        if (!empty($_SESSION["user_id"]) || empty($_COOKIE["webandoo_remember"])) {
            return;
        }

        $parts = explode(":", $_COOKIE["webandoo_remember"], 2);
        if (count($parts) !== 2) {
            $this->forgetRememberCookie();
            return;
        }

        [$selector, $token] = $parts;
        $row = User::findByRememberSelector($this->conn, $selector);

        if (!$row || strtotime($row["remember_expires"] ?? "") < time() || !password_verify($token, $row["remember_token"] ?? "")) {
            $this->forgetRememberCookie();
            return;
        }

        $_SESSION["user_id"] = (int) $row["id"];
    }

    private function forgetRememberCookie(): void
    {
        setcookie("webandoo_remember", "", [
            "expires" => time() - 3600,
            "path" => "/",
            "secure" => !empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] !== "off",
            "httponly" => true,
            "samesite" => "Lax"
        ]);
    }
}
