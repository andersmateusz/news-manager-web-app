<?php

declare(strict_types=1);

$dbParams = parse_ini_file(__DIR__ . '/params.ini', true)['DATABASE'];
$pdo = new PDO(
    sprintf('%s:host=%s;port=%s;charset=%s;', $dbParams['driver'], $dbParams['host'], $dbParams['port'], $dbParams['charset']),
    $dbParams['username'],
    $dbParams['password'],
);

$pdo->exec(sprintf('CREATE DATABASE IF NOT EXISTS %s', 'app'));
$pdo->exec(
    <<<SQL
USE app;
CREATE TABLE IF NOT EXISTS news(
    id INT NOT NULL AUTO_INCREMENT AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    PRIMARY KEY (id)
)
SQL
);

$pdo->exec(
    <<<SQL
USE app;
CREATE TABLE IF NOT EXISTS users(
    username VARCHAR(255) NOT NULL,
    hash VARCHAR(60) NOT NULL,
    PRIMARY KEY (username)
)
SQL
);

$insertSampleUser = sprintf(
    'INSERT INTO users (username, hash) VALUES ("%s", "%s")',
    'admin',
    password_hash('admin', PASSWORD_DEFAULT),
);

$pdo->exec($insertSampleUser);
