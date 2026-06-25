<?php

class Review
{
    public static function ensureTable(mysqli $conn): void
    {
        $sql = "CREATE TABLE IF NOT EXISTS place_reviews (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NULL,
            place_name VARCHAR(150) NOT NULL,
            reviewer_name VARCHAR(100) NOT NULL,
            rating TINYINT UNSIGNED NOT NULL,
            visit_date DATE NULL,
            comment TEXT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_place_name (place_name),
            INDEX idx_user_id (user_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";

        if (!mysqli_query($conn, $sql)) {
            Response::json([
                "success" => false,
                "message" => "Gagal menyiapkan tabel review: " . mysqli_error($conn)
            ], 500);
        }

        $checkColumn = mysqli_query($conn, "SHOW COLUMNS FROM place_reviews LIKE 'user_id'");
        if ($checkColumn && mysqli_num_rows($checkColumn) === 0) {
            mysqli_query($conn, "ALTER TABLE place_reviews ADD COLUMN user_id INT NULL AFTER id");
        }
    }

    public static function normalize(array $row): array
    {
        return [
            "id" => (string) $row["id"],
            "userId" => isset($row["user_id"]) ? (int) $row["user_id"] : null,
            "place" => $row["place_name"],
            "name" => $row["reviewer_name"],
            "rating" => (int) $row["rating"],
            "visitDate" => $row["visit_date"],
            "comment" => $row["comment"],
            "createdAt" => $row["created_at"],
            "updatedAt" => $row["updated_at"]
        ];
    }

    public static function all(mysqli $conn): array
    {
        $result = mysqli_query($conn, "SELECT * FROM place_reviews ORDER BY created_at DESC, id DESC");
        if (!$result) {
            Response::json(["success" => false, "message" => "Gagal mengambil data review: " . mysqli_error($conn)], 500);
        }

        $reviews = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $reviews[] = self::normalize($row);
        }

        return $reviews;
    }

    public static function byUser(mysqli $conn, int $userId): array
    {
        $stmt = mysqli_prepare($conn, "SELECT * FROM place_reviews WHERE user_id = ? ORDER BY created_at DESC, id DESC");
        mysqli_stmt_bind_param($stmt, "i", $userId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $reviews = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $reviews[] = self::normalize($row);
        }

        return $reviews;
    }

    public static function find(mysqli $conn, int $id): ?array
    {
        $stmt = mysqli_prepare($conn, "SELECT * FROM place_reviews WHERE id = ? LIMIT 1");
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = $result ? mysqli_fetch_assoc($result) : null;

        return $row ? self::normalize($row) : null;
    }

    public static function create(mysqli $conn, ?int $userId, array $payload): int
    {
        $stmt = mysqli_prepare($conn, "INSERT INTO place_reviews (user_id, place_name, reviewer_name, rating, visit_date, comment) VALUES (?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "ississ", $userId, $payload["place"], $payload["name"], $payload["rating"], $payload["visitDate"], $payload["comment"]);

        if (!mysqli_stmt_execute($stmt)) {
            Response::json(["success" => false, "message" => "Gagal menambah review: " . mysqli_error($conn)], 500);
        }

        return mysqli_insert_id($conn);
    }

    public static function update(mysqli $conn, int $id, array $payload): void
    {
        $stmt = mysqli_prepare($conn, "UPDATE place_reviews SET place_name = ?, reviewer_name = ?, rating = ?, visit_date = ?, comment = ? WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "ssissi", $payload["place"], $payload["name"], $payload["rating"], $payload["visitDate"], $payload["comment"], $id);

        if (!mysqli_stmt_execute($stmt)) {
            Response::json(["success" => false, "message" => "Gagal mengupdate review: " . mysqli_error($conn)], 500);
        }
    }

    public static function delete(mysqli $conn, int $id): void
    {
        $stmt = mysqli_prepare($conn, "DELETE FROM place_reviews WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $id);

        if (!mysqli_stmt_execute($stmt)) {
            Response::json(["success" => false, "message" => "Gagal menghapus review: " . mysqli_error($conn)], 500);
        }
    }
}
