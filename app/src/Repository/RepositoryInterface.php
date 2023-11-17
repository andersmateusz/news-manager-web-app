<?php

declare(strict_types=1);

namespace Andersma\NewsManager\Repository;

interface RepositoryInterface
{
    public static function tableName(): string;
}