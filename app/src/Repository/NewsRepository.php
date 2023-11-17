<?php

declare(strict_types=1);

namespace Andersma\NewsManager\Repository;

use Andersma\NewsManager\Container;
use Andersma\NewsManager\DatabaseManager;
use Andersma\NewsManager\Entity\News;
use PDO;
use function sprintf;

final readonly class NewsRepository implements RepositoryInterface
{
    private DatabaseManager $dbManager;

    public function __construct()
    {
        $this->dbManager = Container::get(DatabaseManager::class);
    }

    public static function tableName(): string
    {
        return 'news';
    }

    public function delete(int $id): bool
    {
        return false !== $this->dbManager->getPdo()->exec(sprintf('DELETE FROM %s WHERE id = %s', self::tableName(), $id));
    }

    public function find(int $id): ?News
    {
        /** @var DatabaseManager $dbManager */
        $dbManager = Container::get(DatabaseManager::class);
        $sql = sprintf('SELECT * FROM %s WHERE id = :id', self::tableName());
        $statement = $dbManager->getPdo()->prepare($sql);
        $statement->execute([':id' => $id]);
        $statement->setFetchMode(PDO::FETCH_CLASS, News::class);
        return $statement->fetch() ?: null;
    }

    public function update(News $news): bool
    {
        $sql = sprintf(
            'UPDATE %s SET title = :title, description = :description WHERE id = :id',
            self::tableName(),
        );
        $statement = $this->dbManager->getPdo()->prepare($sql);
        return $statement->execute([
            ':title' => $news->getTitle(),
            ':description' => $news->getDescription(),
            ':id' => $news->getId(),
        ]);
    }

    /** @return News[] */
    public function list(): array
    {
        return $this->dbManager
            ->getPdo()
            ->query(sprintf('SELECT * FROM %s ORDER BY id DESC;', self::tableName()))
            ?->fetchAll(PDO::FETCH_CLASS, News::class) ?? [];
    }

    public function create(News $news): bool
    {
        /** @var DatabaseManager $dbManager */
        $dbManager = Container::get(DatabaseManager::class);
        $sql = sprintf(
            'INSERT INTO %s (title, description) VALUES (:title, :description)',
            self::tableName(),
        );
        return $dbManager
            ->getPdo()
            ->prepare($sql)
            ?->execute([
                ':title' => $news->getTitle(),
                ':description' => $news->getDescription(),
            ])
        ;
    }
}
