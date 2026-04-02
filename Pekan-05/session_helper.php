<?php
// Jika session belum aktif, mulai session sekarang.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Nama cookie remember me.
define("REMEMBER_COOKIE_NAME", "inkluskill_remember");
// Lama remember me aktif dalam hari.
define("REMEMBER_COOKIE_DAYS", 7);
// Secret key sederhana untuk membuat signature cookie.
define("REMEMBER_SECRET_KEY", "inkluskill_pekan5_secret_key");

// Mengambil identifier user yang dipakai untuk remember me.
function getUserIdentifier(array $user): string
{
    return (string)($user["email"] ?? "");
}

// Membuat signature aman dari data user dan waktu expired.
function buildRememberSignature(array $user, int $expiresAt): string
{
    $identifier = getUserIdentifier($user);
    $passwordHash = (string)($user["password"] ?? "");
    $role = (string)($user["role"] ?? "");
    $data = $identifier . "|" . $role . "|" . $expiresAt;

    return hash_hmac("sha256", $data, REMEMBER_SECRET_KEY . $passwordHash);
}

// Menyimpan cookie remember me ke browser.
function setRememberMeCookie(array $user): void
{
    $identifier = getUserIdentifier($user);
    if ($identifier === "") {
        return;
    }

    $expiresAt = time() + (REMEMBER_COOKIE_DAYS * 24 * 60 * 60);
    $signature = buildRememberSignature($user, $expiresAt);
    $payload = json_encode([
        "identifier" => $identifier,
        "expires_at" => $expiresAt,
        "signature" => $signature,
    ]);

    setcookie(
        REMEMBER_COOKIE_NAME,
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
function clearRememberMeCookie(): void
{
    setcookie(
        REMEMBER_COOKIE_NAME,
        "",
        [
            "expires" => time() - 3600,
            "path" => "/",
            "httponly" => true,
            "samesite" => "Lax",
        ]
    );
}

// Menyimpan data user ke session dan cookie bila diperlukan.
function loginUser(array $user, bool $rememberMe = false): void
{
    $_SESSION["user"] = $user["email"] ?? "";
    $_SESSION["role"] = $user["role"] ?? "";
    $_SESSION["username"] = $user["username"] ?? ($user["nama"] ?? "");

    if ($rememberMe) {
        setRememberMeCookie($user);
    } else {
        clearRememberMeCookie();
    }
}

// Mencari user berdasarkan email sebagai identifier.
function findUserByIdentifier(mysqli $koneksi, string $identifier): ?array
{
    $sql = "SELECT * FROM user WHERE email = ? LIMIT 1";
    $stmt = mysqli_prepare($koneksi, $sql);
    if (!$stmt) {
        return null;
    }

    mysqli_stmt_bind_param($stmt, "s", $identifier);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = $result ? mysqli_fetch_assoc($result) : null;
    mysqli_stmt_close($stmt);

    return $user ?: null;
}

// Mengembalikan login user dari cookie remember me.
function restoreRememberedUser(mysqli $koneksi): bool
{
    if (!empty($_SESSION["user"])) {
        return true;
    }

    if (empty($_COOKIE[REMEMBER_COOKIE_NAME])) {
        return false;
    }

    $decoded = base64_decode($_COOKIE[REMEMBER_COOKIE_NAME], true);
    if ($decoded === false) {
        clearRememberMeCookie();
        return false;
    }

    $data = json_decode($decoded, true);
    if (!is_array($data)) {
        clearRememberMeCookie();
        return false;
    }

    $identifier = trim((string)($data["identifier"] ?? ""));
    $expiresAt = (int)($data["expires_at"] ?? 0);
    $signature = (string)($data["signature"] ?? "");

    if ($identifier === "" || $expiresAt < time() || $signature === "") {
        clearRememberMeCookie();
        return false;
    }

    $user = findUserByIdentifier($koneksi, $identifier);
    if (!$user) {
        clearRememberMeCookie();
        return false;
    }

    $expectedSignature = buildRememberSignature($user, $expiresAt);
    if (!hash_equals($expectedSignature, $signature)) {
        clearRememberMeCookie();
        return false;
    }

    loginUser($user, true);
    return true;
}

// Menghapus seluruh session dan cookie login user.
function logoutUser(): void
{
    $_SESSION = [];

    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), "", time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
    }

    session_destroy();
    clearRememberMeCookie();
}
?>
