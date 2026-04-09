<?php

class TransactionRepository
{
    public function __construct(private mysqli $connection)
    {
    }

    public function getAll(): mysqli_result|false
    {
        return $this->connection->query('SELECT * FROM laporan ORDER BY tanggal DESC, id DESC');
    }

    public function findById(int $id): ?array
    {
        $statement = $this->connection->prepare('SELECT * FROM laporan WHERE id = ?');
        $statement->bind_param('i', $id);
        $statement->execute();
        $result = $statement->get_result();
        $data = $result->fetch_assoc() ?: null;
        $statement->close();

        return $data;
    }

    public function create(array $payload): void
    {
        $statement = $this->connection->prepare(
            'INSERT INTO laporan (tanggal, keterangan, jenis, jumlah, foto) VALUES (?, ?, ?, ?, ?)'
        );
        $statement->bind_param(
            'sssis',
            $payload['tanggal'],
            $payload['keterangan'],
            $payload['jenis'],
            $payload['jumlah'],
            $payload['foto']
        );
        $statement->execute();
        $statement->close();
    }

    public function update(int $id, array $payload): void
    {
        $statement = $this->connection->prepare(
            'UPDATE laporan SET tanggal = ?, keterangan = ?, jenis = ?, jumlah = ?, foto = ? WHERE id = ?'
        );
        $statement->bind_param(
            'sssisi',
            $payload['tanggal'],
            $payload['keterangan'],
            $payload['jenis'],
            $payload['jumlah'],
            $payload['foto'],
            $id
        );
        $statement->execute();
        $statement->close();
    }

    public function delete(int $id): void
    {
        $statement = $this->connection->prepare('DELETE FROM laporan WHERE id = ?');
        $statement->bind_param('i', $id);
        $statement->execute();
        $statement->close();
    }

    public function getSummary(): array
    {
        $query = $this->connection->query(
            "SELECT
                SUM(CASE WHEN jenis = 'Pemasukan' THEN jumlah ELSE 0 END) AS total_masuk,
                SUM(CASE WHEN jenis = 'Pengeluaran' THEN jumlah ELSE 0 END) AS total_keluar
            FROM laporan"
        );

        return $query ? ($query->fetch_assoc() ?: ['total_masuk' => 0, 'total_keluar' => 0]) : ['total_masuk' => 0, 'total_keluar' => 0];
    }
}
