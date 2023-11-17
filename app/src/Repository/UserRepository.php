<?php

declare(strict_types=1);

namespace Andersma\NewsManager\Repository;

use Andersma\NewsManager\Container;
use Andersma\NewsManager\DatabaseManager;
use Andersma\NewsManager\Entity\User;
use PDO;
use function sprintf;

class UserRepository implements RepositoryInterface
{
    private DatabaseManager $dbManager;

    public function __construct()
    {
        $this->dbManager = Container::get(DatabaseManager::class);
    }

    public static function tableName(): string
    {
        return 'users';
    }

    public function find(string $username): ?User
    {
        $sql = sprintf('SELECT * FROM %s WHERE username = :username', self::tableName());

        $statement = $this->dbManager->getPdo()->prepare($sql);
        $statement->execute([':username' => $username]);
        $statement->setFetchMode(PDO::FETCH_CLASS, User::class);
        return $statement->fetch() ?: null;
    }


}