<?php

declare(strict_types=1);

namespace App\Repositories;

use mysqli;

// Repository ini menangani seluruh operasi data pada tabel user.
final class UserRepository
{
    // Nama tabel utama yang dipakai aplikasi.
    private string $table = "user";

    // Menerima object koneksi database dari luar class.
    public function __construct(private mysqli $connection)
    {
    }

    // Mengambil daftar semua kolom yang ada pada tabel user.
    public function getColumns(): array
    {
        $columns = [];
        $query = mysqli_query($this->connection, "SHOW COLUMNS FROM {$this->table}");

        if ($query) {
            while ($column = mysqli_fetch_assoc($query)) {
                $columns[] = $column["Field"];
            }
        }

        return $columns;
    }

    // Mengecek apakah suatu kolom tersedia di tabel user.
    public function hasColumn(string $column): bool
    {
        return in_array($column, $this->getColumns(), true);
    }

    // Memastikan kolom foto tersedia sebelum fitur upload digunakan.
    public function ensureFotoColumn(): void
    {
        if ($this->hasColumn("foto")) {
            return;
        }

        mysqli_query($this->connection, "ALTER TABLE {$this->table} ADD COLUMN foto VARCHAR(255) NULL");
    }

    // Mengambil satu user berdasarkan email.
    public function findByEmail(string $email): ?array
    {
        $statement = mysqli_prepare($this->connection, "SELECT * FROM {$this->table} WHERE email = ? LIMIT 1");
        if (!$statement) {
            return null;
        }

        mysqli_stmt_bind_param($statement, "s", $email);
        mysqli_stmt_execute($statement);
        $result = mysqli_stmt_get_result($statement);
        $user = $result ? mysqli_fetch_assoc($result) : null;
        mysqli_stmt_close($statement);

        return $user ?: null;
    }

    // Mengambil user berdasarkan kombinasi email, password, dan role.
    public function findByCredentials(string $email, string $passwordHash, string $role): ?array
    {
        if ($this->hasColumn("role")) {
            $statement = mysqli_prepare($this->connection, "SELECT * FROM {$this->table} WHERE email = ? AND password = ? AND role = ? LIMIT 1");
            if (!$statement) {
                return null;
            }

            mysqli_stmt_bind_param($statement, "sss", $email, $passwordHash, $role);
        } else {
            $statement = mysqli_prepare($this->connection, "SELECT * FROM {$this->table} WHERE email = ? AND password = ? LIMIT 1");
            if (!$statement) {
                return null;
            }

            mysqli_stmt_bind_param($statement, "ss", $email, $passwordHash);
        }

        mysqli_stmt_execute($statement);
        $result = mysqli_stmt_get_result($statement);
        $user = $result ? mysqli_fetch_assoc($result) : null;
        mysqli_stmt_close($statement);

        return $user ?: null;
    }

    // Menambahkan akun user baru ke database.
    public function createUser(string $username, string $email, string $passwordHash, string $role): array
    {
        if ($this->hasColumn("role")) {
            $statement = mysqli_prepare($this->connection, "INSERT INTO {$this->table} (username, email, password, role) VALUES (?, ?, ?, ?)");
            if (!$statement) {
                return [false, "Gagal menyiapkan query: " . mysqli_error($this->connection)];
            }

            mysqli_stmt_bind_param($statement, "ssss", $username, $email, $passwordHash, $role);
        } else {
            $statement = mysqli_prepare($this->connection, "INSERT INTO {$this->table} (username, email, password) VALUES (?, ?, ?)");
            if (!$statement) {
                return [false, "Gagal menyiapkan query: " . mysqli_error($this->connection)];
            }

            mysqli_stmt_bind_param($statement, "sss", $username, $email, $passwordHash);
        }

        $success = mysqli_stmt_execute($statement);
        $message = $success ? "Registrasi berhasil." : "Registrasi gagal: " . mysqli_stmt_error($statement);
        mysqli_stmt_close($statement);

        return [$success, $message];
    }

    // Menentukan nama kolom-kolom yang dipakai untuk fitur peserta.
    public function getParticipantSchema(): array
    {
        $columns = $this->getColumns();

        return [
            "id" => in_array("id", $columns, true) ? "id" : (in_array("id_user", $columns, true) ? "id_user" : null),
            "nama" => in_array("nama", $columns, true) ? "nama" : (in_array("username", $columns, true) ? "username" : null),
            "umur" => in_array("umur", $columns, true) ? "umur" : (in_array("usia", $columns, true) ? "usia" : null),
            "jenis_kelamin" => in_array("jenis_kelamin", $columns, true) ? "jenis_kelamin" : (in_array("jenisKelamin", $columns, true) ? "jenisKelamin" : null),
            "pelatihan" => in_array("pelatihan", $columns, true) ? "pelatihan" : null,
            "foto" => in_array("foto", $columns, true) ? "foto" : null,
        ];
    }

    // Mengecek apakah struktur tabel sudah cukup untuk mengelola peserta.
    public function canManageParticipants(): bool
    {
        $schema = $this->getParticipantSchema();

        return $schema["id"] && $schema["nama"] && $schema["umur"] && $schema["jenis_kelamin"] && $schema["pelatihan"];
    }

    // Mengambil seluruh data peserta yang siap ditampilkan.
    public function getParticipants(): array
    {
        $schema = $this->getParticipantSchema();
        if (!$this->canManageParticipants()) {
            return [];
        }

        $selectFoto = $schema["foto"] ? ", {$schema["foto"]} AS foto" : ", '' AS foto";
        $sql = "SELECT
            {$schema["id"]} AS id,
            {$schema["nama"]} AS nama,
            {$schema["umur"]} AS umur,
            {$schema["jenis_kelamin"]} AS jenis_kelamin,
            {$schema["pelatihan"]} AS pelatihan
            {$selectFoto}
            FROM {$this->table}
            WHERE {$schema["nama"]} IS NOT NULL AND TRIM({$schema["nama"]}) <> ''
            AND {$schema["umur"]} IS NOT NULL
            AND {$schema["jenis_kelamin"]} IS NOT NULL AND TRIM({$schema["jenis_kelamin"]}) <> ''
            AND {$schema["pelatihan"]} IS NOT NULL AND TRIM({$schema["pelatihan"]}) <> ''
            ORDER BY {$schema["id"]} ASC";

        $rows = [];
        $query = mysqli_query($this->connection, $sql);
        if ($query) {
            while ($row = mysqli_fetch_assoc($query)) {
                $rows[] = $row;
            }
        }

        return $rows;
    }

    // Menyimpan data peserta baru ke database.
    public function createParticipant(array $data): array
    {
        $schema = $this->getParticipantSchema();
        $columns = [$schema["nama"], $schema["umur"], $schema["jenis_kelamin"], $schema["pelatihan"]];
        $values = [$data["nama"], (int)$data["umur"], $data["jenis_kelamin"], $data["pelatihan"]];
        $types = "siss";

        if ($schema["foto"]) {
            $columns[] = $schema["foto"];
            $values[] = $data["foto"];
            $types .= "s";
        }

        $statement = mysqli_prepare(
            $this->connection,
            "INSERT INTO {$this->table} (" . implode(", ", $columns) . ") VALUES (" . implode(", ", array_fill(0, count($columns), "?")) . ")"
        );

        if (!$statement) {
            return [false, "Prepare query gagal: " . mysqli_error($this->connection)];
        }

        $this->bindDynamic($statement, $types, $values);
        $success = mysqli_stmt_execute($statement);
        $message = $success ? "Data peserta berhasil ditambahkan." : "Gagal menyimpan data: " . mysqli_stmt_error($statement);
        mysqli_stmt_close($statement);

        return [$success, $message];
    }

    // Memperbarui data peserta yang sudah ada.
    public function updateParticipant(array $data): array
    {
        $schema = $this->getParticipantSchema();
        $sql = "UPDATE {$this->table} SET {$schema["nama"]} = ?, {$schema["umur"]} = ?, {$schema["jenis_kelamin"]} = ?, {$schema["pelatihan"]} = ?";
        $values = [$data["nama"], (int)$data["umur"], $data["jenis_kelamin"], $data["pelatihan"]];
        $types = "siss";

        if ($schema["foto"]) {
            $sql .= ", {$schema["foto"]} = ?";
            $values[] = $data["foto"];
            $types .= "s";
        }

        $sql .= " WHERE {$schema["id"]} = ? LIMIT 1";
        $values[] = (int)$data["id"];
        $types .= "i";

        $statement = mysqli_prepare($this->connection, $sql);
        if (!$statement) {
            return [false, "Prepare query edit gagal: " . mysqli_error($this->connection)];
        }

        $this->bindDynamic($statement, $types, $values);
        $success = mysqli_stmt_execute($statement);
        $message = $success ? "Data peserta berhasil diperbarui." : "Gagal memperbarui data: " . mysqli_stmt_error($statement);
        mysqli_stmt_close($statement);

        return [$success, $message];
    }

    // Menghapus data peserta berdasarkan id.
    public function deleteParticipant(int $id): array
    {
        $schema = $this->getParticipantSchema();
        $statement = mysqli_prepare($this->connection, "DELETE FROM {$this->table} WHERE {$schema["id"]} = ? LIMIT 1");

        if (!$statement) {
            return [false, "Prepare query hapus gagal: " . mysqli_error($this->connection)];
        }

        mysqli_stmt_bind_param($statement, "i", $id);
        $success = mysqli_stmt_execute($statement);
        $message = $success ? "Data peserta berhasil dihapus." : "Gagal menghapus data: " . mysqli_stmt_error($statement);
        mysqli_stmt_close($statement);

        return [$success, $message];
    }

    // Helper untuk bind parameter dinamis pada prepared statement.
    private function bindDynamic(\mysqli_stmt $statement, string $types, array &$values): void
    {
        $params = [$statement, $types];

        foreach ($values as $key => $value) {
            $params[] = &$values[$key];
        }

        call_user_func_array("mysqli_stmt_bind_param", $params);
    }
}
