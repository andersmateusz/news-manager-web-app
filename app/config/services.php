<?php

declare(strict_types=1);


use Andersma\NewsManager\Container;
use Andersma\NewsManager\DatabaseManager;
use Andersma\NewsManager\Router;

Container::set(new Router());

$dbParams = parse_ini_file(__DIR__ . '/params.ini', true)['DATABASE'];

Container::set(
    new DatabaseManager(
        $dbParams['host'],
        $dbParams['username'],
        $dbParams['password'],
        $dbParams['port'],
        $dbParams['db_name'],
        $dbParams['charset'],
        $dbParams['driver'],
    )
);
