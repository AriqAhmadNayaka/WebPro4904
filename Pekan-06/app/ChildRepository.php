<?php

class ChildRepository
{
    private mysqli $conn;

    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;
        $this->ensureSchema();
    }

    public function ensureSchema(): void
    {
        $requiredColumns = [
            'user_id' => "ALTER TABLE anak ADD COLUMN user_id INT NULL AFTER id",
            'foto' => "ALTER TABLE anak ADD COLUMN foto VARCHAR(255) NULL AFTER catatan",
        ];

        foreach ($requiredColumns as $column => $sql) {
            $check = $this->conn->query("SHOW COLUMNS FROM anak LIKE '{$column}'");
            if ($check && $check->num_rows === 0) {
                $this->conn->query($sql);
            }
        }
    }

    public function allByUser(int $userId): array
    {
        $stmt = $this->conn->prepare("SELECT * FROM anak WHERE user_id = ? ORDER BY id DESC");
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $rows = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
        $stmt->close();

        return $rows;
    }

    public function adoptLegacyRowsForUser(int $userId): void
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) AS total FROM anak WHERE user_id = ?");
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $owned = $result ? (int) (($result->fetch_assoc()['total'] ?? 0)) : 0;
        $stmt->close();

        if ($owned > 0) {
            return;
        }

        $this->conn->query("UPDATE anak SET user_id = {$userId} WHERE user_id IS NULL");
    }

    public function firstByUser(int $userId): ?array
    {
        $stmt = $this->conn->prepare("SELECT * FROM anak WHERE user_id = ? ORDER BY id DESC LIMIT 1");
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result ? $result->fetch_assoc() : null;
        $stmt->close();

        return $row ?: null;
    }

    public function findByIdAndUser(int $id, int $userId): ?array
    {
        $stmt = $this->conn->prepare("SELECT * FROM anak WHERE id = ? AND user_id = ? LIMIT 1");
        $stmt->bind_param('ii', $id, $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result ? $result->fetch_assoc() : null;
        $stmt->close();

        return $row ?: null;
    }

    public function save(array $payload): int
    {
        $id = (int) ($payload['id'] ?? 0);

        if ($id > 0) {
            $stmt = $this->conn->prepare(
                "UPDATE anak
                 SET nama = ?, gender = ?, tanggal_lahir = ?, kelas = ?, alamat = ?, catatan = ?, foto = ?
                 WHERE id = ? AND user_id = ?"
            );
            $stmt->bind_param(
                'sssssssii',
                $payload['nama'],
                $payload['gender'],
                $payload['tanggal_lahir'],
                $payload['kelas'],
                $payload['alamat'],
                $payload['catatan'],
                $payload['foto'],
                $id,
                $payload['user_id']
            );
            $stmt->execute();
            $stmt->close();

            return $id;
        }

        $stmt = $this->conn->prepare(
            "INSERT INTO anak (user_id, nama, gender, tanggal_lahir, kelas, alamat, catatan, foto)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->bind_param(
            'isssssss',
            $payload['user_id'],
            $payload['nama'],
            $payload['gender'],
            $payload['tanggal_lahir'],
            $payload['kelas'],
            $payload['alamat'],
            $payload['catatan'],
            $payload['foto']
        );
        $stmt->execute();
        $newId = (int) $this->conn->insert_id;
        $stmt->close();

        return $newId;
    }

    public function delete(int $id, int $userId): bool
    {
        $stmt = $this->conn->prepare("DELETE FROM anak WHERE id = ? AND user_id = ?");
        $stmt->bind_param('ii', $id, $userId);
        $stmt->execute();
        $affected = $stmt->affected_rows > 0;
        $stmt->close();

        return $affected;
    }
}
