<?php

class ContentBlock
{
    public static function ensureTable(mysqli $conn): void
    {
        $sql = "CREATE TABLE IF NOT EXISTS content_blocks (
            id INT AUTO_INCREMENT PRIMARY KEY,
            page VARCHAR(80) NOT NULL,
            title VARCHAR(150) NOT NULL,
            body TEXT NOT NULL,
            image VARCHAR(255) DEFAULT NULL,
            link_url VARCHAR(255) DEFAULT NULL,
            link_label VARCHAR(80) DEFAULT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_content_blocks_page (page)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";

        if (!mysqli_query($conn, $sql)) {
            Response::json(["success" => false, "message" => "Gagal menyiapkan tabel konten: " . mysqli_error($conn)], 500);
        }
    }

    public static function normalize(array $row): array
    {
        return [
            "id" => (int) $row["id"],
            "page" => $row["page"],
            "title" => $row["title"],
            "body" => $row["body"],
            "image" => $row["image"],
            "linkUrl" => $row["link_url"],
            "linkLabel" => $row["link_label"]
        ];
    }

    public static function byPage(mysqli $conn, string $page): array
    {
        $stmt = mysqli_prepare($conn, "SELECT * FROM content_blocks WHERE page = ? OR page = 'all' ORDER BY created_at DESC, id DESC");
        mysqli_stmt_bind_param($stmt, "s", $page);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $items = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $items[] = self::normalize($row);
        }

        return $items;
    }

    public static function create(mysqli $conn, array $payload): int
    {
        $stmt = mysqli_prepare($conn, "INSERT INTO content_blocks (page, title, body, image, link_url, link_label) VALUES (?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "ssssss", $payload["page"], $payload["title"], $payload["body"], $payload["image"], $payload["linkUrl"], $payload["linkLabel"]);

        if (!mysqli_stmt_execute($stmt)) {
            Response::json(["success" => false, "message" => "Konten gagal disimpan: " . mysqli_error($conn)], 500);
        }

        return mysqli_insert_id($conn);
    }

    public static function delete(mysqli $conn, int $id): void
    {
        $stmt = mysqli_prepare($conn, "DELETE FROM content_blocks WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
    }
}
