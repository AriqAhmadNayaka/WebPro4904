<?php

class User
{
    public static function ensureTable(mysqli $conn): void
    {
        $sql = "CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(100) NOT NULL,
            email VARCHAR(100) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            role ENUM('user','admin') NOT NULL DEFAULT 'user',
            nama_lengkap VARCHAR(100) DEFAULT NULL,
            kota VARCHAR(100) DEFAULT NULL,
            kebutuhan_utama VARCHAR(150) DEFAULT NULL,
            minat TEXT DEFAULT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";

        if (!mysqli_query($conn, $sql)) {
            Response::json([
                "success" => false,
                "message" => "Gagal menyiapkan tabel users: " . mysqli_error($conn)
            ], 500);
        }

        self::ensureColumn($conn, "role", "`role` ENUM('user','admin') NOT NULL DEFAULT 'user' AFTER `password`");
        self::ensureColumn($conn, "kebutuhan_utama", "`kebutuhan_utama` VARCHAR(150) DEFAULT NULL");
        self::ensureColumn($conn, "minat", "`minat` TEXT DEFAULT NULL");
        self::ensureColumn($conn, "remember_selector", "`remember_selector` VARCHAR(64) DEFAULT NULL");
        self::ensureColumn($conn, "remember_token", "`remember_token` VARCHAR(255) DEFAULT NULL");
        self::ensureColumn($conn, "remember_expires", "`remember_expires` DATETIME DEFAULT NULL");
        self::ensureColumn($conn, "created_at", "`created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP");
        self::ensureColumn($conn, "updated_at", "`updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP");
        self::seedAdmin($conn);
    }

    private static function seedAdmin(mysqli $conn): void
    {
        if (self::emailExists($conn, "admin@webandoo.test")) {
            return;
        }

        self::create($conn, [
            "username" => "admin",
            "email" => "admin@webandoo.test",
            "password" => '$2y$10$lNCz9lFfpM/181OFr5okbeg41Bk8pXMgMqkItwlXY/8Krr/l6U6Ze',
            "role" => "admin",
            "nama_lengkap" => "Administrator WeBandoo+",
            "kota" => "Bandung",
            "kebutuhan_utama" => "Mengelola data lokasi dan review",
            "minat" => "Admin, Moderasi"
        ]);
    }

    private static function ensureColumn(mysqli $conn, string $column, string $definition): void
    {
        $safeColumn = mysqli_real_escape_string($conn, $column);
        $result = mysqli_query($conn, "SHOW COLUMNS FROM `users` LIKE '$safeColumn'");
        if ($result && mysqli_num_rows($result) === 0) {
            mysqli_query($conn, "ALTER TABLE `users` ADD COLUMN $definition");
        }
    }

    public static function normalize(array $row): array
    {
        return [
            "id" => (int) $row["id"],
            "username" => $row["username"],
            "email" => $row["email"],
            "role" => $row["role"] ?? "user",
            "nama_lengkap" => $row["nama_lengkap"] ?? "",
            "kota" => $row["kota"] ?? "",
            "kebutuhan_utama" => $row["kebutuhan_utama"] ?? "",
            "minat" => $row["minat"] ?? ""
        ];
    }

    public static function findById(mysqli $conn, int $id): ?array
    {
        $stmt = mysqli_prepare($conn, "SELECT id, username, email, role, nama_lengkap, kota, kebutuhan_utama, minat FROM users WHERE id = ? LIMIT 1");
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = $result ? mysqli_fetch_assoc($result) : null;

        return $row ? self::normalize($row) : null;
    }

    public static function findCredentialsByEmail(mysqli $conn, string $email): ?array
    {
        $stmt = mysqli_prepare($conn, "SELECT id, password FROM users WHERE email = ? LIMIT 1");
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = $result ? mysqli_fetch_assoc($result) : null;

        return $row ?: null;
    }

    public static function findByRememberSelector(mysqli $conn, string $selector): ?array
    {
        $stmt = mysqli_prepare($conn, "SELECT id, remember_token, remember_expires FROM users WHERE remember_selector = ? LIMIT 1");
        mysqli_stmt_bind_param($stmt, "s", $selector);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = $result ? mysqli_fetch_assoc($result) : null;

        return $row ?: null;
    }

    public static function emailExists(mysqli $conn, string $email, ?int $exceptId = null): bool
    {
        if ($exceptId) {
            $stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ? AND id != ? LIMIT 1");
            mysqli_stmt_bind_param($stmt, "si", $email, $exceptId);
        } else {
            $stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ? LIMIT 1");
            mysqli_stmt_bind_param($stmt, "s", $email);
        }

        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return $result && mysqli_num_rows($result) > 0;
    }

    public static function create(mysqli $conn, array $data): int
    {
        $stmt = mysqli_prepare($conn, "INSERT INTO users (username, email, password, role, nama_lengkap, kota, kebutuhan_utama, minat) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param(
            $stmt,
            "ssssssss",
            $data["username"],
            $data["email"],
            $data["password"],
            $data["role"],
            $data["nama_lengkap"],
            $data["kota"],
            $data["kebutuhan_utama"],
            $data["minat"]
        );

        if (!mysqli_stmt_execute($stmt)) {
            Response::json(["success" => false, "message" => "Registrasi gagal: " . mysqli_error($conn)], 500);
        }

        return mysqli_insert_id($conn);
    }

    public static function updateProfile(mysqli $conn, int $id, string $username, string $email, string $nama, string $kota): void
    {
        $stmt = mysqli_prepare($conn, "UPDATE users SET username = ?, email = ?, nama_lengkap = ?, kota = ? WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "ssssi", $username, $email, $nama, $kota, $id);

        if (!mysqli_stmt_execute($stmt)) {
            Response::json(["success" => false, "message" => "Profil gagal diperbarui: " . mysqli_error($conn)], 500);
        }
    }

    public static function saveRememberToken(mysqli $conn, int $id, string $selector, string $tokenHash, string $expires): void
    {
        $stmt = mysqli_prepare($conn, "UPDATE users SET remember_selector = ?, remember_token = ?, remember_expires = ? WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "sssi", $selector, $tokenHash, $expires, $id);
        mysqli_stmt_execute($stmt);
    }

    public static function clearRememberToken(mysqli $conn, int $id): void
    {
        $stmt = mysqli_prepare($conn, "UPDATE users SET remember_selector = NULL, remember_token = NULL, remember_expires = NULL WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
    }
}
