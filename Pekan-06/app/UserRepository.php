<?php

class UserRepository
{
    private mysqli $conn;

    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;
    }

    public function emailExists(string $email): bool
    {
        $stmt = $this->conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();
        $exists = $stmt->num_rows > 0;
        $stmt->close();

        return $exists;
    }

    public function create(string $name, string $email, string $hashedPassword, string $role): int
    {
        $stmt = $this->conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('ssss', $name, $email, $hashedPassword, $role);
        $stmt->execute();
        $id = (int) $this->conn->insert_id;
        $stmt->close();

        return $id;
    }

    public function findByEmailAndRole(string $email, string $role): ?array
    {
        $stmt = $this->conn->prepare("SELECT id, name, email, password, role FROM users WHERE email = ? AND role = ? LIMIT 1");
        $stmt->bind_param('ss', $email, $role);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result ? $result->fetch_assoc() : null;
        $stmt->close();

        return $user ?: null;
    }
}
