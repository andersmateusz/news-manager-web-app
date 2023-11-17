<?php

declare(strict_types=1);

namespace Andersma\NewsManager;

use ReflectionEnum;
use ReflectionException;

trait EnumFromNameTrait
{
    /**
     * @throws ReflectionException
     */
    public static function tryFromName(string $name): ?static
    {
        $reflection = new ReflectionEnum(static::class);

        return $reflection->hasCase($name)
            ? $reflection->getCase($name)->getValue()
            : null;
    }
}