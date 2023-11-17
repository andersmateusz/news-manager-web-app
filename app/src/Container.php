<?php

declare(strict_types=1);

namespace Andersma\NewsManager;

use ArgumentCountError;
use InvalidArgumentException;
use LogicException;
use function class_exists;
use function get_class;
use function sprintf;

final class Container
{
    private static array $singletonInstances = [];

    /**
     * Get service by id or class-string.
     * If class of type class-string exists and is not registered, it will be initialized and registered as singleton.
     *
     * @param class-string|string $id
     */
    public static function get(string $id): object
    {
        if (isset(self::$singletonInstances[$id])) {
            return self::$singletonInstances[$id];
        } else if (class_exists($id)) {
            try {
                $instance = new $id();
                self::$singletonInstances[$id] = $instance;
                return $instance;
            } catch (ArgumentCountError) {
                throw new InvalidArgumentException(sprintf('Class "%s" cannot be initialized without constructor arguments', $id));
            }
        } else {
            throw new InvalidArgumentException(sprintf('Service with id "%s" is not registered and class with provided name does not exist', $id));
        }
    }

    /**
     * Register service as singleton by providing object and optional id.
     * If id is not provided, class-string of given object is used.
     *
     * @param $id null|string By default, class string of given object is used
     */
    public static function set(object $obj, ?string $id = null): void
    {
        $id ??= get_class($obj);

        if (!isset(self::$singletonInstances[$id])) {
            self::$singletonInstances[$id] = $obj;
        } else {
            throw new LogicException(sprintf('Provided "%s" service is already registered', $id));
        }
    }
}