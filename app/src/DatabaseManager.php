<?php

declare(strict_types=1);

namespace Andersma\NewsManager;

use PDO;
use PDOException;
use RuntimeException;
use SensitiveParameter;
use function sprintf;

final class DatabaseManager
{
    private PDO $pdo;
    public function __construct(
        string $host,
        string $username,
        #[SensitiveParameter]
        string $password,
        string $port,
        string $dbname,
        string $charset,
        string $driver,
    ) {
        try {
            $dsn = sprintf('%s:host=%s;port=%s;dbname=%s;charset=%s;', $driver, $host, $port, $dbname, $charset);
            $this->pdo = new PDO($dsn, $username, $password);
        } catch (PDOException $e) {
            throw new RuntimeException('Could not connect to the database', 0, $e);
        }
    }

    public function getPdo(): PDO
    {
        return $this->pdo;
    }
}