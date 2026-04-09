<?php

class Database
{
    private mysqli $connection;

    public function __construct(mysqli $connection)
    {
        $this->connection = $connection;
    }

    public function getConnection(): mysqli
    {
        return $this->connection;
    }
}
